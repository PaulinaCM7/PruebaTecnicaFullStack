<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reserva Confirmada</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f9fafb;
            color: #111827;
            padding: 30px;
        }

        .container {
            background: white;
            padding: 30px;
            border-radius: 16px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            max-width: 600px;
            margin: 0 auto;
        }

        .header {
            text-align: center;
        }

        .header img {
            max-width: 120px;
            margin-bottom: 20px;
        }

        .cta {
            background-color: #3b82f6;
            color: white;
            padding: 12px 24px;
            border-radius: 8px;
            text-decoration: none;
            display: inline-block;
            margin-top: 20px;
        }

        .footer {
            font-size: 14px;
            margin-top: 40px;
            text-align: center;
            color: #6b7280;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <img src="{{ $message->embed(public_path('images/narantilogo.png')) }}" alt="Reserva exitosa">
            <h1>🎉 ¡Tu reserva está confirmada!</h1>
        </div>

        <p>Hola {{ $reservation->user->name }},</p>

        <p>Gracias por reservar con nosotros. Aquí están los detalles de tu reserva:</p>

        <ul>
            <li><strong>📍 Medio:</strong> {{ $reservation->media->name }}</li>
            <li><strong>📅 Desde:</strong> {{ $reservation->start_date }}</li>
            <li><strong>📅 Hasta:</strong> {{ $reservation->end_date }}</li>
            <li><strong>💰 Total:</strong> ${{ number_format($reservation->total, 2) }}</li>
        </ul>

        <p class="footer">Gracias por usar nuestra plataforma 🙌</p>
    </div>
</body>
</html>
