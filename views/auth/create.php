<div class="contenedor crear">
<?php include_once __DIR__ .'/../templates/nombre-sitio.php'; ?>
    <div class="contenedor-sm">
        <p class="descripcion-pagina">Crear Cuenta</p>
        <?php include_once __DIR__ . '/../templates/alertas.php'; ?>
        <form action="/create" method="POST" class="formulario">
            <div class="campo">
                <label for="nombre"><ion-icon name="person-outline"></ion-icon></label>
                <input type="text" name="nombre" id="nombre" placeholder="Tu Nombre." value="<?php echo $usuario->nombre; ?>">
            </div>
            <div class="campo">
                <label for="email"><ion-icon name="mail-outline"></ion-icon></label>
                <input type="text" name="email" id="email" placeholder="Tu Email." value="<?php echo $usuario->email; ?>">
            </div>
            <div class="campo">
                <label for="password"><ion-icon name="key-outline"></ion-icon></label>
                <input type="password" name="password" id="password" placeholder="Tu Contraseña.">
            </div>
            <div class="campo">
                <label for="password2"><ion-icon name="repeat-outline"></ion-icon></label>
                <input type="password" name="password2" id="password2" placeholder="Repetir Contraseña.">
            </div>
            <input type="submit" class="boton" value="Registrate">
        </form>
        <div class="acciones">
            <a href="/">¿Ya tienes una cuenta?, Iniciar Sesión.</a>
            <a href="/forgot">Olvidé mi contraseña.</a>
        </div>
    </div>
</div>

<?php
    $script = '<script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
               <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>';
?>