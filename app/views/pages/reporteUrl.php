<?php require_once APP . '/views/inc/headerReporte.php'; ?>
<?php require_once APP . '/views/inc/sidebar.php'; ?>

<div class="content-base">

  <section class="report-panel">
    <header class="report-header">
      <h2>Generador de Reportes</h2>
      <p>Copie las rutas de los dashboards para generar reportes externos</p>
    </header>

    <div class="report-grid">

      <!-- REPORTE 1 -->
      <div class="report-card" data-path="/Dashboards/inicio/inicio">
        <div class="report-info">
          <h3>Dashboard General</h3>
          <span class="report-path">/Dashboards/inicio/inicio</span>
        </div>
        <button class="btn-copy">Copiar URL</button>
      </div>

      <!-- REPORTE 2 -->
      <div class="report-card" data-path="/Dashboards/preferencias/preferenciasXsegmentF">
        <div class="report-info">
          <h3>Dashboard Segmento F</h3>
          <span class="report-path">/Dashboards/preferencias/preferenciasXsegmentF</span>
        </div>
        <button class="btn-copy">Copiar URL</button>
      </div>

      <!-- REPORTE 3 -->
      <div class="report-card" data-path="/Dashboards/preferencias/preferenciasXsegmentM">
        <div class="report-info">
          <h3>Dashboard Segmento M</h3>
          <span class="report-path">/Dashboards/preferencias/preferenciasXsegmentM</span>
        </div>
        <button class="btn-copy">Copiar URL</button>
      </div>

    </div>
  </section>


  <div class="asignacion_acceso_user_reporte">

  <!-- =========================
       FORMULARIO
  ========================= -->
  <form method="POST" action="/Dashboards/user/insertUserReporte" id="formUsuarioReporte">

    <input type="hidden" name="id_usuario_reporte" id="id_usuario_reporte">

    <div class="form-group">
      <label>Usuario Reporte</label>
      <select name="id_usuario" id="id_usuario" required>
        <option value="">Seleccione usuario</option>
        <?php foreach ($usuarios as $u): ?>
          <option value="<?= $u['id_usuario'] ?>">
            <?= htmlspecialchars($u['nombre']) ?>
          </option>
        <?php endforeach; ?>
      </select>
    </div>

    <div class="form-group">
      <label>Tipo de Acceso</label>
      <select name="id_acceso_usuario" id="id_acceso_usuario" required>
        <option value="">Seleccione acceso</option>
        <?php foreach ($tiposAcceso as $a): ?>
          <option value="<?= $a['id_acceso_usuario'] ?>">
            <?= htmlspecialchars($a['acceso']) ?>
          </option>
        <?php endforeach; ?>
      </select>
    </div>

    <div class="form-group">
      <label>Ruta del Reporte</label>
      <input
        type="text"
        name="ruta_reporte"
        id="ruta_reporte"
        placeholder="/reportes/ventas"
      >
    </div>

    <div class="form-actions">
      <button type="submit" name="submit" id="btnGuardar">Registrar</button>
      <button type="button" id="btnCancelar" style="display:none;">Cancelar</button>
    </div>

  </form>

  <!-- =========================
       TABLA
  ========================= -->
  <table border="1" width="100%" cellpadding="6">
    <thead>
      <tr>
        <th>Usuario</th>
        <th>Acceso</th>
        <th>Ruta</th>
        <th>Acciones</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($usuariosReporte as $ur): ?>
        <tr
          data-id="<?= $ur['id_usuario_reporte'] ?>"
          data-usuario="<?= $ur['id_usuario'] ?>"
          data-acceso="<?= $ur['id_acceso_usuario'] ?>"
          data-ruta="<?= htmlspecialchars($ur['ruta_reporte']) ?>"
        >
          <td><?= htmlspecialchars($ur['usuario']) ?></td>
          <td><?= htmlspecialchars($ur['acceso']) ?></td>
          <td><?= htmlspecialchars($ur['ruta_reporte']) ?></td>
          <td>
            <button type="button"class="btnEditar">Editar</button>

            <form
              method="POST"
              action="/Dashboards/user/eliminarUserReporte"
              style="display:inline;"
              onsubmit="return confirm('¿Eliminar este registro?')"
            >
              <input type="hidden" name="id_usuario_reporte"
                     value="<?= $ur['id_usuario_reporte'] ?>">
              <button type="submit" name="submit" >Eliminar</button>
            </form>
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>

</div>





</div>

<script>
document.querySelectorAll('.btn-copy').forEach(btn => {
  btn.addEventListener('click', () => {
    const card = btn.closest('.report-card');
    const path = card.getAttribute('data-path');
    const fullUrl = window.location.origin + path;

    navigator.clipboard.writeText(fullUrl);

    btn.textContent = 'Copiado ✓';
    setTimeout(() => btn.textContent = 'Copiar URL', 1500);
  });
});
</script>
<!-- =========================
     JAVASCRIPT
========================= -->
<script>
document.addEventListener('DOMContentLoaded', () => {

  const form = document.getElementById('formUsuarioReporte');
  const btnGuardar = document.getElementById('btnGuardar');
  const btnCancelar = document.getElementById('btnCancelar');

  const inputId = document.getElementById('id_usuario_reporte');
  const inputUsuario = document.getElementById('id_usuario');
  const inputAcceso = document.getElementById('id_acceso_usuario');
  const inputRuta = document.getElementById('ruta_reporte');

  document.querySelectorAll('.btnEditar').forEach(btn => {
    btn.addEventListener('click', () => {
      const fila = btn.closest('tr');

      inputId.value = fila.dataset.id;
      inputUsuario.value = fila.dataset.usuario;
      inputAcceso.value = fila.dataset.acceso;
      inputRuta.value = fila.dataset.ruta;

      btnGuardar.textContent = 'Guardar Cambios';
      btnCancelar.style.display = 'inline-block';

      form.action = '/Dashboards/user/updateUserReporte';
    });
  });

  btnCancelar.addEventListener('click', () => {
    form.reset();
    inputId.value = '';
    btnGuardar.textContent = 'Registrar';
    btnCancelar.style.display = 'none';
    form.action = '/Dashboards/user/insertUserReporte';
  });

});
</script>

<?php require_once APP . '/views/inc/sidebar.php'; ?>