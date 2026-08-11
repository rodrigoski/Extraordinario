<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Confirmación de cita</title>
</head>
<body style="font-family: Arial, sans-serif; background: #f3f4f6; margin: 0; padding: 24px;">
    <div style="max-width: 600px; margin: 0 auto; background: #ffffff; border-radius: 8px; padding: 24px;">
        <h1 style="color: #1f2937; font-size: 24px; margin: 0 0 16px;">BarberManager</h1>
        <h2 style="color: #1f2937; font-size: 18px;">Confirmación de tu cita</h2>
        <p>Hola {{ $appointment->client->name ?? 'cliente' }},</p>
        <p>Tu cita ha sido registrada correctamente.</p>

        <ul>
            <li><strong>Servicio:</strong> {{ $appointment->service->name }}</li>
            <li><strong>Empleado:</strong> {{ $appointment->employee->name }} {{ $appointment->employee->last_name }}</li>
            <li><strong>Fecha:</strong> {{ $appointment->appointment_date->format('d/m/Y') }}</li>
            <li><strong>Hora:</strong> {{ $appointment->start_time }} - {{ $appointment->end_time }}</li>
            <li><strong>Precio:</strong> ${{ number_format($appointment->service->price, 2) }}</li>
            <li><strong>Estado:</strong> {{ ucfirst($appointment->status) }}</li>
        </ul>

        <p>Adjuntamos un PDF con el comprobante de tu cita.</p>
    </div>
</body>
</html>
