<?php include_once __DIR__.'/header_dashboard.php'; ?>
    <div class="contenedor-sm">
        <div class="contenedor-nueva-tarea">
            <button
                type="button"
                class="agregar-tarea"
                id="agregar-tarea"
            > &#43; Nueva Tarea </button>
        </div>
        <div id="filtros" class="filtros">
            <div class="filtros-inputs">
                <h2>Filtros</h2>
                <div class="campo">
                    <label for="todas">Todas</label>
                    <input type="radio" name="filtro" id="todas" value="" checked>
                </div>
                <div class="campo">
                    <label for="pendientes">Pendientes</label>
                    <input type="radio" name="filtro" id="pendientes" value="0" >
                </div>
                <div class="campo">
                    <label for="completas">Completas</label>
                    <input type="radio" name="filtro" id="completas" value="1" >
                </div>
            </div>
        </div>
        <ul id="listado-tareas" class="listado-tareas"></ul>
    </div>
<?php include_once __DIR__.'/footer_dashboard.php'; ?>

<?php
$script .= '
    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="build/js/tareas.js"></script>
'
?>