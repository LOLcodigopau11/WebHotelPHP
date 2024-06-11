<?php
require_once __DIR__ . '/../database/Conexion.php';

class ModeloReserva {
    private $cn;
    private $reservas;

    function __construct() {
        $this->cn = new Conexion();
        $this->reservas = array();
    }
    function getReservas() {
        $sql = "SELECT r.id_reserva AS 'N°', u.usuario AS 'Usuario',
                    CASE 
                      WHEN u.tipouser = 'TU4' THEN CONCAT(cl.nom_clie, ' ', cl.ape_clie)
                      WHEN u.tipouser IN ('TU1', 'TU2', 'TU3') THEN CONCAT(e.nom_emp, ' ', e.ape_emp)
                      ELSE 'Unknown'
                    END AS 'Nombre',
                    c.num_cuarto AS 'Cuarto', r.fecha_reserva AS 'Fecha', r.fecha_inicio AS 'Llegada',
                    r.fecha_fin AS 'Salida', r.dias AS 'Días', r.cant_personas AS 'Personas',
                    p.pago AS 'Costo'
                FROM 
                    tb_reserva r 
                INNER JOIN tb_usuario u ON r.iduser = u.iduser 
                INNER JOIN tb_cuarto c ON r.id_cuarto = c.id_cuarto 
                INNER JOIN tb_pago p ON r.pago_id = p.pago_id 
                LEFT JOIN tb_cliente cl ON u.iduser = cl.iduser AND u.tipouser = 'TU4'
                LEFT JOIN tb_empleado e ON u.iduser = e.iduser AND u.tipouser IN ('TU1', 'TU2', 'TU3')";
        $query = $this->cn->getCon()->query($sql);
        if (!$query) {
            die("Falla en la consulta: " . $this->cn->getCon()->error);
        }
        
        while ($fila = $query->fetch(PDO::FETCH_ASSOC)) {
            $this->reservas[] = $fila;
        }
        return $this->reservas;
    }
    function borrarReserva(){     
    }
}