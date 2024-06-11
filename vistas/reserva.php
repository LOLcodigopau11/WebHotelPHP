<?php
session_start();

include '../database/Conexion.php';

$con = new Conexion();
$db = $con->getCon();

?>
<!DOCTYPE html>
<!--
Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/EmptyPHPWebPage.php to edit this template
-->
<html lang="es">
<html>
    <head>
        <meta charset="UTF-8">
        <title>Rserva</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="icon" href="../img/logo.png">
        <link href="../css/Estilo.css" rel="stylesheet" type="text/css"/>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
        <script src="../js/navbar.js" type="text/javascript"></script>
        <link href="../css/reserva.css" rel="stylesheet" type="text/css"/>
    </head>
    <body>
        <header class="cabecera">
        <nav class="navbar">
            <div class="logo"><a href='../index.php'><img src='../img/logo.png'/>Hotel Green Ghost</a></div>
            <ul class="links_nav">
                <li><a href="../index.php#acerca">Nosotros</a></li>
                <li><a href="../index.php#habitaciones">Habitaciones</a></li>
                <li><a href="../index.php#servicios">Servicios</a></li>
                <li><a href="../index.php#contacto">Contacto</a></li>
            </ul>
            <a href="#" class="action_btn btn">Reservar</a>
            <?php
            if(isset($_SESSION['iduser'])) {
                // Si el usuario está autenticado, mostrar el nombre de usuario y el enlace para cerrar sesión
                switch ($_SESSION['tipouser']) {
                    case 'TU1': // Administrador
                        echo "<div class='admin_btn'><a href='admin/panel_admin.php' class='action_btn btn'>admin{$_SESSION['username']}</a></div>";
                        break;
                    case 'TU2': // Empleado
                        echo "<div class='employee_btn'><a href='supervisor/panel_supervisor.php' class='action_btn btn'>{$_SESSION['username']}</a></div>";
                        break;
                    case 'TU3': // Supervisor
                        echo "<div class='supervisor_btn'><a href='supervisor/panel_supervisor.php' class='action_btn btn'>{$_SESSION['username']}</a></div>";
                        break;
                    case 'TU4': // Cliente
                        echo "<div class='client_btn'><a href='cliente/panel_cliente.php' class='action_btn btn'>Cliente{$_SESSION['username']}</a></div>";
                        break;
                    default:
                        echo "<div><a href='logout.php'>Logout</a></div>";
                        break;
                }
            } else {
                // Si el usuario no está autenticado, mostrar el botón de inicio de sesión
                echo "<a href='vistas/login.php' class='action_btn btn'>Login</a>";
            }
            ?>
            <div class="toggle_btn">
                <i class="fa fa-bars menu-icon"></i>
            </div>    
        </nav>
        <div class="dropdown_menu">
            <li><a href="../index.php#acerca">Nosotros</a></li>
            <li><a href="../index.php#habitaciones">Habitaciones</a></li>
            <li><a href="../index.php#servicios">Servicios</a></li>
            <li><a href="../index.php#contacto">Contacto</a></li>
            <li><a href="#" class="action_btn btn">Reservar</a></li>
            
            <?php
        if(isset($_SESSION['iduser'])) {
            switch ($_SESSION['tipouser']) {
                case 'TU1':
                    echo "<li><a href='admin/panel_admin.php' class='action_btn btn'>{$_SESSION['username']}</a></li>";
                    break;
                case 'TU2':
                    echo "<li><a href='supervisor/panel_supervisor.php' class='action_btn btn'>{$_SESSION['username']}</a></li>";
                    break;
                case 'TU3':
                    echo "<li><a href='supervisor/panel_supervisor.php' class='action_btn btn'>{$_SESSION['username']}</a></li>";
                    break;
                case 'TU4':
                    echo "<li><a href='vistas/cliente/panel_cliente.php' class='action_btn'>CLIENTE</a></li>";
                    break;
                default:
                    echo "<li><a href='vistas/login.php' class='action_btn btn'>Login</a></li>";
                    break;
            }
        } else {
            echo "<li><a href='vistas/login.php' class='action_btn btn'>Login</a></li>";
        }
        ?>
        </div>  
    </header>
