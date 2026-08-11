<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Recordatorio de cita</title>
</head>
<body>
    <h2>Recordatorio de cita</h2>
    <p>Hola {{ $appointment->client->name ?? 'cliente' }},</p>
    <p>Te recordamos que tienes una cita programada para el {{ $appointment->appointment_date->format('d/m/Y') }} a las {{ $appointment->start_time }}.</p>
    <p>Servicio: <strong>{{ $appointment->service->name }}</strong></p>
    <p>Empleado: <strong>{{ $appointment->employee->name }} {{ $appointment->employee->last_name }}</strong></p>
</body>
</html>
