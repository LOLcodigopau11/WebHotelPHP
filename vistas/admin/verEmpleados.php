<?php
session_start();
if ($_SESSION['tipouser'] != 'TU1') {
    header('Location: login.php');
    exit;   
} else if(isset($_SESSION['iduser'])) {}
else {
    header("Location: login.php");
    exit;}
include '../../database/Conexion.php';
$con = new Conexion();
$db = $con->getCon();
// Obtener los datos del formulario
$iduser = $_SESSION['iduser'];
$tipouser = $_SESSION['tipouser'];

if ($tipouser == 'TU1') { // admin
    $stmt = $db->prepare("SELECT nom_emp, ape_emp FROM tb_empleado WHERE iduser = :iduser");
    $stmt->bindParam(':iduser', $iduser);
    $stmt->execute();$row = $stmt->fetch();
    $nombre = $row['nom_emp'];
    $apellido = $row['ape_emp'];}
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Panel Admin</title>
        <link rel="icon" href="../../img/logo_1.png">
        <link href="../../css/Estilo.css" rel="stylesheet" type="text/css"/>
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css2?family=Philosopher:ital,wght@0,400;0,700;1,400;1,700&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="../../css/panel_admin.css" rel="stylesheet" type="text/css"/>
        <script src="../../js/navbar.js" type="text/javascript"></script>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.7.0/chart.min.js"></script>
    </head>
    <body>
        <header class="cabecera">
        <nav class="navbar">
            <div class="logo"><a href='../../index.php'><img src='../../img/logo.png'/>Hotel Green Ghost</a></div>
            <ul class="links_nav">
                <li><a href="../../index.php#acerca">Nosotros</a></li>
                <li><a href="../../index.php#habitaciones">Habitaciones</a></li>
                <li><a href="../../index.php#servicios">Servicios</a></li>
                <li><a href="../../index.php#contacto">Contacto</a></li>
            </ul>
            <a href="../reserva.php" class="action_btn btn">Reservar</a>
            <?php
            if(isset($_SESSION['iduser'])) {
                // Si el usuario está autenticado, mostrar el nombre de usuario y el enlace para cerrar sesión
                switch ($_SESSION['tipouser']) {
                    case 'TU1': // Administrador
                        echo "<div class='admin_btn'><a href='#' class='action_btn btn'>Admin</a></div>";
                        break;
                    case 'TU2': // Empleado
                        echo "<div class='employee_btn'><a href='vistas/supervisor/panel_supervisor.php' class='action_btn btn'>{$_SESSION['username']}</a></div>";
                        break;
                    case 'TU3': // Supervisor
                        echo "<div class='supervisor_btn'><a href='vistas/supervisor/panel_empleado.php' class='action_btn btn'>{$_SESSION['username']}</a></div>";
                        break;
                    case 'TU4': // Cliente
                        echo "<div class='client_btn'><a href='vistas/cliente/panel_cliente.php' class='action_btn btn'>Cliente{$_SESSION['username']}</a></div>";
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
            <li><a href="../../index.php#acerca">Nosotros</a></li>
            <li><a href="../../index.php#habitaciones">Habitaciones</a></li>
            <li><a href="../../index.php#servicios">Servicios</a></li>
            <li><a href="../../index.php#contacto">Contacto</a></li>
            <li><a href="../reserva.php" class="action_btn btn">Reservar</a></li>
            
            <?php
        if(isset($_SESSION['iduser'])) {
            switch ($_SESSION['tipouser']) {
                case 'TU1':
                    echo "<li><a href='#' class='action_btn btn'>ADMIN</a></li>";
                    break;
                case 'TU2':
                    echo "<li><a href='vistas/supervisor/panel_supervisor.php' class='action_btn btn'>Supervisor{$_SESSION['username']}</a></li>";
                    break;
                case 'TU3':
                    echo "<li><a href='vistas/supervisor/panel_empleado.php' class='action_btn btn'>Empleado{$_SESSION['username']}</a></li>";
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
    <main>
        <div class="dashboard">
            <?php echo '<h1>Bienvenido, ' . $nombre . ' ' . $apellido . '</h1>'; 
            echo "<a style='color:blue' href='../cerrarSesion.php'>CERRAR SESIÓN</a>";?>
            <div class="stats">
                <?php
                $stmt = $db->prepare("SELECT COUNT(*) AS num_reservas FROM tb_reserva");
                $stmt->execute();
                $result = $stmt->fetch();
                $num_reservas = $result['num_reservas'];

                $stmt = $db->prepare("SELECT COUNT(*) AS num_pagos FROM tb_pago");
                $stmt->execute();
                $result = $stmt->fetch();
                $num_pagos = $result['num_pagos'];

                $stmt = $db->prepare("SELECT COUNT(*) AS num_user FROM tb_usuario");
                $stmt->execute();
                $result = $stmt->fetch();
                $num_user = $result['num_user'];

                $stmt = $db->prepare("SELECT COUNT(*) AS num_clie FROM tb_cliente");
                $stmt->execute();
                $result = $stmt->fetch();
                $num_clie = $result['num_clie'];

                $stmt = $db->prepare("SELECT COUNT(*) AS num_emp FROM tb_empleado");
                $stmt->execute();
                $result = $stmt->fetch();
                $num_emp = $result['num_emp'];
                ?>
                <button onclick="location.href='../reservas/verReservas.php'">VER RESERVAS<br>TOTAL Reservas: <?php echo $num_reservas; ?></button>
                <button onclick="location.href='panel_admin.php'">VER GRÁFICO<br>Reservas por Mes</button>
                <button onclick="location.href='../reservas/verPagos.php'">VER PAGOS<br>TOTAL Pagos: <?php echo $num_pagos; ?></button>
                <button onclick="location.href='verUsuarios.php'">VER USUARIOS<br>TOTAL Usuarios: <?php echo $num_user; ?></button>
                <button onclick="location.href='verClientes.php'">VER CLIENTES<br>TOTAL Clientes: <?php echo $num_clie; ?></button>
                <button>VER EMPLEADOS<br>TOTAL Empleados: <?php echo $num_emp; ?></button>
            </div>
            <table class='table table-bordered'>
                <thead>
                    <tr>
                        <th>N°</th>
                        <th>Nombre</th>
                        <th>Apellido</th>
                        <th>DNI</th>
                        <th>Teléfono</th>
                        <th>Correo</th>
                        <th>Dirección</th>
                        <th>Cargo</th>
                        <th>Sueldo</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $stmt = $db->prepare("SELECT * FROM tb_empleado");
                    $stmt->execute();
                    $empleados = $stmt->fetchAll();
                    foreach ($empleados as $empleado) {
                        $cargo_id = $empleado['idcargo'];
                        $stmt = $db->prepare("SELECT * FROM tb_cargo WHERE idcargo = :cargo_id");
                        $stmt->bindParam(':cargo_id', $cargo_id);
                        $stmt->execute();
                        $cargo = $stmt->fetch();
                        echo "<tr>";
                        echo "<td>". $empleado['idemp']. "</td>";
                        echo "<td>". $empleado['nom_emp']. "</td>";
                        echo "<td>". $empleado['ape_emp']. "</td>";
                        echo "<td>". $empleado['dni_emp']. "</td>";
                        echo "<td>". $empleado['telefono_emp']. "</td>";
                        echo "<td>". $empleado['correo_emp']. "</td>";
                        echo "<td>". $empleado['direccion_emp']. "</td>";
                        echo "<td>". $cargo['cargo']. "</td>";
                        echo "<td>". $cargo['sueldo']. "</td>";
                        echo "</tr>";
                    }
                 ?>
                </tbody>
            </table>
        </div>
    </main>
    </section>
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

