CREATE DATABASE  IF NOT EXISTS `picel3` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `picel3`;
-- MySQL dump 10.13  Distrib 8.0.28, for Win64 (x86_64)
--
-- Host: localhost    Database: picel
-- ------------------------------------------------------
-- Server version	8.0.28

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `actividades_asignadas`
--

DROP TABLE IF EXISTS `actividades_asignadas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `actividades_asignadas` (
  `id_Actividades` int NOT NULL AUTO_INCREMENT,
  `id_Estudiante` int NOT NULL,
  `id_Evento` int NOT NULL,
  `id_Tarea` int NOT NULL,
  `fecha_ini` varchar(30) NOT NULL,
  `fecha_fin` varchar(30) NOT NULL,
  PRIMARY KEY (`id_Actividades`),
  KEY `FK_Actividades_Estudiantes` (`id_Estudiante`),
  KEY `FK_Actividades_Evento` (`id_Evento`),
  KEY `FK_Actividades_Tarea` (`id_Tarea`),
  CONSTRAINT `FK_Actividades_Estudiantes` FOREIGN KEY (`id_Estudiante`) REFERENCES `estudiantes` (`id_Estudiante`),
  CONSTRAINT `FK_Actividades_Evento` FOREIGN KEY (`id_Evento`) REFERENCES `evento` (`id_Evento`),
  CONSTRAINT `FK_Actividades_Tarea` FOREIGN KEY (`id_Tarea`) REFERENCES `tarea` (`id_Tarea`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `actividades_asignadas`
--

LOCK TABLES `actividades_asignadas` WRITE;
/*!40000 ALTER TABLE `actividades_asignadas` DISABLE KEYS */;
INSERT INTO `actividades_asignadas` VALUES (1,1,1,1,'2023-10-26 15:51:32','2023-10-26 15:51:32'),(2,2,3,4,'2023-10-26 15:51:32','2023-10-26 15:51:32');
/*!40000 ALTER TABLE `actividades_asignadas` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `docentes`
--

DROP TABLE IF EXISTS `docentes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `docentes` (
  `id_Docente` int NOT NULL AUTO_INCREMENT,
  `id_User` int DEFAULT NULL,
  `nombre` varchar(50) NOT NULL,
  `apellidoP` varchar(50) NOT NULL,
  `apellidoM` varchar(50) NOT NULL,
  `correo` varchar(50) NOT NULL,
  `carrera` varchar(35) NOT NULL,
  `num_celular` char(10) NOT NULL,
  PRIMARY KEY (`id_Docente`),
  KEY `FK_Docentes_Usuario` (`id_User`),
  CONSTRAINT `FK_Docentes_Usuario` FOREIGN KEY (`id_User`) REFERENCES `usuarios` (`id_User`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `docentes`
--

LOCK TABLES `docentes` WRITE;
/*!40000 ALTER TABLE `docentes` DISABLE KEYS */;
INSERT INTO `docentes` VALUES (1,4,'Juan pablo','Villa','Villagomez','JuanViVi@Docentes.itsur.edu.mx','Ing Sistemas','4174567898'),(2,5,'Dominic','Camargo','Ruiz','Comino@Docentes.itsur.edu.mx','Ing Sistemas','4171124565'),(3,6,'Socorro','García','Perez','SocorroGaPe@Docentes.itsur.edu.mx','Ing Sistemas','4454578967');
/*!40000 ALTER TABLE `docentes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `estudiantes`
--

DROP TABLE IF EXISTS `estudiantes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `estudiantes` (
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
  KEY `FK_Estudiantes_Usuario` (`id_User`),
  CONSTRAINT `FK_Estudiantes_Usuario` FOREIGN KEY (`id_User`) REFERENCES `usuarios` (`id_User`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `estudiantes`
--

LOCK TABLES `estudiantes` WRITE;
/*!40000 ALTER TABLE `estudiantes` DISABLE KEYS */;
INSERT INTO `estudiantes` VALUES (1,1,'s20120224','Jesse Santiago','Barrera','Zuñiga','s20120224@alumnos.itsur.edu.mx','Ing Sistemas',7,'4171128536'),(2,2,'s20120176','Azucena','Camargo','Ruiz','s20120176@alumnos.itsur.edu.mx','Ing Sistemas',7,'4171027589'),(3,3,'s20120212','Jose Martin','García','Martínez','s20120212@alumnos.itsur.edu.mx','Ing Sistemas',7,'4452163920');
/*!40000 ALTER TABLE `estudiantes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `evento`
--

DROP TABLE IF EXISTS `evento`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `evento` (
  `id_Evento` int NOT NULL AUTO_INCREMENT,
  `id_Docente` int NOT NULL,
  `periodo` varchar(50) NOT NULL,
  `nombre` text NOT NULL,
  `descrip` text,
  PRIMARY KEY (`id_Evento`),
  KEY `FK_Evento_Docente` (`id_Docente`),
  CONSTRAINT `FK_Evento_Docente` FOREIGN KEY (`id_Docente`) REFERENCES `docentes` (`id_Docente`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `evento`
--

LOCK TABLES `evento` WRITE;
/*!40000 ALTER TABLE `evento` DISABLE KEYS */;
INSERT INTO `evento` VALUES (1,1,'AGO-DIC','Deletreo master','El participante tiene que deletrar una serie de palabras que el jurado dará en su momento'),(2,2,'ENE-JUL','Trivia','Se realizarán la lectura de un libro del cual se responderan preguntas de diversas parte del libro'),(3,3,'ENE-JUL','Dibujo','Los participantes dibujarán un personaje de día de muestos que sera original'),(4,3,'AGO-JUL','Calaberita','Los participantes harán una calaberita sobre algun maestro.');
/*!40000 ALTER TABLE `evento` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tarea`
--

DROP TABLE IF EXISTS `tarea`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tarea` (
  `id_Tarea` int NOT NULL AUTO_INCREMENT,
  `nombre` text NOT NULL,
  `descrip` text,
  PRIMARY KEY (`id_Tarea`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tarea`
--

LOCK TABLES `tarea` WRITE;
/*!40000 ALTER TABLE `tarea` DISABLE KEYS */;
INSERT INTO `tarea` VALUES (1,'Reportes','Realizar un reporte sobre el evento, debe contener...'),(2,'Fotos','Tomar fotos del evento y editarlos para que se ven bien nice'),(3,'Comida','llevarle comida a los otros miembros de equipo'),(4,'Organizar','Agendarle tarear y eventos a cada alumno');
/*!40000 ALTER TABLE `tarea` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `usuarios`
--

DROP TABLE IF EXISTS `usuarios`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `usuarios` (
  `id_User` int NOT NULL AUTO_INCREMENT,
  `usuario` varchar(100) NOT NULL,
  `contrasena` varchar(250) NOT NULL,
  `tipo_us` enum('Admin','Estudiante','Docente') NOT NULL,
  PRIMARY KEY (`id_User`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `usuarios`
--

LOCK TABLES `usuarios` WRITE;
/*!40000 ALTER TABLE `usuarios` DISABLE KEYS */;
INSERT INTO `usuarios` VALUES (1,'jesseLoco','eljesse','Admin'),(2,'Jose','12345','Admin'),(3,'Azu','12345','Docente'),(4,'David','12345','Docente'),(5,'Sofia','12345','Estudiante'),(6,'Pedro','12345','Estudiante');
/*!40000 ALTER TABLE `usuarios` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping routines for database 'picel'
--
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2023-11-08 15:56:54
