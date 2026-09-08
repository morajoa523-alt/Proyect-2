<?php require_once APP . '/views/inc/headerEditUser.php' ?>
<?php require_once APP . '/views/inc/sidebar.php' ?>


<?php
$session = $_SESSION['usuario'];
$isAdmin = $session['rol'] === 'admin';
$isPropio = $session['id_usuario'] == $_SESSION['user']['idUsuario'];
$puedeEditar = $isPropio;
$verCampo = $_SESSION['user']['verCampoPass'];

?>

<div class="content-base">

  <section class="panel-user">

    <header class="panel-header">
      <h2>Editar usuario</h2>
      <p>
        <?= $puedeEditar
            ? 'Actualiza tu información'
            : 'Vista de información del usuario'
        ?>
      </p>
    </header>

    <form class="form-user" method="post" action="/Dashboards/user/updateUser">

      <input type="hidden" name="id_usuario" value="<?= htmlspecialchars($_SESSION['user']['idUsuario']) ?>">

      <div class="form-grid">


      

        <!-- CORREO -->
        <div class="form-group">
          <label>Correo electrónico</label>
          <input
            type="email"
            name="correo"
            value="<?= htmlspecialchars($_SESSION['user']['datosUsuario']['correo']) ?>"
            <?= $puedeEditar ? 'required' : 'readonly disabled' ?>
          >
        </div>

        <!-- CONTRASEÑA -->
     <?php if($verCampo !== ''){ ?>
        <div class="form-group">
          <label>Nueva contraseña</label>
          <input
            type="password"
            name="contrasena"
            placeholder="<?= $puedeEditar ? 'Dejar vacío para no cambiar' : '*************************************' ?>"
            <?= $puedeEditar ? '' : 'readonly disabled' ?>
          >
        </div>
        <?php } ?>
        
        <!-- NOMBRES -->
        <div class="form-group">
          <label>Nombres</label>
          <input
            type="text"
            name="nombres"
            value="<?= htmlspecialchars($_SESSION['user']['datosUsuario']['nombres']) ?>"
            <?= $puedeEditar ? '' : 'readonly disabled' ?>
          >
        </div>

        <!-- APELLIDOS -->
        <div class="form-group">
          <label>Apellidos</label>
          <input
            type="text"
            name="apellidos"
            value="<?= htmlspecialchars($_SESSION['user']['datosUsuario']['apellidos']) ?>"
            <?= $puedeEditar ? '' : 'readonly disabled' ?>
          >
        </div>

        <!-- CÉDULA -->
        <div class="form-group">
          <label>Cédula</label>
          <input
            type="text"
            name="cedula"
            value="<?= htmlspecialchars($_SESSION['user']['datosUsuario']['cedula']) ?>"
            <?= $puedeEditar ? '' : 'readonly disabled' ?>
          >
        </div>

        <!-- CELULAR -->
        <div class="form-group">
          <label>Celular</label>
          <input
            type="text"
            name="celular"
            value="<?= htmlspecialchars($_SESSION['user']['datosUsuario']['celular']) ?>"
            <?= $puedeEditar ? '' : 'readonly disabled' ?>
          >
        </div>

        <!-- SOLO ADMIN PUEDE CAMBIAR TIPO (A OTROS) -->
        <?php if ($isAdmin && !$isPropio): ?>
        <div class="form-group">
          <label>Tipo de usuario</label>
          <select name="id_tipo">
            <option value="1" <?= $_SESSION['user']['datosUsuario']['id_tipo'] == 1 ? 'selected' : '' ?>>Administrador</option>
            <option value="2" <?= $_SESSION['user']['datosUsuario']['id_tipo'] == 2 ? 'selected' : '' ?>>Analista</option>
            <option value="3" <?= $_SESSION['user']['datosUsuario']['id_tipo'] == 3 ? 'selected' : '' ?>>Usuario Avanzado</option>
            <option value="4" <?= $_SESSION['user']['datosUsuario']['id_tipo'] == 4 ? 'selected' : '' ?>>Usuario reporte</option>
          </select>
        </div>

        <button type="submit" name="submit" class="btn-primary">Guardar cambios</button>
        
        <?php endif; ?>

      </div>

      <!-- BOTONES -->
      <div class="form-actions">
        <?php if ($puedeEditar): ?>
          <button type="submit" class="btn-primary">Guardar cambios</button>
        <?php endif; ?>

        <a href="/Dashboards/inicio/users" class="btn-secondary">Volver</a>
      </div>

    </form>

  </section>

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


<?php require_once APP . '/views/inc/footer.php' ?>
