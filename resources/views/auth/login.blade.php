<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
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
        .login-box {
            background-color: #1b2a24;
            padding: 2rem;
            border-radius: 10px;
            width: 100%;
            max-width: 400px;
            box-shadow: 0 0 10px #0fa15f33;
        }
        .login-box h2 {
            text-align: center;
            margin-bottom: 1.5rem;
            color: #0fa15f;
        }
        .form-group {
            margin-bottom: 1rem;
        }
        label {
            display: block;
            margin-bottom: 0.25rem;
        }
        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 0.6rem;
            background-color: #22352d;
            border: 1px solid #2f4d41;
            color: #fff;
            border-radius: 5px;
        }
        .btn {
            background-color: #0fa15f;
            border: none;
            color: #fff;
            padding: 0.6rem 1rem;
            width: 100%;
            border-radius: 5px;
            cursor: pointer;
            font-weight: bold;
        }
        .btn:hover {
            background-color: #0c864f;
        }
        .error {
            color: #ff4e4e;
            font-size: 0.9rem;
            margin-bottom: 0.5rem;
        }
    </style>
</head>
<body>
    <form method="POST" action="{{ route('login') }}" class="login-box">
    @csrf
    <h2>Iniciar Sesión</h2>

    @if ($errors->any())
        <div class="error">{{ $errors->first() }}</div>
    @endif

    <div class="form-group">
        <label for="correo">Correo Electrónico</label>
        <input id="correo" type="email" name="correo" value="{{ old('correo') }}" required autofocus>
        @error('correo')
            <div class="error">{{ $message }}</div>
        @enderror
    </div>

    <div class="form-group">
        <label for="password">Contraseña</label>
        <input id="password" type="password" name="password" required>
        @error('password')
            <div class="error">{{ $message }}</div>
        @enderror
    </div>

    <div class="form-group">
        <label><input type="checkbox" name="remember"> Recordarme</label>
    </div>

    <button type="submit" class="btn">Ingresar</button>
</form>

</body>
</html>
