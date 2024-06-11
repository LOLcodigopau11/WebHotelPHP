<?php
session_start();
include '../database/Conexion.php';

$con = new Conexion();
$db = $con->getCon();
// Verifica si la sesión está iniciada
if (isset($_SESSION['iduser'])) {
    header('Location: ../index.php'); // Redirige al índix o a otra página adecuada
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
        <title>Login</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="../css/Estilo.css" rel="stylesheet" type="text/css"/>
        <link href="../css/login-registro.css" rel="stylesheet" type="text/css"/>
        <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <script src="../js/navbar.js" type="text/javascript"></script>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    </head>
    <body>
        <header class="cabecera">
        <nav class="navbar">
            <div class="logo"><a href='../index.php'><img src='../img/logo.png'/>Hotel Green Ghost</a></div>
            <ul class="links_nav">
                <li><a href="../index.php#acerca">Nosotros</a></li>
                <li><a href="../index.php#habitaciones">Habitaciones</a></li>
                <li><a href="../index.php#servicios">Servicios</a></li>
                <li><a href="#contacto">Contacto</a></li>
            </ul>
            <a href="" class="action_btn btn">Reservar</a>
            
            <div class="toggle_btn">
                <i class="fa fa-bars menu-icon"></i>
            </div>    
        </nav>
        <div class="dropdown_menu">
            <li><a href="../index.php#acerca">Nosotros</a></li>
            <li><a href="../index.php#habitaciones">Habitaciones</a></li>
            <li><a href="../index.php#servicios">Servicios</a></li>
            <li><a href="#contacto">Contacto</a></li>
            <li><a href="" class="action_btn btn">Reservar</a></li>
        </div>  
    </header>
        <main>
            <div class="fondo1">
                <section class="hero">
                    <div class="contenedor">
                        <h2>INICIAR SESIÓN</h2>
                        <form class="form" action="login.php" method="post">
                            <label >
                                <i class='bx bx-user' ></i>
                                <input type="text" placeholder="Usuario o Correo" name="username" required>
                            </label>
                            <label>
                                <i class='bx bx-lock-alt' ></i>
                                <input type="password" placeholder="Contraseña" name="password" required>
                            </label>
                            <input type="submit" value="Iniciar Sesión" name="btnlogin">
                            <a href="registro.php">Registrarse</a>
                        </form>
                    </div>
                    <?php
                    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                    $username = $_POST['username'];
                    $password = $_POST['password'];

                    $stmt = $db->prepare("SELECT iduser, tipouser FROM tb_usuario WHERE usuario = :username AND contra = :password");
                    $stmt->bindParam(':username', $username);
                    $stmt->bindParam(':password', $password);
                    $stmt->execute();
                    $user = $stmt->fetch(PDO::FETCH_ASSOC);

                    if ($user) {
                        $_SESSION['iduser'] = $user['iduser'];
                        $_SESSION['tipouser'] = $user['tipouser'];
                        $_SESSION['username'] = $user['usuario'];
                        switch ($user['tipouser']) {
                            case 'TU1':
                                header('Location: admin/panel_admin.php');
                                break;
                            case 'TU2':
                                header('Location: supervisor/panel_supervisor.php');
                                break;
                            case 'TU3':
                                header('Location: empleado/panel_empleado.php');
                                break;
                            case 'TU4':
                                header('Location: cliente/panel_cliente.php');
                                break;
                            default:
                                echo "Invalido usuario.";
                                break;
                        }
                        exit;
                    } else {
                        echo "<div class='alert-danger' role='alert'>Usuario o Contraseña Incorrecto</div>";
                    }
                } 
                ?>
                </section>
            </div>
            <hr>
        </main>
    <div class="contact-container" id="contacto">
        <div class="contact-info">
            <h2>Póngase en contacto</h2>
            <p><strong>Email:</strong> Green-Ghost@email.com</p>
            <p><strong>Telefono:</strong> +51-919-727-292</p>
            <p><strong>Dirección:</strong> 145 Tarapoto, San Martín, Perú</p>
        </div>
        <div class="contact-form">
            <form>
                <label for="name">Nombre</label>
                <input type="text" id="name" name="name" required>
                
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required>
                
                <label for="message">Escribe tu mensaje aquí...</label>
                <textarea id="message" name="message" required></textarea>
                
                <button type="submit">Enviar</button>
            </form>
        </div>
    </div>
    <footer>
        <div class="container">
            <p>&copy; 2024 Hotel Green Ghost. Todos los derechos reservados.</p>
            <div class="social-icons">
                <a href="#" target="_blank"><i class="fab fa-facebook-f"></i></a>
                <a href="#"><i class="fab fa-twitter"></i></a>
                <a href="#"><i class="fab fa-instagram"></i></a>
                <a href="#"><i class="fab fa-youtube"></i></a>
            </div>
        </div>
    </footer>
    </body>
</html>
