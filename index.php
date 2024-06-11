<?php
session_start(); //Crea una sesión o reanuda la actual basada en un identificador de sesión pasado mediante una 
//petición GET o POST, o pasado mediante una cookie.
?>
<!DOCTYPE html>
<!--
Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
Click nbfs://nbhost/SystemFileSystem/Templates/Project/PHP/PHPProject.php to edit this template
-->
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Green Ghost - Hotel</title>
    <link rel="icon" href="img/logo_1.png">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Philosopher:ital,wght@0,400;0,700;1,400;1,700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="css/Estilo.css" rel="stylesheet" type="text/css"/>
    <link href="css/login-registro.css" rel="stylesheet" type="text/css"/>
    <script src="js/navbar.js" type="text/javascript"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>
<body>
    <header class="cabecera">
        <nav class="navbar">
            <div class="logo"><a href='index.php'><img src='img/logo.png'/>Hotel Green Ghost</a></div>
            <ul class="links_nav">
                <li><a href="#acerca">Nosotros</a></li>
                <li><a href="#habitaciones">Habitaciones</a></li>
                <li><a href="#servicios">Servicios</a></li>
                <li><a href="#contacto">Contacto</a></li>
            </ul>
            <a href="vistas/reserva.php" class="action_btn btn">Reservar</a>
            <?php
            if(isset($_SESSION['iduser'])) {
                // Si el usuario está autenticado, mostrar el usuario y el enlace para cerrar sesión
                switch ($_SESSION['tipouser']) {
                    case 'TU1': // Administrador
                        echo "<div class='admin_btn'><a href='vistas/admin/panel_admin.php' class='action_btn btn'>Admin{$_SESSION['username']}</a></div>";
                        break;
                    case 'TU2': // Empleado
                        echo "<div class='employee_btn'><a href='vistas/supervisor/panel_supervisor.php' class='action_btn btn'>{$_SESSION['username']}</a></div>";
                        break;
                    case 'TU3': // Supervisor
                        echo "<div class='supervisor_btn'><a href='vistas/sempleado/panel_empleado.php' class='action_btn btn'>{$_SESSION['username']}</a></div>";
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
        <div class="dropdown_menu"> <-<!-- Desplega el menu hamburguesa cuando se achica el nav los suficiente -->
            <li><a href="#acerca">Nosotros</a></li>
            <li><a href="#habitaciones">Habitaciones</a></li>
            <li><a href="#servicios">Servicios</a></li>
            <li><a href="#contacto">Contacto</a></li>
            <li><a href="vistas/reserva.php" class="action_btn btn">Reservar</a></li>
            
            <?php
        if(isset($_SESSION['iduser'])) { // Si tiene una sesión iniciada Remplaza el link/boton de login 
            switch ($_SESSION['tipouser']) {
                case 'TU1':
                    echo "<li><a href='vistas/admin/panel_admin.php' class='action_btn btn'>ADMIN</a></li>";
                    break;
                case 'TU2':
                    echo "<li><a href='vistas/supervisor/panel_supervisor.php' class='action_btn btn'>Supervisro</a></li>";
                    break;
                case 'TU3':
                    echo "<li><a href='vistas/empleado/panel_empleado.php' class='action_btn btn'>Empleado</a></li>";
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
    <main>
    <div class="fondo1">
        <section class="hero">
            <div class="container">
                <h2>Bienvenido al</h2>
                <h2 style="font-size: 3rem; color: #59DE98">~GREEN GHOST~</h2>
                <p>Donde la elegancia se fusiona con el misterio. Nuestros servicios exclusivos realzan su esplendor único, redefiniendo el arte de la hospitalidad.
                Disfruta de una experiencia única en nuestro hotel de lujo.</p>
                <a href="vistas/reserva.php" class="button">Reserva Ahora</a> 
            </div>
            <div class="busqueda">
                <form action="vistas/reserva.php" method="post">
                    <label for="fecha-inicio">Fecha de Inicio:</label>
                    <input type="date" id="fecha_inicio" name="fecha_inicio" required>

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
        </section> 
        </div>
        
        <hr>
    
        <div class="fondocolor" id="acerca">
        <section  class="section" >
            <div class="container">
                <h2>Sobre Nosotros</h2> <br>
                <p style="color: white">Nuestro hotel ofrece un ambiente elegante y sofisticado para que disfrutes de una estancia inolvidable. Con instalaciones de primera clase y un servicio excepcional, estamos comprometidos a hacer que tu visita sea perfecta.</p>
                <br><p style="color: white">Descubre una hospitalidad personalizada en Green Ghost, donde cada detalle refleja el misterio de su individualidad. Dirigido por un equipo experto, nos especializamos en realzar sus características únicas, desde tratamientos 
                    rejuvenecedores de la piel hasta elegantes habitaciones. Nuestro compromiso va más allá de los servicios; creamos experiencias transformadoras de autoexpresión. Únase a nosotros para celebrar la elegancia y la individualidad de su historia única. Bienvenido a Green Ghost, donde la hospitalidad no solo se ve, sino que se siente en cada detalle personalizado.</p>
                <img src="img/stars.png" style="height: 150px" alt=""/>
            </div>
        </section>
        </div>
        <hr> 
        <section id="habitaciones" class="section">
            <div class="container">
                <h2>Nuestras Habitaciones</h2>
                <div class="rooms">
                    <div class="room">
                        <h3>Habitación Deluxe</h3>
                        <img src="img/habitacion_deluxe.jpg" alt="Habitación Deluxe"/>
                        <b><p>Confort y lujo en cada detalle.</p></b>
                        <p>
                            Esta habitación ofrece una experiencia cómoda y relajante con todas las comodidades esenciales. Cuenta con una cama queen size, un baño moderno, un televisor de pantalla plana y acceso a Wi-Fi de alta velocidad. La decoración elegante y los muebles de calidad crean un ambiente acogedor para descansar después de un día de exploración.
                        </p>
                    </div>
                    <div class="room">
                        <h3>Suite Ejecutiva</h3>
                        <img src="img/habitacion_ejecutiva.jpg" alt="Suite Ejecutiva"/>
                        <b><p>Espacio y elegancia para tu estancia.</p></b>
                        <p>
                           Diseñada pensando en el viajero de negocios, esta suite cuenta con un amplio espacio de trabajo, una sala de estar separada y servicios premium. Además de las comodidades de la Habitación Deluxe, la Suite Ejecutiva ofrece un minibar, una máquina de café espresso y un servicio de habitaciones 24 horas. El diseño sofisticado y la atención al detalle aseguran una estancia productiva y confortable.
                        </p>
                    </div>
                    <div class="room">
                        <h3>Suite Presidencial</h3>
                        <img src="img/habitacion_presindencial.jpg" alt="Suite Presidencial"/>
                        <b><p>La máxima expresión del lujo.</p></b>
                        <p>
                            Esta es la joya de la corona de nuestro hotel, ofreciendo lo último en lujo y exclusividad. La Suite Presidencial cuenta con un dormitorio principal, una sala de estar amplia, un comedor, un baño de lujo con jacuzzi y una terraza privada con vistas impresionantes. Los huéspedes disfrutarán de servicios personalizados, como un mayordomo privado, transporte al aeropuerto y acceso al lounge ejecutivo. Cada elemento de la suite está diseñado para proporcionar una experiencia inolvidable de lujo y confort.
                        </p>
                    </div>
                </div>
            </div>
        </section>
        <hr> 
        <section id="servicios" class="section">
            <div class="container">
                <h2>Servicios</h2>
                <p>Ofrecemos una amplia gama de servicios para satisfacer todas tus necesidades, incluyendo spa, gimnasio, bufet las 24 horas, piscina y mucho más.</p>
            </div>
        </section>
        <p id="contenido">
        
    </main>
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
    
</body>
</html>