<div class="fondo">
    <section class="hero">
        <div class="busqueda">
            <form method="post">
                <label for="fecha-inicio">Fecha de Inicio:</label>
                <input type="date" id="fecha_inicio" name="fecha_inicio" value="$_REQUEST" required>

                <label for="fecha-salida">Fecha de Salida:</label>
                <input type="date" id="fecha_fin" name="fecha_fin" required>

                <label for="room-type">Tipo de Habitación:</label>
                <select id="t_cuarto" name="t_cuarto" required>
                    <option value="">Seleccione Tipo de Habitación</option>
                    <option value="TC01">Deluxe</option>
                    <option value="TC02">Ejecutiva</option>
                    <option value="TC03">Presidencial</option>
                </select>

                <label for="number-of-people">Cant. Personas:</label>
                <input type="number" id="capacidad" name="capacidad" min="1" max="10" required>

                <button type="submit" id="busqueda-dispo">Buscar Disponibilidad</button>
            </form>
        </div>
        <h2 style="color:white; margin-top: 2rem; background-color: rgba(0, 0, 0, 0.5);">Cuartos disponibles:</h2>
        <div class="resultados">
            <?php
                if ($_SERVER["REQUEST_METHOD"] == "POST") {
                    try {
                        // Obtenemos y sanitizamos las entradas del formulario
                        $fecha_inicio = $_POST['fecha_inicio'];
                        $fecha_fin = $_POST['fecha_fin'];
                        $t_cuarto = $_POST['t_cuarto'];
                        $capacidad = $_POST['capacidad'];

                        // Creamos la consulta SQL con prepared statements
                        $query = "SELECT t.foto, c.id_cuarto, c.num_cuarto, c.t_cuarto, t.nombre, c.capacidad, c.precio_noche 
                                  FROM tb_cuarto c 
                                  LEFT JOIN tb_reserva r ON c.id_cuarto = r.id_cuarto 
                                  INNER JOIN tb_tipo_cuarto t ON c.t_cuarto = t.t_cuarto
                                  WHERE c.t_cuarto = ? AND c.capacidad >= ? 
                                  AND (r.id_cuarto IS NULL OR (r.fecha_inicio > ? OR r.fecha_fin < ?))";

                        $stmt = $db->prepare($query);
                        $stmt->execute([$t_cuarto, $capacidad, $fecha_fin, $fecha_inicio]);
                        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

                        if (count($result) > 0) {
                            echo "";
                            foreach ($result as $row) {
                                echo "<div class='resultado-item'>
                                    <form action='pago.php' method='GET'>
                                        <img src='{$row['foto']}' alt='Cuarto'>
                                        <p>Cuarto: {$row['num_cuarto']}</p>
                                        <p>Tipo: {$row['nombre']}</p>
                                        <p>Capacidad: {$row['capacidad']}</p>
                                        <p>Precio: {$row['precio_noche']}</p>
                                        <input type='hidden' name='id_cuarto' value='{$row['id_cuarto']}'>
                                        <input type='hidden' name='num_cuarto' value='{$row['num_cuarto']}'>
                                        <input type='hidden' name='fecha_inicio' value='{$fecha_inicio}'>
                                        <input type='hidden' name='fecha_fin' value='{$fecha_fin}'>
                                        <input type='hidden' name='precio_noche' value='{$row['precio_noche']}'>
                                        <input type='hidden' name='capacidad' value='{$capacidad}'>
                                        <button type='submit' class='btn'>Reservar</button>
                                    </form>
                                    </div>";
                            }
                        } else {
                            echo "<p>No hay cuartos disponibles en esas fechas.</p>";
                        }
                    } catch (PDOException $e) {
                        echo "Error: " . $e->getMessage();
                    }
                }
            ?>
        </div>
    </section>
</div>
        
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
    function reservar(idCuarto) {
      <?php
      if (isset($_SESSION['iduser'])) {
        // If user is logged in, redirect to payment page
        echo "window.location.href = `pago.php?id_cuarto=${idCuarto}`;";
      } else {
        // If user is not logged in, display pop-up message
        echo "alert('Debes iniciar sesión para reservar una habitación.');";
      }
      ?>
    }
</script>
    </body>
</html>

