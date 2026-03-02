<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Permiso Aprobado</title>
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
            color: #2c3e50;
            margin-bottom: 15px;
        }

        p {
            color: #555555;
            font-size: 15px;
            line-height: 1.6;
        }

        .code-box {
            margin: 25px 0;
            padding: 18px;
            background: #eaf4ff;
            border: 1px solid #3498db;
            border-radius: 6px;
            text-align: center;
        }

        .code {
            font-size: 28px;
            font-weight: bold;
            color: #3498db;
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
        <h2>¡Hola {{ $correo }}!</h2>
        <p>Tu solicitud de acceso ha sido <strong>aprobada</strong>.</p>
        <p>Para ingresar al módulo autorizado, utiliza el siguiente código de acceso único:</p>
        
        <div class="code-box">
            <span class="code">{{ $codigo }}</span>
        </div>
        
        <p>Ingresa este código en el sistema como contraseña para acceder al módulo con el permiso otorgado.</p>
        
        <div class="footer">
            <p>Este código es de un solo uso y tiene validez limitada. Por favor, utilízalo cuanto antes.</p>
        </div>
    </div>
</body>


</html>