<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Apartment;
use App\Models\Reservation;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ReportController extends Controller
{
    /**
     * Página de finanzas: filtros, ingresos por dueño, comisión, pendientes, export PDF/Excel.
     */
    public function finances(Request $request): View
    {
        $from = $request->date('from') ?? now()->copy()->startOfMonth();
        $to = ($request->date('to') ?? now())->copy()->endOfDay();
        $fromStart = $from->copy()->startOfDay();
        $toEnd = $to->copy()->endOfDay();

        $paymentMethod = $request->get('payment_method');

        $queryPaid = Reservation::with(['apartment.owner', 'user'])
            ->where('status', 'paid')
            ->whereBetween('created_at', [$fromStart, $toEnd]);
        if ($paymentMethod) {
            $queryPaid->where('payment_method', $paymentMethod);
        }
        $reservationsPaid = $queryPaid->orderByDesc('created_at')->get();

        $totalCents = $reservationsPaid->sum('total_amount_cents');
        $commissionCents = $reservationsPaid->sum(fn ($r) => $r->commission_cents);
        $ownersTotalCents = $reservationsPaid->sum(fn ($r) => $r->owner_amount_cents);

        // Ingresos por dueño (solo reservas pagadas en el periodo)
        $byOwner = $reservationsPaid->groupBy(fn ($r) => $r->apartment?->owner_id)
            ->map(function ($items, $ownerId) {
                $owner = $items->first()?->apartment?->owner;
                return [
                    'owner' => $owner,
                    'owner_id' => $ownerId,
                    'count' => $items->count(),
                    'total_cents' => $items->sum('total_amount_cents'),
                    'commission_cents' => $items->sum(fn ($r) => $r->commission_cents),
                    'owner_amount_cents' => $items->sum(fn ($r) => $r->owner_amount_cents),
                ];
            })
            ->sortByDesc('total_cents')
            ->values();

        // Pagos pendientes (todas las reservas pending, sin filtrar por fecha para visibilidad)
        $pending = Reservation::with(['apartment.owner', 'user'])
            ->where('status', 'pending')
            ->orderByDesc('created_at')
            ->limit(50)
            ->get();

        return view('admin.finances.index', [
            'reservationsPaid' => $reservationsPaid,
            'byOwner' => $byOwner,
            'pending' => $pending,
            'from' => $from,
            'to' => $to,
            'paymentMethod' => $paymentMethod,
            'totalCents' => $totalCents,
            'commissionCents' => $commissionCents,
            'ownersTotalCents' => $ownersTotalCents,
        ]);
    }

    /**
     * Reporte PDF de ingresos (con comisión 10% y filtro por método de pago).
     */
    public function revenue(Request $request)
    {
        $from = $request->date('from') ?: now()->startOfMonth();
        $to = $request->date('to') ?: now();
        $paymentMethod = $request->get('payment_method');

        $fromStart = $from->copy()->startOfDay();
        $toEnd = $to->copy()->endOfDay();

        $query = Reservation::with(['apartment.owner', 'user'])
            ->where('status', 'paid')
            ->whereBetween('created_at', [$fromStart, $toEnd]);
        if ($paymentMethod) {
            $query->where('payment_method', $paymentMethod);
        }
        $reservations = $query->orderBy('created_at')->get();

        $total = $reservations->sum('total_amount_cents') / 100;
        $commissionTotal = $reservations->sum(fn ($r) => $r->commission_cents) / 100;
        $ownersTotal = $reservations->sum(fn ($r) => $r->owner_amount_cents) / 100;

        $pdf = Pdf::loadView('admin.reports.revenue', [
            'reservations' => $reservations,
            'from' => $from,
            'to' => $to,
            'total' => $total,
            'commissionTotal' => $commissionTotal,
            'ownersTotal' => $ownersTotal,
            'paymentMethod' => $paymentMethod,
        ])->setPaper('a4', 'portrait');

        $filename = 'reporte-ingresos-' . $from->format('Ymd') . '-' . $to->format('Ymd') . '.pdf';
        return $pdf->download($filename);
    }

    /**
     * Exportar ingresos a Excel (CSV compatible con Excel).
     */
    public function revenueExcel(Request $request): StreamedResponse
    {
        $from = $request->date('from') ?: now()->startOfMonth();
        $to = $request->date('to') ?: now();
        $paymentMethod = $request->get('payment_method');

        $fromStart = $from->copy()->startOfDay();
        $toEnd = $to->copy()->endOfDay();

        $query = Reservation::with(['apartment.owner', 'user'])
            ->where('status', 'paid')
            ->whereBetween('created_at', [$fromStart, $toEnd]);
        if ($paymentMethod) {
            $query->where('payment_method', $paymentMethod);
        }
        $reservations = $query->orderBy('created_at')->get();

        $filename = 'reporte-ingresos-' . $from->format('Ymd') . '-' . $to->format('Ymd') . '.csv';

        return Response::streamDownload(function () use ($reservations) {
            $out = fopen('php://output', 'w');
            fprintf($out, "\xEF\xBB\xBF"); // UTF-8 BOM for Excel
            fputcsv($out, [
                'Fecha', 'Alojamiento', 'Dueño', 'Cliente', 'Email', 'Tipo', 'Entrada', 'Salida',
                'Total (MXN)', 'Comisión 10% (MXN)', 'Dueño (MXN)', 'Método de pago',
            ], ';');
            foreach ($reservations as $r) {
                fputcsv($out, [
                    $r->created_at->format('d/m/Y H:i'),
                    $r->apartment->title ?? '—',
                    $r->apartment->owner->name ?? '—',
                    $r->user->name ?? '—',
                    $r->user->email ?? '—',
                    $r->rent_type === 'month' ? 'Por mes' : 'Por día',
                    $r->check_in?->format('d/m/Y') ?? '—',
                    $r->check_out?->format('d/m/Y') ?? '—',
                    number_format($r->total_amount_cents / 100, 2),
                    number_format($r->commission_cents / 100, 2),
                    number_format($r->owner_amount_cents / 100, 2),
                    Reservation::paymentMethodLabel($r->payment_method ?? 'stripe'),
                ], ';');
            }
            $totalCents = $reservations->sum('total_amount_cents');
            $commissionCents = $reservations->sum(fn ($r) => $r->commission_cents);
            $ownerCents = $reservations->sum(fn ($r) => $r->owner_amount_cents);
            fputcsv($out, [], ';');
            fputcsv($out, ['TOTAL', '', '', '', '', '', '', '', number_format($totalCents / 100, 2), number_format($commissionCents / 100, 2), number_format($ownerCents / 100, 2), ''], ';');
            fclose($out);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }

    /**
     * Reporte PDF de departamentos y sus reservas/ingresos.
     */
    public function apartments(Request $request)
    {
        $query = Apartment::with('owner')
            ->withCount(['reservations as reservations_paid_count' => function ($q) {
                $q->where('status', 'paid');
            }])
            ->withSum(['reservations as revenue_total_cents' => function ($q) {
                $q->where('status', 'paid');
            }], 'total_amount_cents');

        if ($request->filled('owner_id')) {
            $query->where('owner_id', $request->owner_id);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('q')) {
            $s = $request->q;
            $query->where(function ($q) use ($s) {
                $q->where('title', 'like', "%{$s}%")
                  ->orWhere('address', 'like', "%{$s}%");
            });
        }

        $apartments = $query->orderBy('id')->get();

        $totalRevenue = $apartments->sum(fn ($a) => ($a->revenue_total_cents ?? 0) / 100);
        $totalReservations = $apartments->sum('reservations_paid_count');

        $pdf = Pdf::loadView('admin.reports.apartments', [
            'apartments' => $apartments,
            'totalRevenue' => $totalRevenue,
            'totalReservations' => $totalReservations,
        ])->setPaper('a4', 'landscape');

        return $pdf->download('reporte-departamentos.pdf');
    }
}
