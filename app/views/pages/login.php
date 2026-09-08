<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Iniciar sesión</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- CSS -->
    <link rel="stylesheet" href="<?= URL  . '/css/login.css'?>">
</head>
<body>

<div class="login-page">

    <!-- OVERLAY OSCURO -->
    <div class="overlay"></div>

    <!-- CONTENEDOR LOGIN -->
    <div class="login-container">

        <!-- BRAND / LOGO -->
        <div class="login-header">
            <h1>Dashboard Empresarial</h1>
            <p>Acceso al sistema</p>
        </div>

        <!-- FORMULARIO -->
        <form class="login-form" method="post" action="/plataformaDect/inicio/iniciarSesion">

            <div class="form-group">
                <label for="correo">Correo electrónico</label>
                <input
                    type="email"
                    id="correo"
                    name="correo"
                    placeholder="usuario@empresa.com"
                    required
                >
            </div>

            <div class="form-group">
                <label for="contrasena">Contraseña</label>
                <input
                    type="password"
                    id="contrasena"
                    name="contrasena"
                    placeholder="••••••••"
                    required
                >
            </div>

            <button type="submit" name="submit" class="btn-login">
                Iniciar sesión
            </button>
            

        </form>

        <!-- FOOTER LOGIN -->
        <div class="login-footer">
            <span>© <?= date('Y') ?> Empresa</span>
        </div>

    </div>

</div>

</body>
</html>
