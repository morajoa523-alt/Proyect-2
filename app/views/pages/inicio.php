<?php
if (session_status() === PHP_SESSION_NONE) {
session_start();
}

if (!isset($_SESSION['usuario'])) {
    header('Location: /plataformaDect/inicio/login');
    exit;
}


?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Panel de Salud</title>
    <link rel="stylesheet" href="dashboard.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <style>

      /* ===============================
   RESET BÁSICO
================================ */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: 'Segoe UI', Tahoma, sans-serif;
    background-color: #f4f6f9;
    color: #333;
}

/* ===============================
   CONTENEDOR GENERAL
================================ */
.admin-container {
    display: flex;
    min-height: 100vh;
}

/* ===============================
   SIDEBAR
================================ */
.sidebar {
    width: 230px;
    background: #0d3b66;
    color: #fff;
    padding: 20px;
    position: fixed;
    top: 0;
    left: 0;
    bottom: 0;
}

.sidebar-title {
    font-size: 20px;
    margin-bottom: 30px;
    text-align: center;
    font-weight: bold;
}

.sidebar ul {
    list-style: none;
}

.sidebar ul li {
    padding: 12px 10px;
    margin-bottom: 10px;
    border-radius: 6px;
    cursor: pointer;
    transition: background 0.3s;
}

.sidebar ul li:hover {
    background: #145da0;
}

/* Quitar color azul y subrayado de enlaces del sidebar */
.sidebar a {
    color: inherit;
    text-decoration: none;
    display: flex;
    align-items: center;
    gap: 10px;
}

/* Mantener color al hover y activo */
.sidebar li:hover a,
.sidebar .active a {
    color: #ffffff;
}
/* ===============================
   CONTENIDO PRINCIPAL
================================ */
.main-content {
    margin-left: 230px;
    width: calc(100% - 230px);
    padding: 20px;
}

/* ===============================
   TOP BAR
================================ */
.topbar {
    height: 60px;
    background: #ffffff;
    border-radius: 10px;
    padding: 0 20px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    box-shadow: 0 2px 6px rgba(0,0,0,0.08);
    margin-bottom: 20px;
}

.topbar h1 {
    font-size: 18px;
    font-weight: 600;
}

/* ===============================
   NOTIFICACIONES
================================ */
.notifications {
    position: relative;
    font-size: 22px;
    cursor: pointer;
}

.notifications .badge {
    position: absolute;
    top: -5px;
    right: -5px;
    width: 16px;
    height: 16px;
    background: #e63946;
    color: #fff;
    font-size: 12px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
}

/* ===============================
   CONTENIDO BASE
================================ */
.content-base {
    background: transparent;
}

/* ===============================
   TARJETAS SUPERIORES
================================ */
.stats-container {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 20px;
    margin-bottom: 25px;
}

.stat-card {
    background: #ffffff;
    border-radius: 12px;
    padding: 20px;
    box-shadow: 0 3px 8px rgba(0,0,0,0.08);
    transition: transform 0.3s;
}

.stat-card:hover {
    transform: translateY(-5px);
}

.stat-card h3 {
    font-size: 28px;
    color: #0d3b66;
    margin-bottom: 8px;
}

.stat-card p {
    font-size: 14px;
    color: #666;
}

/* ALERTAS */
.stat-card.alert h3 {
    color: #e63946;
}

.stat-card.percentage h3 {
    color: #2a9d8f;
}

/* ===============================
   ZONAS
================================ */
.zones-container {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
    margin-bottom: 25px;
}

.zone-card {
    background: #ffffff;
    border-radius: 12px;
    padding: 20px;
    box-shadow: 0 3px 8px rgba(0,0,0,0.08);
}

.zone-card h3 {
    font-size: 18px;
    margin-bottom: 10px;
    color: #0d3b66;
}

.zone-card p {
    font-size: 14px;
    margin-bottom: 5px;
}

/* ===============================
   LOCALIDAD
================================ */
.location-container {
    background: #ffffff;
    border-radius: 12px;
    padding: 20px;
    box-shadow: 0 3px 8px rgba(0,0,0,0.08);
}

.location-container h3 {
    font-size: 18px;
    margin-bottom: 10px;
    color: #0d3b66;
}

.location-container p {
    font-size: 14px;
    color: #444;
}

.menu-toggle {
    display: none;
}

/* ===============================
   SECCIÓN LOCALIDAD MODERNA
================================ */
.location-section {
    background: #ffffff;
    border-radius: 14px;
    padding: 25px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.08);
    margin-top: 25px;
}

.section-title {
    font-size: 18px;
    margin-bottom: 20px;
    color: #0d3b66;
    display: flex;
    align-items: center;
    gap: 10px;
}

.location-cards {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 20px;
}

.location-card {
    background: #f8fafc;
    border-radius: 12px;
    padding: 18px;
    display: flex;
    align-items: center;
    gap: 15px;
    transition: all 0.3s ease;
    border-left: 5px solid transparent;
}

.location-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 6px 14px rgba(0,0,0,0.1);
}

.location-card .info span {
    font-size: 13px;
    color: #666;
}

.location-card .info h4 {
    font-size: 18px;
    color: #0d3b66;
    margin-top: 3px;
}

/* ICONOS */
.icon-box {
    width: 45px;
    height: 45px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 18px;
    color: #fff;
}

