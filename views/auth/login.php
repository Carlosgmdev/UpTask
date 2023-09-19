<div class="contenedor login">
<?php include_once __DIR__ .'/../templates/nombre-sitio.php'; ?>
    <div class="contenedor-sm">
        <p class="descripcion-pagina">Iniciar Sesión</p>
        <?php include_once __DIR__ . '/../templates/alertas.php'; ?>
        <form action="/" method="POST" class="formulario">
            <div class="campo">
                <label for="email"><ion-icon name="mail-outline"></ion-icon></label>
                <input type="text" name="email" id="email" placeholder="Tu Email.">
            </div>
            <div class="campo">
                <label for="password"><ion-icon name="key-outline"></ion-icon></label>
                <input type="password" name="password" id="password" placeholder="Tu Contraseña.">
            </div>
            <input type="submit" class="boton" value="Iniciar Sesión">
        </form>
        <div class="acciones">
            <a href="/create">¿No tienes una cuenta?, Registrate aquí.</a>
            <a href="/forgot">Olvide mi contraseña.</a>
        </div>
    </div>
</div>

<?php
    $script = '<script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
               <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>';
?>