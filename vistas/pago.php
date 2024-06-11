<?php
session_start();
include '../database/Conexion.php';
$con = new Conexion();
$db = $con->getCon();
// Verificar si el usuario no está autenticado
if (!isset($_SESSION['iduser'])) {
    // Si no está autenticado, redirigirlo a la página de inicio de sesión
    header("Location: login.php");
    exit; // Terminar el script para evitar que se ejecute más código
}
// Obtener los datos del formulario
$iduser = $_SESSION['iduser'];
$tipouser = $_SESSION['tipouser'];

if ($tipouser == 'TU4') { // Cliente
    $stmt = $db->prepare("SELECT idclie FROM tb_cliente WHERE iduser = :iduser");
    $stmt->bindParam(':iduser', $iduser);
    $stmt->execute();
    $idpersona = $stmt->fetchColumn();
} elseif ($tipouser == 'TU2' || $tipouser == 'TU3' || $tipouser == 'TU1') { // Empleado
    $stmt = $db->prepare("SELECT idemp FROM tb_empleado WHERE iduser = :iduser");
    $stmt->bindParam(':iduser', $iduser);
    $stmt->execute();
    $idpersona = $stmt->fetchColumn();
}
$idcuarto = $_GET['id_cuarto'];
$ncuarto = $_GET['num_cuarto'];
$fechaI = $_GET['fecha_inicio'];
$fechaF = $_GET['fecha_fin'];
$cant_personas = $_GET['capacidad'];
$precio = $_GET['precio_noche'];
// Calcular la cantidad de noches
$datetime1 = new DateTime($fechaI);
$datetime2 = new DateTime($fechaF);
$interval = $datetime1->diff($datetime2);
$cant_noches = $interval->days;
// Calcular el precio total
$precio_t = $precio * $cant_noches;
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
        <title>Pago</title>
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
            <a href="reserva.php" class="action_btn btn">Reservar</a>
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
            <li><a href="reserva.php" class="action_btn btn">Reservar</a></li>
            
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
    <div class="fondop">
        <div class="pago">
            <form method="post">
            <h2>Detalles de Pago</h2>
            <p>N° Cuarto: <?php echo $ncuarto; ?></p>
            <p>Fecha de Inicio: <?php echo $fechaI; ?></p>
            <p>Fecha de Fin: <?php echo $fechaF; ?></p>
            <p>Cantidad de Personas: <?php echo $cant_personas; ?></p>
            <p>Precio x Noche: <?php echo $precio; ?></p>
            <p>Cant. de Noches: <?php echo $cant_noches; ?></p>
            <p>Precio Total: <?php echo $precio_t; ?></p>
            <label>Metodo de pago:</label>
            <select id="metodo_pago" name="metodo_pago" required>
            <option value="">Seleccione Pago</option>
            <option value="Tarjeta Visa">Tarjeta Visa</option>
            <option value="Tarjeta mastercar">Tarjeta Mastercard</option>
            <option value="Paypal">Paypal</option>
            </select>
            <input type="submit" class="btn" value="PAGAR">
            </form>
            <?php
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
        try {
            $metodo_pago = $_POST['metodo_pago'];

            // Iniciar transacción
            $db->beginTransaction();

            // Insertar en tb_pago
            $query_pago = "INSERT INTO tb_pago (pago, fecha_pago, metodo_pago) VALUES (?, CURRENT_TIMESTAMP, ?)";
            $stmt_pago = $db->prepare($query_pago);
            $stmt_pago->execute([$precio_t, $metodo_pago]);
            $pago_id = $db->lastInsertId();

            // Insertar en tb_reserva
            $query_reserva = "INSERT INTO tb_reserva (iduser, id_cuarto, fecha_inicio, fecha_fin, dias, cant_personas, pago_id) VALUES (?, ?, ?, ?, ?, ?, ?)";
            $stmt_reserva = $db->prepare($query_reserva);
            $stmt_reserva->execute([$iduser, $idcuarto, $fechaI, $fechaF, $cant_noches, $cant_personas, $pago_id]);

            // Confirmar transacción
            $db->commit();

            echo "<div class='alert-success'>Pago y reserva registrados con éxito!</div>";
        } catch (PDOException $e) {
            // Revertir transacción
            $db->rollBack();
                echo "<div class='alert-danger'>Error al registrar el pago y la reserva: " . $e->getMessage() . "</div>";
            }}?>  
     </div>  
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
    </body>
</html>
