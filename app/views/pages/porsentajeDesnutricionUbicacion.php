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
:root {
    --primary: #0f766e;
    --primary-dark: #115e59;
    --accent: #dc2626;
    --bg: #f4f9f8;
    --card: #ffffff;
    --text: #1f2937;
}

* {
    box-sizing: border-box;
}

body {
    margin: 0;
    font-family: 'Segoe UI', sans-serif;
    background: var(--bg);
    color: var(--text);
}

/* ======================
   LAYOUT
====================== */
.layout {
    display: flex;
    min-height: 100vh;
}

/* ======================
   SIDEBAR
====================== */
.sidebar {
    width: 230px;
    background: linear-gradient(180deg, var(--primary), var(--primary-dark));
    color: #fff;
    padding: 24px;
    transition: all 0.3s ease;
}

.sidebar-title {
    text-align: center;
    margin-bottom: 32px;
    font-size: 1.3rem;
}

.sidebar ul {
    list-style: none;
    padding: 0;
}

.sidebar li {
    padding: 14px;
    border-radius: 10px;
    margin-bottom: 10px;
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 10px;
}

.sidebar li a {
    color: inherit;
    text-decoration: none;
    width: 100%;
}

.sidebar li:hover,
.sidebar .active {
    background: rgba(255,255,255,0.15);
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

/* ======================
   MAIN
====================== */
.main {
    flex: 1;
    display: flex;
    flex-direction: column;
}

/* ======================
   TOPBAR
====================== */
.topbar {
    background: #fff;
    padding: 18px 24px;
    border-bottom: 1px solid #e5e7eb;
}

.topbar h1 {
    font-size: 1.2rem;
    display: flex;
    align-items: center;
    gap: 10px;
}

/* ======================
   CONTENT
====================== */
.content {
    padding: 24px;
}

.card {
    background: var(--card);
    border-radius: 14px;
    padding: 20px;
    box-shadow: 0 10px 25px rgba(0,0,0,0.08);
}

/* ======================
   TABLE
====================== */
.health-table {
    width: 100%;
    border-collapse: collapse;
    min-width: 700px;
}

.health-table th {
    background: #ecfeff;
    color: #065f46;
    padding: 14px;
    text-align: left;
    font-size: 0.9rem;
}

.health-table td {
    padding: 14px;
    border-bottom: 1px solid #e5e7eb;
}

.health-table tr:hover {
    background: #f0fdfa;
}

/* ======================
   BADGES
====================== */
.badge {
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 0.8rem;
    font-weight: 600;
}

.badge.urbana {
    background: #dbeafe;
    color: #1e40af;
}

.badge.rural {
    background: #fee2e2;
    color: #991b1b;
}

/* ======================
   INDICADORES
====================== */
.danger {
    color: var(--accent);
    font-weight: bold;
}

/* Progress bar */
.progress {
    background: #e5e7eb;
    border-radius: 20px;
    overflow: hidden;
}

.progress-bar {
    background: linear-gradient(90deg, #dc2626, #f97316);
    color: #fff;
    padding: 6px;
    font-size: 0.75rem;
    text-align: center;
    white-space: nowrap;
}

.menu-toggle {
    display: none;
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


<div class="layout">
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
    <!-- MAIN -->
    <main class="main">

        <!-- TOPBAR -->
        <header class="topbar">
             <i class="fas fa-bars menu-toggle" onclick="toggleSidebar()"></i>
            <h1>📊 Desnutrición Infantil por Localidad</h1>
        </header>

        <!-- CONTENIDO -->
        <section class="content">

            <div class="card">
                <table class="health-table">
                    <thead>
                        <tr>
                            <th>Provincia</th>
                            <th>Ciudad</th>
                            <th>Zona</th>
                            <th>Total niños</th>
                            <th>Niños desnutridos</th>
                            <th>% Desnutrición</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($indicadores as $row): ?>
                            <tr>
                                <td><?= htmlspecialchars($row['provincia']) ?></td>
                                <td><?= htmlspecialchars($row['ciudad']) ?></td>
                                <td>
                                    <span class="badge <?= $row['zona'] === 'Rural' ? 'rural' : 'urbana' ?>">
                                        <?= $row['zona'] ?>
                                    </span>
                                </td>
                                <td><?= $row['total_ninos'] ?></td>
                                <td class="danger"><?= $row['ninos_desnutridos'] ?></td>
                                <td>
                                    <div class="progress">
                                        <div 
                                            class="progress-bar" 
                                            style="width: <?= $row['porcentaje_desnutricion'] ?>%">
                                            <?= $row['porcentaje_desnutricion'] ?>%
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

        </section>
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