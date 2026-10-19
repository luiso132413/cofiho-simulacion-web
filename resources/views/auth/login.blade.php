
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar sesión | COFIHO</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f3f4f6;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
        }
        .login {
            background: white;
            padding: 35px;
            border-radius: 12px;
            width: 350px;
            max-width: calc(100% - 60px);
            box-shadow: 0 4px 20px #00000015;
        }
        h2 { text-align: center; }
        input {
            width: 100%;
            padding: 12px;
            margin: 8px 0 15px;
            box-sizing: border-box;
        }
        button {
            width: 100%;
            padding: 12px;
            background: #1d4ed8;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
        }
        .error { color: #dc2626; }
    </style>
</head>
<body>
    <div class="login">
        <h2>COFIHO V2</h2>
        <p style="text-align:center">Inicio de sesión</p>

        @if ($errors->any())
            <p class="error">{{ $errors->first() }}</p>
        @endif

        <form method="POST" action="{{ route('login.submit') }}">
            @csrf

            <label for="email">Correo electrónico</label>
            <input type="email" id="email" name="email"
                   value="{{ old('email') }}" required autofocus>

            <label for="password">Contraseña</label>
            <input type="password" id="password"
                   name="password" required>

            <button type="submit">Ingresar</button>
        </form>
    </div>
</body>
</html>
