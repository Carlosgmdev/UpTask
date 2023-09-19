<div class="contenedor forgot">
<?php include_once __DIR__ .'/../templates/nombre-sitio.php'; ?>
    <div class="contenedor-sm">
        <p class="descripcion-pagina">Olvidé mi contraseña</p>
        <?php include_once __DIR__ . '/../templates/alertas.php'; ?>
        <form action="/forgot" method="POST" class="formulario">
            <div class="campo">
                <label for="email"><ion-icon name="mail-outline"></ion-icon></label>
                <input type="text" name="email" id="email" placeholder="Tu Email.">
            </div>
            <input type="submit" class="boton" value="Recuperar Acceso">
        </form>
        <div class="acciones">
            <a href="/">¿Ya tienes una cuenta?, Iniciar Sesión.</a>
            <a href="/create">¿No tienes una cuenta?, Registrate aquí.</a>
        </div>
    </div>
</div>

<?php
    $script = '<script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
               <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>';
?>