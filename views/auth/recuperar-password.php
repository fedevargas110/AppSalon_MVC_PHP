<h1 class="nombre-pagina">Restablecer Password</h1>
<p class="descripcion-pagina">Coloca tu nueva password a continuación.</p>

<?php  include_once __DIR__ . '/../templates/alertas.php'; ?>

<?php if($error == true) {
    return null;
} ?>


<form class="formulario" method="POST">

    <div class="campo">
        <label for="password">Password</label>
        <input 
            type="password"
            id="password"
            placeholder="Tu Nueva Password"
            name="password"
        />
    </div>

    <input type="submit" class="boton" value="Cambiar Contraseña">
</form>

<div class="acciones">
    <a href="/">¿Ya tienes cuenta? Iniciar Sesión</a>
</div>