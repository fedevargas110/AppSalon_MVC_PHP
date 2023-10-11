<h1 class="nombre-pagina">Nuevo Servicio</h1>
<p class="descripcion-pagina">Crea un Nuevo Servicio</p>

<?php include_once __DIR__ . '/../templates/alertas.php' ?>

<div class="barra">
    <p>Hola: <?php echo $nombre; ?></p>

    <a href="/logout" class="barra boton"> Cerrar Sesión</a>
</div>

<form action="/servicios/crear" method="POST" class="formulario"> 
    <?php include_once __DIR__ . '/formulario.php' ?>
    <input type="submit" class="boton" value="Guardar Servicio">
</form>