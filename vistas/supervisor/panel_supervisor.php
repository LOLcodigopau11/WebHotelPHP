<?php
session_start();
if ($_SESSION['tipouser'] != 'TU2') {
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
        <title>Supervisor - Recepcion</title>
    </head>
    <body>
        <h1>Welcome, Employee</h1>
        <?php
        // put your code here
        ?>
    </body>
</html>
