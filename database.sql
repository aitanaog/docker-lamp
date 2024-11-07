-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Servidor: db
-- Tiempo de generación: 16-09-2020 a las 16:37:17
-- Versión del servidor: 10.5.5-MariaDB-1:10.5.5+maria~focal
-- Versión de PHP: 7.4.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

-- Base de datos: `database`
-- --------------------------------------------------------

-- Estructura de tabla para la tabla `usuarios`
CREATE TABLE usuarios (
    id INT NOT NULL AUTO_INCREMENT,  
    nombre VARCHAR(50) NOT NULL,
    apellidos VARCHAR(100) NOT NULL,
    dni VARCHAR(20) NOT NULL UNIQUE,
    telefono VARCHAR(15),
    fecha_nacimiento DATE,
    email VARCHAR(100) NOT NULL,
    contrasenna VARCHAR(255) NOT NULL,
    PRIMARY KEY (id) 
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Inserción de datos para la tabla `usuarios`
INSERT INTO usuarios (nombre, apellidos, dni, telefono, fecha_nacimiento, email, contrasenna) VALUES
('Napoleon', 'Bonaparte', '12345678-A', '600123456', '1985-10-15', 'napoleon.bonaparte@example.com', 'gabacho'),
('Elon', 'Musk', '12345678-B', '600987654', '1971-06-28', 'elon.musk@example.com', 'tesla'),
('Taylor', 'Swift', '12345678-C', '600345678', '1989-12-13', 'taylor.swift@example.com', 'swiftie'),
('Brad', 'Pitt', '12345678-D', '600456789', '1963-12-18', 'brad.pitt@example.com', 'angelina'),
('Ariana', 'Grande', '12345678-E', '600567890', '1993-06-26', 'ariana.grande@example.com', 'handia'),
('Dwayne', 'Johnson', '12345678-F', '600678901', '1972-05-02', 'dwayne.johnson@example.com', 'rock');


-- Estructura de tabla para la tabla `canciones`
CREATE TABLE  canciones (
    id INT NOT NULL AUTO_INCREMENT,
    nombre_cancion VARCHAR(100) NOT NULL,
    cantante VARCHAR(100) NOT NULL,
    album VARCHAR(100) NOT NULL,
    genero_musical VARCHAR(50) NOT NULL,
    fecha_lanzamiento DATE NOT NULL,
    PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Inserción de datos para la tabla `canciones`
-- Inserción de datos para la tabla `canciones`
INSERT INTO canciones (nombre_cancion, cantante, album, genero_musical, fecha_lanzamiento) VALUES
('Thriller', 'Michael Jackson', 'Thriller', 'Pop', '1982-11-30'),
('Bohemian Rhapsody', 'Queen', 'A Night at the Opera', 'Rock', '1975-11-21'),
('Imagine', 'John Lennon', 'Imagine', 'Pop', '1971-10-11'),
('Hotel California', 'Eagles', 'Hotel California', 'Rock', '1976-12-08'),
('Billie Jean', 'Michael Jackson', 'Thriller', 'Pop', '1982-01-02'),
('Shape of You', 'Ed Sheeran', 'Divide', 'Pop', '2017-01-06'),
('Smells Like Teen Spirit', 'Nirvana', 'Nevermind', 'Grunge', '1991-09-24'),
('Rolling in the Deep', 'Adele', '21', 'Soul', '2010-11-29'),
('Sweet Child O\' Mine', 'Guns N\' Roses', 'Appetite for Destruction', 'Rock', '1987-08-17'),
('Despacito', 'Luis Fonsi', 'Vida', 'Reggaeton', '2017-01-13'),
('Uptown Funk', 'Mark Ronson ft. Bruno Mars', 'Uptown Special', 'Funk', '2014-11-10');


CREATE TABLE login_fallidos (
	id_usuario INT NOT NULL,
	fecha DATE NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

	


-- Confirmar los cambios
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

