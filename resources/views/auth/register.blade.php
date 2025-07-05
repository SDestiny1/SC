<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Registro</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        body {
            background-color: #0e1c15;
            color: #fff;
            font-family: 'Segoe UI', sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .form-box {
            background-color: #1b2a24;
            padding: 2rem;
            border-radius: 10px;
            width: 100%;
            max-width: 400px;
            box-shadow: 0 0 10px #0fa15f33;
        }
        h2 {
            text-align: center;
            margin-bottom: 1.5rem;
            color: #0fa15f;
        }
        input {
            width: 100%;
            padding: 0.6rem;
            margin-bottom: 1rem;
            background-color: #22352d;
            border: 1px solid #2f4d41;
            color: #fff;
            border-radius: 5px;
        }
        .btn {
            background-color: #0fa15f;
            border: none;
            color: #fff;
            padding: 0.6rem;
            width: 100%;
            border-radius: 5px;
            cursor: pointer;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <form method="POST" action="{{ route('register') }}" class="form-box">
        @csrf
        <h2>Registro de Usuario</h2>

        <input type="text" name="name" placeholder="Nombre" required>
        <input type="email" name="email" placeholder="Correo Electrónico" required>
        <input type="password" name="password" placeholder="Contraseña" required>
        <input type="password" name="password_confirmation" placeholder="Confirmar Contraseña" required>

        <button type="submit" class="btn">Registrarse</button>
    </form>
</body>
</html>