.icon-box.province { background: #457b9d; }
.icon-box.city { background: #1d3557; }
.icon-box.zone { background: #2a9d8f; }
.icon-box.percentage { background: #e63946; }

.location-card.alert {
    border-left: 5px solid #e63946;
}

/* RESPONSIVE */
@media (max-width: 992px) {
    .location-cards {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media (max-width: 600px) {
    .location-cards {
        grid-template-columns: 1fr;
    }
}

/* ===============================
   RESPONSIVE
================================ */
@media (max-width: 768px) {

    .sidebar {
        position: fixed;
        left: -250px;
        top: 0;
        width: 230px;
        height: 100%;
        transition: left 0.3s ease;
        z-index: 1000;
    }

    .sidebar.active {
        left: 0;
    }

    .main-content {
        margin-left: 0;
        width: 100%;
    }

    .menu-toggle {
        display: block;
        font-size: 22px;
        cursor: pointer;
        color: #0d3b66;
    }

    .stats-container {
        grid-template-columns: 1fr;
    }

    .zones-container {
        grid-template-columns: 1fr;
    }

    /* Fondo oscuro cuando el sidebar esté abierto */
    .overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0,0,0,0.4);
        display: none;
        z-index: 900;
    }

    .overlay.active {
        display: block;
    }
}


    </style>

</head>
<body>

<div class="admin-container">
<div class="overlay" onclick="toggleSidebar()"></div>
       <!-- SIDEBAR -->
<aside class="sidebar">
    <h2 class="sidebar-title">
        <i class="fas fa-hospital"></i> Centro Salud
    </h2>
    <nav>
        <ul>
            <li class="active">
                <a href="/plataformaDect/inicio/inicio"><i class="fas fa-chart-line"></i>Inicio</a>
            </li>
            <li>
                <a href="/plataformaDect/inicio/nutricionPorLocalidad">
                    <i class="fas fa-child"></i> Datos por Localidad
                </a>
            </li>
            <li>
                <a href="/plataformaDect/inicio/cerrarSesion">
                    👥 Cerrar sesión
                </a>
            </li>
        </ul>
    </nav>
</aside>

    <!-- CONTENIDO PRINCIPAL -->
    <main class="main-content">

        <!-- BARRA SUPERIOR -->
        <header class="topbar">

    <!-- BOTÓN MENU RESPONSIVE -->
    <i class="fas fa-bars menu-toggle" onclick="toggleSidebar()"></i>

    <h1>Panel General</h1>

    <div class="notifications">
        <span class="icon">🔔</span>
        <?php if ($data['alertaDesnutricion']) : ?>
            <span class="badge">!</span>
        <?php endif; ?>
    </div>
</header>

        <!-- CONTENEDOR GENERAL -->
        <div class="content-base">

            <!-- BLOQUE SUPERIOR -->
            <div class="stats-container">

                <div class="stat-card">
                    <h3><?= $data['totalPacientes'] ?></h3>
                    <p>Total de pacientes registrados</p>
                </div>

                <div class="stat-card">
                    <h3><?= $data['ninosMenores5'] ?></h3>
                    <p>Niños menores de 5 años</p>
                </div>

                <div class="stat-card alert">
                    <h3><?= $data['ninosDesnutridos'] ?></h3>
                    <p>Niños con desnutrición</p>
                </div>

                <div class="stat-card percentage">
                    <h3><?= $data['porcentajeDesnutricion'] ?>%</h3>
                    <p>Porcentaje de desnutrición infantil</p>
                </div>

            </div>

            <!-- BLOQUE ZONAS -->
            <div class="zones-container">

                <div class="zone-card">
                    <h3>Zona Rural</h3>
                    <p>Niños: <?= $data['zonaRural']['cantidad'] ?></p>
                    <p>Desnutrición: <?= $data['zonaRural']['porcentaje'] ?>%</p>
                </div>

                <div class="zone-card">
                    <h3>Zona Urbana</h3>
                    <p>Niños: <?= $data['zonaUrbana']['cantidad'] ?></p>
                    <p>Desnutrición: <?= $data['zonaUrbana']['porcentaje'] ?>%</p>
                </div>

            </div>

            <!-- BLOQUE LOCALIDAD REDISEÑADO -->
<div class="location-section">

    <h3 class="section-title">
        <i class="fas fa-map-marked-alt"></i>
        Localidad con mayor desnutrición
    </h3>

    <div class="location-cards">

        <div class="location-card">
            <div class="icon-box province">
                <i class="fas fa-map"></i>
            </div>
            <div class="info">
                <span>Provincia</span>
                <h4><?= $data['localidad']['provincia'] ?></h4>
            </div>
        </div>

        <div class="location-card">
            <div class="icon-box city">
                <i class="fas fa-city"></i>
            </div>
            <div class="info">
                <span>Ciudad</span>
                <h4><?= $data['localidad']['ciudad'] ?></h4>
            </div>
        </div>

        <div class="location-card">
            <div class="icon-box zone">
                <i class="fas fa-location-dot"></i>
            </div>
            <div class="info">
                <span>Zona</span>
                <h4><?= $data['localidad']['zona'] ?></h4>
            </div>
        </div>

        <div class="location-card alert">
            <div class="icon-box percentage">
                <i class="fas fa-triangle-exclamation"></i>
            </div>
            <div class="info">
                <span>Desnutrición</span>
                <h4><?= $data['localidad']['porcentaje'] ?>%</h4>
            </div>
        </div>

    </div>

</div>

        </div>

    </main>
</div>
<script>
function toggleSidebar() {
    const sidebar = document.querySelector('.sidebar');
    const overlay = document.querySelector('.overlay');

    sidebar.classList.toggle('active');
    overlay.classList.toggle('active');
}
</script>
</body>
</html>
