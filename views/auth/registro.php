<main class="auth">
    <h2 class="auth__heading"><?php echo $titulo ?></h2>
    <p class="auth__texto">Registrate en DevWebCamp</p>

    <?php require_once __DIR__ . "/../templates/alertas.php" ?>

    <form class="formulario" method="POST" action="/registro">
        <div class="formulario__campo">
            <label for="nombre" class="formulario__label">Nombre</label>
            <input
                type="text"
                class="formulario__input"
                name="nombre"
                placeholder="Tu Nombre"
                id="nombre"
                value="<?php echo $usuario->nombre; ?>">
        </div>

        <div class="formulario__campo">
            <label for="apellido" class="formulario__label">Apellido</label>
            <input
                type="text"
                class="formulario__input"
                name="apellido"
                placeholder="Tu Apellido"
                id="apellido"
                value="<?php echo $usuario->apellido; ?>">
        </div>

        <div class="formulario__campo">
            <label for="email" class="formulario__label">Email</label>
            <input
                type="email"
                class="formulario__input"
                name="email"
                placeholder="Tu Email"
                id="email"
                value="<?php echo $usuario->email; ?>">
        </div>

        <div class="formulario__campo">
            <label for="password" class="formulario__label">Password</label>
            <input
                type="password"
                class="formulario__input"
                name="password"
                placeholder="Tu Password"
                id="password">
        </div>

        <div class="formulario__campo">
            <label for="password2" class="formulario__label">Repitir Password</label>
            <input
                type="password"
                class="formulario__input"
                name="password2"
                placeholder="Repite Tu Password"
                id="password2">
        </div>

        <input type="submit" class="formulario__submit" value="Crear Cuenta">
    </form>

    <div class="acciones">
        <a href="/login" class="acciones__enlace">¿Ya tienes una cuenta? Inicia Sesion</a>
        <a href="/olvide" class="acciones__enlace">¿Olvidaste tu Password?</a>
    </div>
</main>