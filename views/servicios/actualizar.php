<h1 class="nombre-pagina">Actualizar Servicios</h1>
<p class="descripcion-pagina">Modifica los valores del formulario</p>

<?php include_once __DIR__ . '/../templates/alertas.php' ?>

<div class="barra">
    <p>Hola: <?php echo $nombre; ?></p>

    <a href="/logout" class="barra boton"> Cerrar Sesión</a>
</div>

<form method="POST" class="formulario"> 
    <?php include_once __DIR__ . '/formulario.php' ?>
    <input type="submit" class="boton" value="Actualizar Servicio">
</form>