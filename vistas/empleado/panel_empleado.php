<?php
session_start();
if ($_SESSION['tipouser'] != 'TU3') {
    header('Location: login.php');
    exit;
}
?>
<!DOCTYPE html>
<!--
Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/EmptyPHPWebPage.php to edit this template
-->
<html>
    <head>
        <meta charset="UTF-8">
        <title>Empleado</title>
    </head>
    <body>
        <h1>Welcome, Empleado</h1>
        <!-- Customer-specific content -->
        <?php
        // put your code here
        ?>
    </body>
</html>
