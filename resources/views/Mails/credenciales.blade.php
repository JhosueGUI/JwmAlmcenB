<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
</head>
<body>
    <p>Hola, aquí están tus credenciales para acceder al sistema:</p>
    <p>Usuario:{{ $credenciales['username'] }}</p>
    <p>Contraseña: {{ $credenciales['password'] }}</p> 
    <p>Por favor, cambia tu contraseña después de iniciar sesión por primera vez.</p>
</body>
</html>