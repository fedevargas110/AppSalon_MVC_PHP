<h1 class="nombre-pagina">Crear Nueva Cita</h1>
<p class="descripcion-pagina">Elige tus servicios a continuación.</p>

<div class="barra">
    <p>Hola: <?php echo $nombre; ?></p>

    <a href="/logout" class="barra boton"> Cerrar Sesión</a>
</div>


<div class="app">
    <nav class="tabs">
        <button class="actual" type="button" data-paso="1">Servicios</button>
        <button type="button" data-paso="2">Informacíon Citas</button>
        <button type="button" data-paso="3">Resumen</button>
    </nav>

    <div id="part-1" class="seccion">
        <h2>Servicios</h2>
        <p class="text-center">Elige tus servicios a continuación</p>
        <div id="servicios" class="listado-servicios"></div>
    </div>
    <div id="part-2" class="seccion">
        <h2>Datos de Citas</h2>
        <p class="text-center">Elige la fecha y la hora para agendar la cita</p>

        <form class="formulario">
            <div class="campo">
                <label for="nombre">Tu Nombre</label>
                <input 
                    type="text"
                    id="nombre"
                    name="nombre"
                    value="<?php echo $nombre;?>"
                    disabled
                />
            </div>
            <div class="campo">
                <label for="fecha">Fecha</label>
                <input 
                    type="date"
                    id="fecha"  
                    min="<?php echo date('Y-m-d', strtotime( '+1 day')) ; ?>"
                />
            </div>
            <div class="campo">
                <label for="hora">Hora</label>
                <input 
                    type="time"
                    id="hora"
                />
            </div>
            <input type="hidden" id="id" value="<?php echo $id; ?>">
        </form>
    </div>
    <div id="part-3" class="seccion contenido-resumen">
        <h2>Resumen</h2>
        <p class="text-center">Verifica que la información sea correcta antes de continuar</p>
    </div>

    <div class="paginacion">
        <button id="anterior" class="boton">&laquo; Anterior</button>
        <button id="siguiente" class="boton">Siguiente &raquo;</button>
    </div>
</div>

<?php $script = "
    <script src='https://cdn.jsdelivr.net/npm/sweetalert2@11'></script>
    <script src = 'build/js/app.js'></script>
    "; 
?>