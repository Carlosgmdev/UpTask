<?php include_once __DIR__.'/header_dashboard.php'; ?>
<div class="contenedor-sm">
    <?php include_once __DIR__ . '/../templates/alertas.php' ?>
    <form class="formulario" method="POST" action="/cambiar-password">
        <div class="campo">
            <label for="password"><ion-icon name="key-outline"></ion-icon></label>
            <input type="password" name="password" id="password" placeholder="Tu Contraseña.">
        </div>
        <input type="submit" value="Actualizar Contraseña" class="boton-actualizar">
    </form>
</div>
<?php include_once __DIR__.'/footer_dashboard.php'; ?>