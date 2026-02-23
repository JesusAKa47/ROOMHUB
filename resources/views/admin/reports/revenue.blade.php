<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Reporte de ingresos - RoomHub</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; color: #111827; }
        h1 { font-size: 20px; margin-bottom: 4px; }
        h2 { font-size: 14px; margin: 12px 0 4px; }
        .text-muted { color: #6b7280; font-size: 11px; }
        .header { margin-bottom: 16px; border-bottom: 1px solid #e5e7eb; padding-bottom: 8px; }
        table { width: 100%; border-collapse: collapse; margin-top: 8px; }
        th, td { padding: 6px 4px; border-bottom: 1px solid #e5e7eb; }
        th { background: #f3f4f6; text-align: left; font-size: 11px; }
        .text-right { text-align: right; }
        .small { font-size: 11px; }
        .mt-1 { margin-top: 4px; }
        .mt-2 { margin-top: 8px; }
        .mt-4 { margin-top: 16px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Reporte de ingresos</h1>
        <div class="text-muted">
            Periodo: {{ $from->format('d/m/Y') }} al {{ $to->format('d/m/Y') }}<br>
            @if(!empty($paymentMethod))
                Método de pago: {{ \App\Models\Reservation::paymentMethodLabel($paymentMethod) }}<br>
            @endif
            Generado: {{ now()->format('d/m/Y H:i') }}
        </div>
    </div>

    <h2>Resumen</h2>
    <div class="small">
        Total de reservas pagadas: <strong>{{ $reservations->count() }}</strong><br>
        Ingreso bruto: <strong>${{ number_format($total ?? 0, 2) }} MXN</strong><br>
        Comisión RoomHub (10%): <strong>${{ number_format($commissionTotal ?? 0, 2) }} MXN</strong><br>
        Ingresos para dueños: <strong>${{ number_format($ownersTotal ?? 0, 2) }} MXN</strong>
    </div>

    <h2 class="mt-4">Detalle de reservas</h2>
    <table>
        <thead>
            <tr>
                <th>Fecha</th>
                <th>Alojamiento</th>
                <th>Cliente</th>
                <th>Tipo</th>
                <th>Entrada</th>
                <th>Salida</th>
                <th>Método</th>
                <th class="text-right">Total</th>
                <th class="text-right">Comisión 10%</th>
                <th class="text-right">Dueño</th>
            </tr>
        </thead>
        <tbody>
            @forelse($reservations as $r)
                <tr>
                    <td>{{ $r->created_at->format('d/m/Y') }}</td>
                    <td>
                        {{ $r->apartment->title ?? '—' }}
                        @if($r->apartment && $r->apartment->owner)
                            <div class="text-muted small mt-1">Anfitrión: {{ $r->apartment->owner->name }}</div>
                        @endif
                    </td>
                    <td class="small">
                        {{ $r->user->name ?? '—' }}<br>
                        <span class="text-muted">{{ $r->user->email ?? '' }}</span>
                    </td>
                    <td class="small">{{ $r->rent_type === 'month' ? 'Por mes' : 'Por día' }}</td>
                    <td class="small">{{ $r->check_in?->format('d/m/Y') ?? '—' }}</td>
                    <td class="small">{{ $r->check_out?->format('d/m/Y') ?? '—' }}</td>
                    <td class="small">{{ \App\Models\Reservation::paymentMethodLabel($r->payment_method ?? 'stripe') }}</td>
                    <td class="text-right small">${{ number_format($r->total_amount, 2) }}</td>
                    <td class="text-right small">${{ number_format($r->commission_cents / 100, 2) }}</td>
                    <td class="text-right small">${{ number_format($r->owner_amount_cents / 100, 2) }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="10" class="small">No hay reservas pagadas en el periodo seleccionado.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
