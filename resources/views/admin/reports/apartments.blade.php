<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Reporte de inmuebles - RoomHub</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 11px; color: #111827; }
        h1 { font-size: 18px; margin-bottom: 4px; }
        h2 { font-size: 13px; margin: 10px 0 4px; }
        .text-muted { color: #6b7280; font-size: 10px; }
        .header { margin-bottom: 12px; border-bottom: 1px solid #e5e7eb; padding-bottom: 6px; }
        table { width: 100%; border-collapse: collapse; margin-top: 6px; }
        th, td { padding: 5px 4px; border-bottom: 1px solid #e5e7eb; }
        th { background: #f3f4f6; text-align: left; font-size: 10px; }
        .text-right { text-align: right; }
        .small { font-size: 10px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Reporte de inmuebles</h1>
        <div class="text-muted">
            Generado: {{ now()->format('d/m/Y H:i') }}
        </div>
    </div>

    <h2>Resumen</h2>
    <div class="small">
        Total de inmuebles: <strong>{{ $apartments->count() }}</strong><br>
        Reservas pagadas: <strong>{{ $totalReservations }}</strong><br>
        Ingresos acumulados: <strong>${{ number_format($totalRevenue, 2) }} MXN</strong>
    </div>

    <h2>Detalle por inmueble</h2>
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Título</th>
                <th>Dirección</th>
                <th>Anfitrión</th>
                <th>Estado</th>
                <th class="text-right">Renta mensual</th>
                <th class="text-right">Reservas pagadas</th>
                <th class="text-right">Ingresos (MXN)</th>
            </tr>
        </thead>
        <tbody>
            @forelse($apartments as $a)
                <tr>
                    <td class="small">{{ $a->id }}</td>
                    <td class="small">{{ $a->title }}</td>
                    <td class="small">{{ $a->address }}</td>
                    <td class="small">{{ $a->owner->name ?? '—' }}</td>
                    <td class="small">{{ ucfirst($a->status) }}</td>
                    <td class="text-right small">${{ number_format($a->monthly_rent, 2) }}</td>
                    <td class="text-right small">{{ $a->reservations_paid_count ?? 0 }}</td>
                    <td class="text-right small">
                        ${{ number_format(($a->revenue_total_cents ?? 0) / 100, 2) }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="small">No hay inmuebles para mostrar.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>

