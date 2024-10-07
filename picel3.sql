-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1:3306
-- Tiempo de generación: 07-10-2024 a las 01:42:04
-- Versión del servidor: 8.3.0
-- Versión de PHP: 8.2.18

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `picel3`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `actividades_asignadas`
--

DROP TABLE IF EXISTS `actividades_asignadas`;
CREATE TABLE IF NOT EXISTS `actividades_asignadas` (
  `id_Actividades` int NOT NULL AUTO_INCREMENT,
  `id_Evento` int NOT NULL,
  `fecha_ini` varchar(30) NOT NULL,
  `id_Docente` int NOT NULL,
  `Nombre` varchar(100) NOT NULL,
  PRIMARY KEY (`id_Actividades`),
  KEY `FK_Actividades_Evento` (`id_Evento`),
  KEY `fk_estudiante` (`id_Docente`)
) ENGINE=MyISAM AUTO_INCREMENT=65 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `actividades_asignadas`
--

INSERT INTO `actividades_asignadas` (`id_Actividades`, `id_Evento`, `fecha_ini`, `id_Docente`, `Nombre`) VALUES
(64, 1, '2023-12-15', 2, 'm jm'),
(63, 1, '2023-01-15', 5, 'N2'),
(62, 1, '2023-12-15', 5, 'ACT FINAL');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `docentes`
--

