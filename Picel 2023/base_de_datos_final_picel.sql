drop database picel;
create database picel;
use picel;

CREATE TABLE Usuarios (
  id_User int primary key auto_increment,
  usuario varchar(100) not null,
  contrasena varchar(250) NOT NULL,
  tipo_us enum("Admin","Estudiante","Docente") NOT NULL
);

CREATE TABLE Docentes (
  id_Docente int primary key auto_increment,
  id_User int,
  nombre varchar(50) NOT NULL,
  apellidoP varchar(50) NOT NULL,
  apellidoM varchar(50) NOT NULL,
  correo varchar(50) NOT NULL,
  carrera varchar(35) NOT NULL,
  num_celular char(10) NOT NULL,
  constraint FK_Docentes_Usuario foreign key (id_User) references usuarios(id_User)
);

CREATE TABLE Estudiantes (
  id_Estudiante int primary key auto_increment,
  id_User int,
  num_control char(9) NOT NULL,
  nombre varchar(50) NOT NULL,
  apellidoP varchar(50) NOT NULL,
  apellidoM varchar(50) NOT NULL,
  correo varchar(50) NOT NULL,
  carrera varchar(25) NOT NULL,
  semestre int NOT NULL,
  num_celular char(10) NOT NULL,
  constraint FK_Estudiantes_Usuario foreign key (id_User) references Usuarios(id_User)
);

CREATE TABLE Evento (
  id_Evento int primary key auto_increment,
  id_Docente int not null,
  periodo varchar(50) NOT NULL,
  nombre text NOT NULL,
  descrip text ,
  constraint FK_Evento_Docente foreign key (id_Docente) references docentes(id_Docente)
);

CREATE TABLE Tarea (
  id_Tarea int primary key auto_increment,
  nombre text NOT NULL,
  descrip text
);

CREATE TABLE Actividades_Asignadas (
  id_Actividades int primary key auto_increment,
  id_Estudiante int NOT NULL,
  id_Evento int NOT NULL,
  id_Tarea int NOT NULL,
  fecha_ini varchar(30) NOT NULL,
  fecha_fin varchar(30) NOT NULL,
  constraint FK_Actividades_Estudiantes foreign key (id_Estudiante) references Estudiantes(id_Estudiante),
  constraint FK_Actividades_Evento foreign key (id_Evento) references Evento(id_Evento),
  constraint FK_Actividades_Tarea foreign key (id_Tarea) references Tarea(id_Tarea)
);

insert into Usuarios(usuario,contrasena,tipo_us) values
("jesseLoco","eljesse","Admin"),("Jose","12345","Admin"),
("Azu","12345","Docente"),("David","12345","Docente"),
("Sofia","12345","Estudiante"),("Pedro","12345","Estudiante");

insert into estudiantes (id_User,num_control,nombre,apellidoP,apellidoM,correo,carrera,semestre,num_celular) values
(1,"s20120224","Jesse Santiago","Barrera","Zuñiga","s20120224@alumnos.itsur.edu.mx","Ing Sistemas",7,"4171128536"),
(2,"s20120176","Azucena","Camargo","Ruiz","s20120176@alumnos.itsur.edu.mx","Ing Sistemas",7,"4171027589"),
(3,"s20120212","Jose Martin","García","Martínez","s20120212@alumnos.itsur.edu.mx","Ing Sistemas",7,"4452163920");

insert into docentes (id_User,nombre,apellidoP,apellidoM,correo,carrera,num_celular) values
(4,"Juan pablo","Villa","Villagomez","JuanViVi@Docentes.itsur.edu.mx","Ing Sistemas","4174567898"),
(5,"Dominic","Camargo","Ruiz","Comino@Docentes.itsur.edu.mx","Ing Sistemas","4171124565"),
(6,"Socorro","García","Perez","SocorroGaPe@Docentes.itsur.edu.mx","Ing Sistemas","4454578967");


insert into evento (id_Docente,periodo,nombre,descrip) values
(1,"AGO-DIC","Deletreo master","El participante tiene que deletrar una serie de palabras que el jurado dará en su momento"),
(2,"ENE-JUL","Trivia","Se realizarán la lectura de un libro del cual se responderan preguntas de diversas parte del libro"),
(3,"ENE-JUL","Dibujo","Los participantes dibujarán un personaje de día de muestos que sera original"),
(3,"AGO-JUL","Calaberita","Los participantes harán una calaberita sobre algun maestro.");

insert into Tarea (nombre,descrip) values
("Reportes","Realizar un reporte sobre el evento, debe contener..."),
("Fotos","Tomar fotos del evento y editarlos para que se ven bien nice"),
("Comida","llevarle comida a los otros miembros de equipo"),
("Organizar","Agendarle tarear y eventos a cada alumno");

Insert into Actividades_Asignadas (id_Estudiante,id_Evento,id_Tarea,fecha_ini,fecha_fin) values
(1,1,1,now(),now()),
(2,3,4,now(),now());
describe usuarios;
select * from usuarios;
select * from estudiantes;
select * from docentes;
select * from evento;
select * from tarea;
select * from actividades_asignadas;

select *  from docentes d, usuarios u where d.id_user=u.id_user and u.usuario="David";
SELECT * FROM docentes d, usuarios u WHERE d.id_user = u.id_user and u.usuario='David'; 	
SELECT * FROM docentes d, usuarios u WHERE d.id_user=u.id_user and u.usuario= "David";
