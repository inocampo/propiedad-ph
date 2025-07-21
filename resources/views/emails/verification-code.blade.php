<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Código de Verificación</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .container {
            background-color: #f9f9f9;
            border-radius: 5px;
            padding: 20px;
            border: 1px solid #ddd;
        }
        .header {
            text-align: center;
            padding-bottom: 20px;
            border-bottom: 1px solid #eee;
            margin-bottom: 20px;
        }
        .code {
            font-size: 24px;
            font-weight: bold;
            text-align: center;
            padding: 15px;
            background-color: #e9f5ff;
            border-radius: 5px;
            margin: 20px 0;
            letter-spacing: 5px;
        }
        .footer {
            font-size: 12px;
            text-align: center;
            margin-top: 30px;
            color: #777;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Conjunto Residencial Gualanday</h1>
            <p>Verificación de Identidad</p>
        </div>
        
        <p>Estimado(a) Residente,</p>
        
        <p>Hemos recibido una solicitud para acceder a la información de su apartamento. Para continuar con este proceso, por favor utilice el siguiente código de verificación:</p>
        
        <div class="code">{{ $codigo }}</div>
        
        <p>Este código es válido por 30 minutos. Si usted no solicitó este código, por favor ignore este correo.</p>
        
        <p>Gracias,<br>
        Administración<br>
        Conjunto Residencial Gualanday</p>
        
        <div class="footer">
            <p>Este es un correo automático, por favor no responda a este mensaje.</p>
            <p>© {{ date('Y') }} Conjunto Residencial Gualanday. Todos los derechos reservados.</p>
        </div>
    </div>
</body>
</html>
