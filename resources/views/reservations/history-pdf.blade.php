<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Historial de rentas - RoomHub</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 11px; color: #111827; }
        h1 { font-size: 18px; margin-bottom: 6px; }
        .text-muted { color: #6b7280; font-size: 10px; }
        table { width: 100%; border-collapse: collapse; margin-top: 8px; }
        th, td { padding: 5px 4px; border-bottom: 1px solid #e5e7eb; }
        th { background: #f3f4f6; text-align: left; font-size: 10px; }
        .text-right { text-align: right; }
        .small { font-size: 10px; }
    </style>
</head>
<body>
    <h1>Historial de rentas</h1>
    <div class="text-muted">
        Huésped: {{ $user->name }} ({{ $user->email }})<br>
        Generado: {{ now()->format('d/m/Y H:i') }}
    </div>

    <table>
        <thead>
            <tr>
                <th>Alojamiento</th>
                <th>Entrada</th>
                <th>Salida</th>
                <th>Tipo</th>
                <th>Estado</th>
                <th class="text-right">Total (MXN)</th>
            </tr>
        </thead>
        <tbody>
            @forelse($reservations as $r)
                <tr>
                    <td class="small">
                        {{ $r->apartment->title ?? 'Alojamiento eliminado' }}
                        @if($r->apartment)
                            <div class="text-muted small">{{ $r->apartment->address }}</div>
                        @endif
                    </td>
                    <td class="small">{{ $r->check_in?->format('d/m/Y') ?? '—' }}</td>
                    <td class="small">{{ $r->check_out?->format('d/m/Y') ?? '—' }}</td>
                    <td class="small">{{ $r->rent_type === 'month' ? 'Por mes' : 'Por día' }}</td>
                    <td class="small">{{ ucfirst($r->status) }}</td>
                    <td class="text-right small">${{ number_format($r->total_amount, 2) }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="small">No hay reservas registradas.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>

