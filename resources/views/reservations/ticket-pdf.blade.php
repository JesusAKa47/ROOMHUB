<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Ticket de reserva - RoomHub</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 11px; color: #2C1810; margin: 0; padding: 24px; }
        .ticket { max-width: 520px; margin: 0 auto; border: 2px solid #6F4E37; border-radius: 8px; overflow: hidden; }
        .ticket-header { background: #6F4E37; color: #fff; padding: 14px 18px; text-align: center; }
        .ticket-header h1 { margin: 0; font-size: 18px; font-weight: 700; }
        .ticket-header .sub { font-size: 10px; opacity: .9; margin-top: 4px; }
        .ticket-body { padding: 20px 18px; background: #FAF6F0; }
        .ticket-body table { width: 100%; border-collapse: collapse; }
        .ticket-body .row td { padding: 6px 0; border-bottom: 1px solid #E8E2DA; }
        .ticket-body .row:last-child td { border-bottom: none; }
        .ticket-body .label { width: 38%; color: #6B5344; }
        .ticket-body .value { font-weight: 600; color: #2C1810; text-align: right; }
        .ticket-body .row.total td { border-top: 2px solid #6F4E37; padding-top: 12px; margin-top: 8px; font-size: 13px; }
        .ticket-body .row.total .value { font-size: 15px; color: #6F4E37; }
        .ticket-footer { background: #EDE4D8; padding: 10px 18px; text-align: center; font-size: 9px; color: #6B5344; }
        .status-badge { display: inline-block; padding: 2px 8px; border-radius: 4px; font-size: 10px; font-weight: 600; }
        .status-paid { background: #d1fae5; color: #065f46; }
        .status-pending { background: #fef3c7; color: #92400e; }
        .generated { margin-top: 12px; font-size: 9px; color: #6B5344; }
    </style>
</head>
<body>
    <div class="ticket">
        <div class="ticket-header">
            <h1>RoomHub — Ticket de reserva</h1>
            <div class="sub">Comprobante de reserva · {{ $reservation->isPaid() ? 'Pagado' : 'Pendiente de pago' }}</div>
        </div>
        <div class="ticket-body">
            <table>
                <tr class="row">
                    <td class="label">N.º de reserva</td>
                    <td class="value">#{{ $reservation->id }}</td>
                </tr>
                <tr class="row">
                    <td class="label">Folio de transacción</td>
                    <td class="value">{{ $reservation->transaction_folio }}</td>
                </tr>
                <tr class="row">
                    <td class="label">Huésped</td>
                    <td class="value">{{ $reservation->user->name ?? '—' }}</td>
                </tr>
                <tr class="row">
                    <td class="label">Alojamiento</td>
                    <td class="value">{{ $reservation->apartment->title ?? '—' }}</td>
                </tr>
                <tr class="row">
                    <td class="label">Dirección</td>
                    <td class="value">{{ $reservation->apartment->address ?? '—' }}</td>
                </tr>
                <tr class="row">
                    <td class="label">Entrada</td>
                    <td class="value">{{ $reservation->check_in->format('d/m/Y') }}</td>
                </tr>
                <tr class="row">
                    <td class="label">Salida</td>
                    <td class="value">{{ $reservation->check_out->format('d/m/Y') }}</td>
                </tr>
                <tr class="row">
                    <td class="label">Tipo de renta</td>
                    <td class="value">{{ $reservation->rent_type === 'month' ? 'Por mes' : 'Por día' }}</td>
                </tr>
                <tr class="row">
                    <td class="label">Estado</td>
                    <td class="value">
                        <span class="status-badge {{ $reservation->isPaid() ? 'status-paid' : 'status-pending' }}">
                            {{ $reservation->isPaid() ? 'Pagado' : 'Pendiente' }}
                        </span>
                    </td>
                </tr>
                <tr class="row total">
                    <td class="label">Total</td>
                    <td class="value">${{ number_format($reservation->total_amount, 0) }} MXN</td>
                </tr>
            </table>
            <div class="generated">Generado el {{ now()->format('d/m/Y H:i') }} · RoomHub</div>
        </div>
        <div class="ticket-footer">
            Este documento es tu comprobante de reserva. Consérvalo para acordar la entrada con el anfitrión.
        </div>
    </div>
</body>
</html>
