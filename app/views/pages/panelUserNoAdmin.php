<?php require_once APP . '/views/inc/headerUser.php'; ?>
<?php require_once APP . '/views/inc/sidebar.php'; ?>

<div class="content-base content-center">

    <!-- =============================
         BLOQUE SUPERIOR (60% ALTO)
    ============================== -->
    <div class="top-panels">

        <!-- =====================
             USUARIO LOGUEADO
        ====================== -->
        <section class="panel panel-user">
            <h2>Perfil del usuario</h2>

            <div class="user-card">
                <div class="user-row">
                    <span class="label">Correo</span>
                    <span class="value">
                        <?= htmlspecialchars($_SESSION['usuario']['correo'] ?? '') ?>
                    </span>
                </div>

                <div class="user-row">
                    <span class="label">Rol</span>
                    <span class="value">
                        <?= htmlspecialchars($_SESSION['usuario']['rol'] ?? '') ?>
                    </span>
                </div>

                <div class="user-row">
                    <span class="label">ID</span>
                    <span class="value">
                        <?= htmlspecialchars($_SESSION['usuario']['id_usuario'] ?? '') ?>
                    </span>
                </div>

                <form method="POST" action="/Dashboards/inicio/editarUsuario" class="inline-form">
                <input type="hidden" name="id_usuario" value="<?= $_SESSION['usuario']['id_usuario'] ?? '' ?>">
                <input type="hidden" name="correo" value="<?= $_SESSION['usuario']['correo'] ?? '' ?>">
                <button type="submit" class="btn-edit">
                Editar
                </button>
                </form>
            </div>
        </section>

</div>

</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const yearInput = document.getElementById('yearInput');
    const btnPrev = document.getElementById('yearPrev');
    const btnNext = document.getElementById('yearNext');

    const MIN_YEAR = 2000;
    const MAX_YEAR = new Date().getFullYear();

    function updateYear(delta) {
        let year = parseInt(yearInput.value, 10);

        if (isNaN(year)) {
            year = MAX_YEAR;
        }

        year += delta;

        if (year < MIN_YEAR) year = MIN_YEAR;
        if (year > MAX_YEAR) year = MAX_YEAR;

        // 🔥 CAMBIO INMEDIATO EN PANTALLA
        yearInput.value = year;
    }

    btnPrev.addEventListener('click', () => updateYear(-1));
    btnNext.addEventListener('click', () => updateYear(1));
});
</script>


<?php require_once APP . '/views/inc/footer.php'; ?>
