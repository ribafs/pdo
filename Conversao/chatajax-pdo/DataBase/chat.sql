-- phpMyAdmin SQL Dump
-- version 3.3.9
-- http://www.phpmyadmin.net
--
-- Servidor: localhost
-- Tiempo de generación: 07-02-2018 a las 06:25:46
-- Versión del servidor: 5.5.8
-- Versión de PHP: 5.3.5

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de datos: `chat`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `mensajes`
--

CREATE TABLE IF NOT EXISTS `mensajes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_envia` int(11) NOT NULL,
  `fecha` datetime NOT NULL,
  `texto` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=23 ;

--
-- Volcar la base de datos para la tabla `mensajes`
--

INSERT INTO `mensajes` (`id`, `id_envia`, `fecha`, `texto`) VALUES
(17, 1, '2018-02-07 01:17:58', 'hola <img src=''emoticones/risa.jpg'' style=''width:20px;height:20px;''/>'),
(18, 1, '2018-02-07 01:18:15', 'hola como estas <img src=''emoticones/risa.jpg'' style=''width:20px;height:20px;''/>'),
(19, 1, '2018-02-07 01:18:19', 'jajaja <img src=''emoticones/sonrisa.png'' style=''width:20px;height:20px;''/>'),
(20, 1, '2018-02-07 01:18:24', 'plop <img src=''emoticones/plop.jpg'' style=''width:20px;height:20px;''/>'),
(21, 2, '2018-02-07 01:18:47', 'hola anyelber :'),
(22, 2, '2018-02-07 01:18:55', 'hola anyelber <img src=''emoticones/risa.jpg'' style=''width:20px;height:20px;''/>');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE IF NOT EXISTS `usuarios` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user` varchar(50) NOT NULL,
  `pass` varchar(50) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Volcar la base de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `user`, `pass`, `nombre`) VALUES
(1, 'anyslehider', '123456', 'Anyelber Boscan'),
(2, 'pedro', 'pedro', 'pedro');
