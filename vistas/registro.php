<?php
session_start();
include '../database/Conexion.php';

$con = new Conexion();
$db = $con->getCon();
// Verifica si la sesión está iniciada
if (isset($_SESSION['iduser'])) {
    header('Location: ../index.php'); // Redirige al índice o a otra página adecuada
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
        <title>Green Ghost - Registro</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="icon" href="../img/logo.png">
        <link href="../css/Estilo.css" rel="stylesheet" type="text/css"/>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
        <script src="../js/navbar.js" type="text/javascript"></script>
        <link href="../css/login-registro.css" rel="stylesheet" type="text/css"/>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/css/intlTelInput.css"/>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/intlTelInput.min.js"></script>
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
        
        <div class="fondo1">
            <section class="hero">
                <div class="contenedor-registro">
                    <h2>CREA UNA CUENTA</h2>
                    <form class="form" action="registro.php" method="post">
                        <label>
                            <i class='bx bxs-user-detail' ></i>
                            <input type="text" placeholder="Nombres" name="txtnombre_clie" required>
                        </label>
                        <label>
                            <i class='bx bxs-user-detail' ></i>
                            <input type="text" placeholder="Apellidos" name="txtapellido_clie" required>
                        </label>
                       <label>
                            <i class='bx bx-id-card'></i>
                            <input type="number" placeholder="DNI" name="txtdni_clie" min="10000000"  required>
                        </label>
                        <label>
                            <i class='bx bx-phone'></i>
                            <input id="phone" type="tel" placeholder="Número de Celular" name="txtcelular_clie" required>
                        </label>
                        <label>
                            <i class='bx bx-envelope' ></i>
                            <input type="email" placeholder="Correo Electrónico" name="txtcorreo_clie" required>
                        </label>
                        <label>
                            <i class='bx bx-map' ></i>
                            <input type="text" placeholder="Dirección" name="txtdirec_clie">
                        </label>
                        <label>
                            <i class='bx bx-user' ></i>
                            <input type="text" placeholder="Crea un Usuario" name="txtuser" required>
                        </label>
                        <label>
                            <i class='bx bx-lock-alt' ></i>
                            <input type="password" placeholder="Crea una Contraseña" name="txtpassword" required>
                        </label>
                            <input type="submit" value="Registrarse" name="btnregistro">
                            <a href="login.php">Iniciar Sesión</a>
                        </form>
                </div>
                <?php
                if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                    $nombre = $_POST['txtnombre_clie'];
                    $apellido = $_POST['txtapellido_clie'];
                    $dni = $_POST['txtdni_clie'];
                    $telefono = $_POST['txtcelular_clie'];  // Este será el número en formato internacional
                    $correo = $_POST['txtcorreo_clie'];
                    $direccion = $_POST['txtdirec_clie'];
                    $usuario = $_POST['txtuser'];
                    $contrasena = $_POST['txtpassword'];  // Asegúrate de agregar este campo en el formulario
                    
                    try {
                    // Iniciar transacción
                    $db->beginTransaction();

                    // Inserta el usuario en la tabla tb_usuario
                    $stmt = $db->prepare("INSERT INTO tb_usuario (usuario, contra, tipouser) VALUES (:user, :pass, 'TU4')");
                    $stmt->bindParam(':user', $usuario);
                    $stmt->bindParam(':pass', $contrasena);
                    $stmt->execute();

                    // Obtener el iduser recién insertado
                    $iduser = $db->lastInsertId();

                    // Inserta los detalles del cliente en la tabla tb_cliente
                    $stmt = $db->prepare("INSERT INTO tb_cliente (nom_clie, ape_clie, dni_clie, telefono_clie, correo_clie, direccion_clie, "
                            . "iduser) VALUES (:nombre, :apellido, :dni, :telefono, :correo, :direccion, :iduser)");
                    $stmt->bindParam(':nombre', $nombre);
                    $stmt->bindParam(':apellido', $apellido);
                    $stmt->bindParam(':dni', $dni);
                    $stmt->bindParam(':telefono', $telefono);
                    $stmt->bindParam(':correo', $correo);
                    $stmt->bindParam(':direccion', $direccion);
                    $stmt->bindParam(':iduser', $iduser);

                    if ($stmt->execute()) {
                        // Confirmar transacción
                        $db->commit();
                        /*header('Location:login.php'); // Redirige al índice o a otra página adecuada
                        exit;*/
                        echo "<div class='alert-success'>Registro exitoso!</div>";
                    } else {
                        // Revertir transacción
                        $db->rollBack();
                        echo "<div class='alert-danger' role='alert'>Error al registrar</div>";
                    }
                } catch (Exception $e) {
                    // Revertir transacción en caso de error
                    $db->rollBack();
                    echo "<div class='alert-danger' role='alert'>Error: " . $e->getMessage() . "</div>";
                }
        
                }
                ?>    
            </section>
        </div>
        <hr>
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
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var input = document.querySelector("#phone");
            window.intlTelInput(input, {
                initialCountry: "auto",
                geoIpLookup: function(success, failure) {
                    fetch('https://ipinfo.io/json', {
                        headers: { 'Accept': 'application/json' }
                    }).then(function(response) {
                        return response.json();
                    }).then(function(json) {
                        success(json.country);
                    }).catch(function() {
                        success('US');
                    });
                },
                utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/utils.js"
            });
        });
    </script>
    </body>
</html>
