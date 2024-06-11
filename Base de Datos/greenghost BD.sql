create database greenghost;

use greenghost;

create table tb_tipouser(
	tipouser char(3) not null primary key,
    tipo varchar(25) not null
);

insert into tb_tipouser values ('TU1', 'admin'), ('TU2', 'supervisor'), ('TU3', 'empleado'), ('TU4', 'cliente');

create table tb_usuario(
	iduser int auto_increment not null primary key,
    usuario varchar(25) unique not null,
    contra varchar(25) not null,
    tipouser char(3) not null,
    foreign key (tipouser) references tb_tipouser(tipouser)
);

insert into tb_usuario (usuario, contra, tipouser) values ('admin', 'admin123', 'TU1'),
	('carlin', 'carlin123', 'TU1'),
    ('albert11', 'albert123', 'TU2'),
    ('marial', 'maril123', 'TU3'),
    ('mmani', 'mamani123', 'TU3'),
    ('juliox', 'juli123', 'TU4');

create table tb_cliente(
	idclie int auto_increment not null primary key,
    nom_clie varchar(25) not null,
    ape_clie varchar(25) not null,
    dni_clie char(8) unique not null ,
    telefono_clie varchar(50) unique not null ,
    correo_clie varchar(30) not null,
    direccion_clie varchar(50),
    iduser int not null,
    foreign key (iduser) references tb_usuario(iduser)
);

insert into tb_cliente (nom_clie, ape_clie, dni_clie, telefono_clie, correo_clie, direccion_clie, iduser) values
('Julio', 'August Cornet', '87655583', '900000008', 'julin@example.com', 'Cordisep 142', 6);


create table tb_cargo(
	idcargo char(3) not null primary key,
    cargo varchar(25) not null unique,
    sueldo double not null
);

insert into tb_cargo values('C01', 'Gerente', 3200),
	('C02', 'Adminisrador', 2600),
    ('C03', 'Supervisor', 2200),
    ('C04', 'Cocinero', 2000),
    ('C05', 'Limpieza', 1800),
    ('C06', 'botonos', 1810),
    ('C07', 'Recepcionista', 1900),
    ('C08', 'Botones', 1800);


create table tb_empleado(
	idemp int auto_increment not null primary key,
    nom_emp varchar(25) not null,
    ape_emp varchar(25) not null,
    dni_emp char(8) unique not null ,
    telefono_emp char(9) unique not null ,
    correo_emp varchar(30) unique not null,
    direccion_emp varchar(50) not null,
    idcargo char(3) not null,
    iduser int not null,
    foreign key (idcargo) references tb_cargo(idcargo),
    foreign key (iduser) references tb_usuario(iduser)
);

insert into tb_empleado (nom_emp, ape_emp, dni_emp, telefono_emp, correo_emp, direccion_emp, idcargo, iduser) values
	('Dexter', 'Buena Fibra', '87654321', '900000001', 'dexter@email.com', 'Saltadilla 104', 'C01', 1),
    ('Carlos', 'Carlin Camacho', '87654322', '900000002', 'carlin@email.com','fuentealvilla 14', 'C02', 2),
    ('Alberto', 'Jara Lara', '87654323', '900000003', 'jarlar@email.com','llano 04', 'C03', 3),
    ('Maria', 'Magadalena Mina', '87654343', '900000004', 'marian@email.com','Giron Union 41', 'C04', 4),
    ('Mando', 'Lariano Skrilex', '87654353', '900000005', 'mandalor@email.com','Greco 152', 'C06', 5);

CREATE TABLE tb_tipo_cuarto (
	t_cuarto char(4) PRIMARY KEY not null,
	nombre VARCHAR(50) not null,
	descripcion VARCHAR(255) not null,
    foto varchar(255)
);

insert into tb_tipo_cuarto values ('TC01', 'Habitación Deluxe', 'Confort y Lujo en cada detalle. TODO INCLUIDO', '../img/habitacion_deluxe.jpg'),
('TC02', 'Suite Ejecutiva', 'Espacio y Elegancia para tu Estancia. TODO INCLUIDO', '../img/habitacion_ejecutiva.jpg'),
('TC03', 'Suite Presidencial', 'La Máxima Expresión del Lujo. TODO INCLUIDO', '../img/habitacion_presidencial.jpg');

CREATE TABLE tb_cuarto (
    id_cuarto INT PRIMARY KEY AUTO_INCREMENT not null,
    num_cuarto char(4) UNIQUE not null,
    t_cuarto char(4) not null,
    capacidad INT not null,
    precio_noche DECIMAL(10, 2) not null,
    foreign key (t_cuarto) references tb_tipo_cuarto(t_cuarto)
);

INSERT INTO tb_cuarto (num_cuarto, t_cuarto, capacidad, precio_noche) values
('CT01', 'TC01', 4, 200),
('CT02', 'TC01', 5, 240),
('CT03', 'TC01', 5, 240),
('CT04', 'TC01', 6, 260),
('CT05', 'TC02', 6, 320),
('CT06', 'TC02', 7, 340),
('CT07', 'TC02', 7, 350),
('CT08', 'TC02', 8, 380),
('CT09', 'TC03', 8, 400),
('CT10', 'TC03', 9, 420),
('CT11', 'TC03', 10, 460),
('CT12', 'TC03', 10, 460);

CREATE TABLE tb_pago (
	pago_id INT auto_increment PRIMARY KEY not null,
	pago DECIMAL(10, 2) not null,
	fecha_pago timestamp not null,
	metodo_pago VARCHAR(50) not null
);

CREATE TABLE tb_reserva (
    id_reserva INT PRIMARY KEY AUTO_INCREMENT not null,
    iduser INT not null,
    id_cuarto INT not null,
    fecha_reserva timestamp not null DEFAULT CURRENT_TIMESTAMP,
    fecha_inicio DATE not null,
    fecha_fin DATE not null,
    dias int not null,
    cant_personas int not null,
    pago_id int not null,
    FOREIGN KEY (iduser) REFERENCES tb_usuario(iduser),
    FOREIGN KEY (id_cuarto) REFERENCES tb_cuarto(id_cuarto),
    FOREIGN KEY (pago_id) REFERENCES tb_pago(pago_id),
    CONSTRAINT fecha_valida CHECK (fecha_inicio < fecha_fin)
);