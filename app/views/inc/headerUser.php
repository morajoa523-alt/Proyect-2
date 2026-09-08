<?php
if (session_status() === PHP_SESSION_NONE) {
session_start();
}

if (!isset($_SESSION['usuario'])) {
    header('Location: /Dashboards/inicio/login');
    exit;
}

?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="<?= URL . '/css/userAndRegisterUser.css' ?>">
  
  <title></title>
   
</head>
<body>