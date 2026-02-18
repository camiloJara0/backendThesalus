<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Código de verificación</title>
    <style>
    body {
        font-family: Arial, sans-serif;
        background-color: #f4f6f8;
        margin: 0;
        padding: 0;
    }

    .container {
        max-width: 600px;
        margin: 40px auto;
        background: #ffffff;
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        padding: 30px;
    }

    h2 {
        color: #333333;
        margin-bottom: 10px;
    }

    p {
        color: #555555;
        font-size: 15px;
        line-height: 1.5;
    }

    .code-box {
        margin: 20px 0;
        padding: 15px;
        background: #eaf4ff;
        border: 1px solid #326872;
        border-radius: 6px;
        text-align: center;
    }

    .code {
        font-size: 28px;
        font-weight: bold;
        color: #326872;
        letter-spacing: 4px;
    }

    .footer {
        margin-top: 30px;
        font-size: 12px;
        color: #888888;
        text-align: center;
    }
    </style>
</head>

<body>
    <div class="container">
        <h2>Hola {{ $correo }} !</h2>
        <p>Tu código de verificación es:</p>
        <div class="code-box">
            <span class="code">{{ $codigo }}</span>
        </div>
        <p>Ingresa este código en el sistema para continuar con la creación o actualización de tu contraseña.</p>
        <div class="footer">
            <p>Este correo también ha sido enviado al administrador si se trata de un nuevo usuario.</p>
            <p>Si no solicitaste este cambio, por favor ignora este mensaje.</p>
        </div>
    </div>
</body>

</html>