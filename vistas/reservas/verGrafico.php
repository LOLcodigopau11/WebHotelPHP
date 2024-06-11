<?php
session_start();
if ($_SESSION['tipouser'] != 'TU1') {
    header('Location: login.php');
    exit;   
} else if(isset($_SESSION['iduser'])) {
}
else {
    header("Location: login.php");
    exit;
}
// Conecta a la base de datos
$conn = mysqli_connect("localhost", "root", "", "greenghost");

// Verifica la conexión
if (!$conn) {
  die("Error de conexión: " . mysqli_connect_error());
}

// Ejecuta la consulta
$result = mysqli_query($conn, "SELECT MONTH(fecha_inicio) AS mes, YEAR(fecha_inicio) AS año, COUNT(id_reserva) AS cantidad_reservas FROM tb_reserva GROUP BY YEAR(fecha_inicio), MONTH(fecha_inicio) ORDER BY año, mes");

// Almacena los resultados en un array
$data = array();
while ($row = mysqli_fetch_assoc($result)) {
  $data[] = array(
    'mes' => $row['mes'],
    'año' => $row['año'],
    'cantidad_reservas' => $row['cantidad_reservas']
  );
}

// Cierra la conexión
mysqli_close($conn);
?>
<!DOCTYPE html>
<!--
Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/EmptyPHPWebPage.php to edit this template
-->
<html>
    <head>
    <meta charset="UTF-8">
    <title>Reservas por Mes</title>
    <link rel="icon" href="../../img/logo_1.png">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.7.0/chart.min.js"></script>
</head>
<body>
    <canvas id="myChart" width="400" height="200"></canvas>
    <script>
        // Obtén el contexto del canvas donde se dibujará el gráfico
        const ctx = document.getElementById('myChart').getContext('2d');

        // Datos del gráfico
        const data = {
            labels: <?php echo json_encode(array_map(function($row) { return $row['año'] . '-' . $row['mes']; }, $data)); ?>,
            datasets: [{
                label: 'Reservas por mes',
                data: <?php echo json_encode(array_column($data, 'cantidad_reservas')); ?>,
                backgroundColor: 'rgba(255, 99, 132, 0.2)',
                borderColor: 'rgba(255, 99, 132, 1)',
                borderWidth: 1
            }]
        };

        // Configuración del gráfico
        const config = {
            type: 'bar',
            data: data,
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        };

        // Crea el gráfico
        const myChart = new Chart(ctx, config);
    </script>
</body>
</html>