DROP TABLE IF EXISTS `docentes`;
CREATE TABLE IF NOT EXISTS `docentes` (
  `id_Docente` int NOT NULL AUTO_INCREMENT,
  `id_User` int DEFAULT NULL,
  `nombre` varchar(50) NOT NULL,
  `apellidoP` varchar(50) NOT NULL,
  `apellidoM` varchar(50) NOT NULL,
  `correo` varchar(50) NOT NULL,
  `carrera` varchar(35) NOT NULL,
  `num_celular` char(10) NOT NULL,
  PRIMARY KEY (`id_Docente`),
  KEY `FK_Docentes_Usuario` (`id_User`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `docentes`
--

INSERT INTO `docentes` (`id_Docente`, `id_User`, `nombre`, `apellidoP`, `apellidoM`, `correo`, `carrera`, `num_celular`) VALUES
(1, 4, 'Juan pablo', 'Villa', 'Villagomez', 'JuanViVi@Docentes.itsur.edu.mx', 'Ing Sistemas', '4174567898'),
(2, 2, 'Dominic', 'Camargo', 'Ruiz', 'Comino@Docentes.itsur.edu.mx', 'Ing Sistemas', '4171124565'),
(3, 6, 'Socorro', 'García', 'Perez', 'SocorroGaPe@Docentes.itsur.edu.mx', 'Ing Sistemas', '4454578967'),
(5, 8, 'BRAYAN', 'SANCHEZ', 'MONROY', 'S290@ALUMNOS.COM', 'ING SISTEMAS COMPUTACIONALES', '123451');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estudiantes`
--

DROP TABLE IF EXISTS `estudiantes`;
CREATE TABLE IF NOT EXISTS `estudiantes` (
  `id_Estudiante` int NOT NULL AUTO_INCREMENT,
  `id_User` int DEFAULT NULL,
  `num_control` char(9) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `apellidoP` varchar(50) NOT NULL,
  `apellidoM` varchar(50) NOT NULL,
  `correo` varchar(50) NOT NULL,
  `carrera` varchar(25) NOT NULL,
  `semestre` int NOT NULL,
  `num_celular` char(10) NOT NULL,
  PRIMARY KEY (`id_Estudiante`),
  KEY `FK_Estudiantes_Usuario` (`id_User`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `estudiantes`
--

INSERT INTO `estudiantes` (`id_Estudiante`, `id_User`, `num_control`, `nombre`, `apellidoP`, `apellidoM`, `correo`, `carrera`, `semestre`, `num_celular`) VALUES
(1, 1, 's20120224', 'Jesse Santiago', 'Barrera', 'Zuñiga', 's20120224@alumnos.itsur.edu.mx', 'Ing Sistemas', 7, '4171128536'),
(2, 2, 's20120176', 'Azucena', 'Camargo', 'Ruiz', 's20120176@alumnos.itsur.edu.mx', 'Ing Sistemas', 7, '4171027589'),
(3, 3, 's20120212', 'Jose Martin', 'García', 'Martínez', 's20120212@alumnos.itsur.edu.mx', 'Ing Sistemas', 7, '4452163920');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `evento`
--

DROP TABLE IF EXISTS `evento`;
CREATE TABLE IF NOT EXISTS `evento` (
  `id_Evento` int NOT NULL AUTO_INCREMENT,
  `id_Docente` int NOT NULL,
  `periodo` varchar(50) NOT NULL,
  `nombre` text NOT NULL,
  `descripcion` text,
  PRIMARY KEY (`id_Evento`),
  KEY `FK_Evento_Docente` (`id_Docente`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `evento`
--

INSERT INTO `evento` (`id_Evento`, `id_Docente`, `periodo`, `nombre`, `descripcion`) VALUES
(1, 1, 'AGO-DIC', 'Deletreo master', 'NADA'),
(2, 2, 'ENE-JUL', 'Trivia', 'Se realizarán la lectura de un libro del cual se responderan preguntas de diversas parte del libro'),
(3, 3, 'ENE-JUL', 'Dibujo', 'Los participantes dibujarán un personaje de día de muestos que sera original'),
(4, 3, 'AGO-JUL', 'Calaberita', 'Los participantes harán una calaberita sobre algun maestro.');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tarea`
--

DROP TABLE IF EXISTS `tarea`;
CREATE TABLE IF NOT EXISTS `tarea` (
  `id_Tarea` int NOT NULL AUTO_INCREMENT,
  `nombre` text NOT NULL,
  `descripcion` text,
  `id_Actividad` int NOT NULL,
  `id_Estudiante` varchar(9) NOT NULL,
  PRIMARY KEY (`id_Tarea`),
  KEY `tarea_act_asig` (`id_Actividad`)
) ENGINE=MyISAM AUTO_INCREMENT=96 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `tarea`
--

INSERT INTO `tarea` (`id_Tarea`, `nombre`, `descripcion`, `id_Actividad`, `id_Estudiante`) VALUES
(90, 'ACT FINAL', 'ijm', 62, 's20120212'),
(89, 'ACT FINAL', 'JHBNJ', 62, 's20120176'),
(88, 'ACT FINAL', 'mnjhbvghbnj', 62, 's20120224'),
(91, 'N2', 'mnjhbvghbnj', 63, 's20120224'),
(92, 'N2', 'NUEVO', 63, 's20120176'),
(93, 'm jm', 'mnjhbvghbnj', 64, 's20120224'),
(94, 'm jm', 'NUEVO', 64, 's20120176'),
(95, 'm jm', 'jnjnjn', 64, 's20120212');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

DROP TABLE IF EXISTS `usuarios`;
CREATE TABLE IF NOT EXISTS `usuarios` (
  `id_User` int NOT NULL AUTO_INCREMENT,
  `usuario` varchar(100) NOT NULL,
  `contrasena` varchar(250) NOT NULL,
  `tipo_us` enum('Admin','Estudiante','Docente') NOT NULL,
  PRIMARY KEY (`id_User`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id_User`, `usuario`, `contrasena`, `tipo_us`) VALUES
(1, 'jesseLoco', 'eljesse', 'Admin'),
(2, 'Jose', '12345', 'Admin'),
(3, 'Azu', '12345', 'Docente'),
(4, 'David', '12345', 'Docente'),
(5, 'Sofia', '12345', 'Estudiante'),
(6, 'Pedro', '12345', 'Estudiante'),
(8, 'BRAYAN', '12345', 'Admin');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
