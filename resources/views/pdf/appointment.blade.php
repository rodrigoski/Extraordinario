<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Recordatorio de cita</title>
    <style>
        body { font-family: Arial, sans-serif; color: #1f2937; font-size: 14px; }
        .header { text-align: center; border-bottom: 3px solid #111827; padding-bottom: 12px; margin-bottom: 24px; }
        .header h1 { font-size: 26px; margin: 0; letter-spacing: 2px; }
        .header p { margin: 4px 0 0; color: #6b7280; }
        .info { margin-bottom: 24px; }
        .info div { margin-bottom: 6px; }
        .box { border: 1px solid #e5e7eb; border-radius: 6px; padding: 16px; margin-bottom: 24px; }
        .box h2 { font-size: 14px; text-transform: uppercase; color: #6b7280; margin: 0 0 12px; }
        .footer { text-align: center; color: #9ca3af; font-size: 11px; border-top: 1px solid #e5e7eb; padding-top: 12px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>BARBERMANAGER</h1>
        <p>Recordatorio de Cita</p>
    </div>

    <div class="box" style="background:#fefce8; border-color:#fde047;">
        <h2 style="color:#854d0e;">Recordatorio</h2>
        <div><strong>{{ $appointment->client->name }},</strong> te recordamos tu próxima cita. Por favor llega 10 minutos antes de la hora agendada. Si necesitas reagendar o cancelar, contáctanos con anticipación.</div>
    </div>

    <div class="info">
        <div><strong>Número de cita:</strong> #{{ str_pad($appointment->id, 4, '0', STR_PAD_LEFT) }}</div>
    </div>

    <div class="box">
        <h2>Cliente</h2>
        <div><strong>Nombre:</strong> {{ $appointment->client->name }} {{ $appointment->client->last_name }}</div>
        <div><strong>Teléfono:</strong> {{ $appointment->client->phone }}</div>
        <div><strong>Correo:</strong> {{ $appointment->client->email }}</div>
    </div>

    <div class="box">
        <h2>Detalle del servicio</h2>
        <div><strong>Servicio:</strong> {{ $appointment->service->name }}</div>
        <div><strong>Empleado:</strong> {{ $appointment->employee->name }} {{ $appointment->employee->last_name }}</div>
        <div><strong>Fecha:</strong> {{ $appointment->appointment_date->format('d/m/Y') }}</div>
        <div><strong>Hora:</strong> {{ $appointment->start_time }} - {{ $appointment->end_time }}</div>
        <div><strong>Duración:</strong> {{ $appointment->service->duration }} minutos</div>
        <div><strong>Precio:</strong> ${{ number_format($appointment->service->price, 2) }}</div>
    </div>

    <div class="info">
        <div><strong>Estado:</strong> {{ ucfirst($appointment->status) }}</div>
        @if($appointment->notes)
            <div><strong>Notas:</strong> {{ $appointment->notes }}</div>
        @endif
    </div>

    <div class="footer">
        Gracias por confiar en BarberManager
    </div>
</body>
</html>
