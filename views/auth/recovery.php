<div class="contenedor recovery">
<?php include_once __DIR__ .'/../templates/nombre-sitio.php'; ?>
    <div class="contenedor-sm">
        <p class="descripcion-pagina">Crea tu nueva contraseña.</p>
        <?php include_once __DIR__ . '/../templates/alertas.php'; ?>
        <?php if($mostrar): ?>
            <form method="POST" class="formulario">
                <div class="campo">
                    <label for="password"><ion-icon name="key-outline"></ion-icon></label>
                    <input type="password" name="password" id="password" placeholder="Tu Nueva Contraseña.">
                </div>
                <input type="submit" class="boton" value="Actualizar contraseña">
            </form>
        <?php endif; ?>
    </div>
</div>

<?php
    $script = '<script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
               <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>';
?>