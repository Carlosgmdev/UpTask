<?php include_once __DIR__.'/header_dashboard.php'; ?>
    <?php if(count($proyectos) === 0): ?>
        <div class="no-proyectos">
            <h3>Áun no tienes proyectos, comienza creando uno. 😉</h3>
            <a href="/crear-proyecto">Crear Proyecto Ahora</a>
        </div>
    <?php endif; ?>
    <div class="proyectos">
        <?php foreach($proyectos as $proyecto): ?>
            <a class="proyecto" href="/proyecto?id=<?php echo $proyecto->url; ?>"><?php echo $proyecto->proyecto ?></a>
        <?php endforeach; ?>
    </div>

<?php include_once __DIR__.'/footer_dashboard.php'; ?>

