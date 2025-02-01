-- =====================================================================
-- 1. Eliminar tablas y base de datos si existen
-- =====================================================================
DROP TABLE IF EXISTS `tarea`;
DROP TABLE IF EXISTS `actividades_asignadas`;
DROP TABLE IF EXISTS `evento`;
DROP TABLE IF EXISTS `estudiantes`;
DROP TABLE IF EXISTS `docentes`;
DROP TABLE IF EXISTS `usuarios`;

DROP DATABASE IF EXISTS PICEL3;

-- =====================================================================
-- 2. Crear la base de datos y usarla
-- =====================================================================
CREATE DATABASE PICEL3;
USE PICEL3;

-- =====================================================================
-- 3. Crear tabla de usuarios
-- =====================================================================
CREATE TABLE `usuarios` (
  `id_User` int NOT NULL AUTO_INCREMENT,
  `usuario` varchar(100) NOT NULL,
  `contrasena` varchar(250) NOT NULL,
  `tipo_us` enum('Admin','Estudiante','Docente') NOT NULL,
  PRIMARY KEY (`id_User`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- =====================================================================
-- 4. Crear tabla de docentes
-- =====================================================================
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
  FOREIGN KEY (`id_User`) REFERENCES `usuarios`(`id_User`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- =====================================================================
-- 5. Crear tabla de estudiantes
-- =====================================================================
CREATE TABLE `estudiantes` (
  `id_Estudiante` int NOT NULL AUTO_INCREMENT,
  `id_User` int DEFAULT NULL,
  `num_control` char(9) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `apellidoP` varchar(50) NOT NULL,
  `apellidoM` varchar(50) NOT NULL,
  `correo` varchar(50) NOT NULL,
  `carrera` varchar(50) NOT NULL,
  `semestre` int NOT NULL,
  `num_celular` char(10) NOT NULL,
  `id_Docente` int NOT NULL,
  PRIMARY KEY (`id_Estudiante`),
  FOREIGN KEY (`id_User`) REFERENCES `usuarios`(`id_User`) ON DELETE SET NULL,
  FOREIGN KEY (`id_Docente`) REFERENCES `docentes`(`id_Docente`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- =====================================================================
-- 6. Crear tabla de eventos
-- =====================================================================
CREATE TABLE `evento` (
  `id_Evento` int NOT NULL AUTO_INCREMENT,
  `id_Docente` int NOT NULL,
  `periodo` varchar(50) NOT NULL,
  `nombre` text NOT NULL,
  `descripcion` text,
  PRIMARY KEY (`id_Evento`),
  FOREIGN KEY (`id_Docente`) REFERENCES `docentes`(`id_Docente`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- =====================================================================
-- 7. Crear tabla de actividades asignadas
-- =====================================================================
CREATE TABLE `actividades_asignadas` (
  `id_Actividades` int NOT NULL AUTO_INCREMENT,
  `id_Evento` int NOT NULL,
  `fecha_ini` varchar(30) NOT NULL,
  `id_Docente` int NOT NULL,
  `Nombre` varchar(100) NOT NULL,
  PRIMARY KEY (`id_Actividades`),
  FOREIGN KEY (`id_Evento`) REFERENCES `evento`(`id_Evento`) ON DELETE CASCADE,
  FOREIGN KEY (`id_Docente`) REFERENCES `docentes`(`id_Docente`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- =====================================================================
-- 8. Crear tabla de tareas
-- =====================================================================
CREATE TABLE `tarea` (
  `id_Tarea` int NOT NULL AUTO_INCREMENT,
  `nombre` text NOT NULL,
  `descripcion` text,
  `id_Actividad` int NOT NULL,
  `id_Estudiante` int NOT NULL,
  `Estatus` tinyint(1) NOT NULL,
  `Anotaciones` varchar(300) NOT NULL,
  PRIMARY KEY (`id_Tarea`),
  FOREIGN KEY (`id_Actividad`) REFERENCES `actividades_asignadas`(`id_Actividades`) ON DELETE CASCADE,
  FOREIGN KEY (`id_Estudiante`) REFERENCES `estudiantes`(`id_Estudiante`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- =====================================================================
-- 9. Insertar datos en la tabla de usuarios
-- =====================================================================
INSERT INTO `usuarios` (`usuario`, `contrasena`, `tipo_us`) VALUES
  ('admin1', '12345', 'admin'),
  ('admin2', '12345', 'Admin'),
  ('docente1', '12345', 'Docente'),
  ('docente2', '12345', 'Docente'),
  ('docente3', '12345', 'Docente'),
  ('estudiante1', '12345', 'Estudiante'),
  ('estudiante2', '12345', 'Estudiante'),
  ('estudiante3', '12345', 'Estudiante'),
  ('estudiante4', '12345', 'Estudiante'),
  ('estudiante5', '12345', 'Estudiante'),
  ('estudiante6', '12345', 'Estudiante'),
  ('estudiante7', '12345', 'Estudiante'),
  ('estudiante8', '12345', 'Estudiante'),
  ('estudiante9', '12345', 'Estudiante'),
  ('estudiante10', '12345', 'Estudiante');

-- =====================================================================
-- 10. Insertar datos en la tabla de docentes
-- =====================================================================
INSERT INTO `docentes` (`id_User`, `nombre`, `apellidoP`, `apellidoM`, `correo`, `carrera`, `num_celular`) VALUES
  (1, 'Bryan', 'Sanchez', 'Monroy', 'bryan@docentes.com', 'ING SISTEMAS COMPUTACIONALES', '4171122233'),
  (2, 'Marta', 'Sanchez', 'Torres', 'marta@docentes.com', 'ING INDUSTRIAL', '4174455678'),
  (3, 'Juan Pablo', 'Villa', 'Villagomez', 'juan@docentes.com', 'ING ELECTRONICA', '4174567898'),
  (4, 'Sofia', 'Lopez', 'Gonzalez', 'sofia@docentes.com', 'ING SISTEMAS COMPUTACIONALES', '4171124565'),
  (5, 'Carlos', 'Diaz', 'Perez', 'carlos@docentes.com', 'ING GESTION EMPRESARIAL', '4454578967');

-- =====================================================================
-- 11. Insertar datos en la tabla de estudiantes
-- =====================================================================
INSERT INTO `estudiantes` (`id_User`, `num_control`, `nombre`, `apellidoP`, `apellidoM`, `correo`, `carrera`, `semestre`, `num_celular`, `id_Docente`) VALUES
  (6, 'S20120224', 'Jesse Santiago', 'Barrera', 'Zuñiga', 's20120224@alumnos.com', 'ING SISTEMAS COMPUTACIONALES', 7, '4171128536', 1),
  (7, 'S20120176', 'Azucena', 'Camargo', 'Ruiz', 's20120176@alumnos.com', 'ING AMBIENTAL', 7, '4171027589', 4),
  (8, 'S20120212', 'Jose Martin', 'García', 'Martínez', 's20120212@alumnos.com', 'ING GESTION EMPRESARIAL', 7, '4452163920', 5),
  (9, 'S20120214', 'Pablo', 'Lopez', 'Gomez', 'pablo@alumnos.com', 'ING ELECTRONICA', 5, '4174589623', 3),
  (10, 'S20120215', 'Carla', 'Ruiz', 'Torres', 'carla@alumnos.com', 'ING SISTEMAS COMPUTACIONALES', 6, '4174532156', 4),
  (11, 'S20120216', 'Jose', 'Hernandez', 'Lopez', 'jose@alumnos.com', 'ING AMBIENTAL', 5, '4178753121', 5),
  (12, 'S20120217', 'Laura', 'Gutierrez', 'Reyes', 'laura@alumnos.com', 'ING GESTION EMPRESARIAL', 6, '4453198620', 3),
  (13, 'S20120218', 'Luis', 'Martinez', 'Vazquez', 'luis@alumnos.com', 'ING SISTEMAS COMPUTACIONALES', 8, '4176548237', 4),
  (14, 'S20120219', 'Pedro', 'Ramirez', 'Mora', 'pedro@alumnos.com', 'ING AMBIENTAL', 9, '4173128956', 5),
  (15, 'S20120220', 'Marcela', 'Perez', 'Moreno', 'marcela@alumnos.com', 'ING GESTION EMPRESARIAL', 4, '4171254765', 3);

-- =====================================================================
-- 12. Insertar datos en la tabla de eventos
-- =====================================================================
INSERT INTO `evento` (`id_Docente`, `periodo`, `nombre`, `descripcion`) VALUES
  (3, 'AGO-DIC', 'Deletreo Master', 'Evento de deletreo entre estudiantes de ingeniería electrónica'),
  (4, 'ENE-JUL', 'Trivia Literaria', 'Trivia basada en preguntas de lectura de libros'),
  (5, 'ENE-JUL', 'Concurso de Dibujo', 'Concurso de dibujo de personajes del día de muertos'),
  (3, '2025-1', 'Curso Introducción a la Programación', 'Curso básico de programación para sistemas computacionales'),
  (4, '2025-1', 'Curso de Ecología Básica', 'Curso de fundamentos de la ecología y el medio ambiente'),
  (5, 'AGO-JUL', 'Calaverita', 'Los estudiantes crearán una calaverita para los maestros');

-- =====================================================================
-- 13. Insertar datos en la tabla de actividades asignadas
--
-- Se crean actividades de ejemplo a partir de los eventos creados. Cada actividad
-- está asociada a un docente (id_Docente) y a un evento (id_Evento).
-- =====================================================================
INSERT INTO `actividades_asignadas` (`id_Evento`, `fecha_ini`, `id_Docente`, `Nombre`) VALUES
  (1, '2025-01-15', 3, 'Actividad Deletreo - Nivel 1'),
  (2, '2025-02-01', 4, 'Trivia - Primera Ronda'),
  (3, '2025-03-10', 5, 'Boceto Inicial'),
  (4, '2025-01-20', 3, 'Programación - Lección 1'),
  (5, '2025-02-10', 4, 'Ecología - Introducción'),
  (6, '2025-04-05', 5, 'Calaverita - Creación');

-- =====================================================================
-- 14. Insertar datos en la tabla de tareas
--
-- Las tareas se asignan a los estudiantes según el docente tutor que tienen asignado.
--
-- Recordatorio de asignación de estudiantes a docentes:
--   - Docente 3: Pablo (id_Estudiante 4), Laura (id_Estudiante 7) y Marcela (id_Estudiante 10)
--   - Docente 4: Azucena (id_Estudiante 2), Carla (id_Estudiante 5) y Luis (id_Estudiante 8)
--   - Docente 5: Jose Martin (id_Estudiante 3), Jose (id_Estudiante 6) y Pedro (id_Estudiante 9)
-- =====================================================================

-- Tareas para la Actividad 1 (id_Actividades = 1, dictada por docente 3)
INSERT INTO `tarea` (`nombre`, `descripcion`, `id_Actividad`, `id_Estudiante`, `Estatus`, `Anotaciones`) VALUES
  ('Tarea Deletreo Nivel 1 - Pablo', 'Realizar ejercicio de deletreo básico', 1, 4, 0, ''),
  ('Tarea Deletreo Nivel 1 - Laura', 'Realizar ejercicio de deletreo básico', 1, 7, 0, ''),
  ('Tarea Deletreo Nivel 1 - Marcela', 'Realizar ejercicio de deletreo básico', 1, 10, 0, '');

-- Tareas para la Actividad 2 (id_Actividades = 2, dictada por docente 4)
INSERT INTO `tarea` (`nombre`, `descripcion`, `id_Actividad`, `id_Estudiante`, `Estatus`, `Anotaciones`) VALUES
  ('Tarea Trivia Ronda 1 - Azucena', 'Participar en la trivia y responder correctamente', 2, 2, 0, ''),
  ('Tarea Trivia Ronda 1 - Carla', 'Participar en la trivia y responder correctamente', 2, 5, 0, ''),
  ('Tarea Trivia Ronda 1 - Luis', 'Participar en la trivia y responder correctamente', 2, 8, 0, '');

-- Tareas para la Actividad 3 (id_Actividades = 3, dictada por docente 5)
INSERT INTO `tarea` (`nombre`, `descripcion`, `id_Actividad`, `id_Estudiante`, `Estatus`, `Anotaciones`) VALUES
  ('Tarea Boceto Inicial - Jose Martin', 'Realizar el boceto inicial del concurso de dibujo', 3, 3, 0, ''),
  ('Tarea Boceto Inicial - Jose', 'Realizar el boceto inicial del concurso de dibujo', 3, 6, 0, ''),
  ('Tarea Boceto Inicial - Pedro', 'Realizar el boceto inicial del concurso de dibujo', 3, 9, 0, '');

-- Tareas para la Actividad 4 (id_Actividades = 4, dictada por docente 3)
INSERT INTO `tarea` (`nombre`, `descripcion`, `id_Actividad`, `id_Estudiante`, `Estatus`, `Anotaciones`) VALUES
  ('Tarea Programación Lección 1 - Pablo', 'Completar ejercicios de introducción a la programación', 4, 4, 0, ''),
  ('Tarea Programación Lección 1 - Laura', 'Completar ejercicios de introducción a la programación', 4, 7, 0, ''),
  ('Tarea Programación Lección 1 - Marcela', 'Completar ejercicios de introducción a la programación', 4, 10, 0, '');

-- Tareas para la Actividad 5 (id_Actividades = 5, dictada por docente 4)
INSERT INTO `tarea` (`nombre`, `descripcion`, `id_Actividad`, `id_Estudiante`, `Estatus`, `Anotaciones`) VALUES
  ('Tarea Ecología Introducción - Azucena', 'Realizar lectura y resumen sobre ecología básica', 5, 2, 0, ''),
  ('Tarea Ecología Introducción - Carla', 'Realizar lectura y resumen sobre ecología básica', 5, 5, 0, ''),
  ('Tarea Ecología Introducción - Luis', 'Realizar lectura y resumen sobre ecología básica', 5, 8, 0, '');

-- Tareas para la Actividad 6 (id_Actividades = 6, dictada por docente 5)
INSERT INTO `tarea` (`nombre`, `descripcion`, `id_Actividad`, `id_Estudiante`, `Estatus`, `Anotaciones`) VALUES
  ('Tarea Calaverita - Jose Martin', 'Crear una calaverita original para los maestros', 6, 3, 0, ''),
  ('Tarea Calaverita - Jose', 'Crear una calaverita original para los maestros', 6, 6, 0, ''),
  ('Tarea Calaverita - Pedro', 'Crear una calaverita original para los maestros', 6, 9, 0, '');
