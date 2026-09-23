-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 05-11-2025 a las 21:00:53
-- Versión del servidor: 10.1.38-MariaDB
-- Versión de PHP: 7.3.2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `gymember_db`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `miembros`
--

CREATE TABLE `miembros` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `plan_id` int(11) DEFAULT NULL,
  `estado` enum('inactivo','activo','suspendido') DEFAULT 'inactivo',
  `fecha_inicio` date DEFAULT NULL,
  `fecha_vencimiento` date DEFAULT NULL,
  `creado_en` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `miembros`
--

INSERT INTO `miembros` (`id`, `usuario_id`, `plan_id`, `estado`, `fecha_inicio`, `fecha_vencimiento`, `creado_en`) VALUES
(1, 3, 1, 'activo', '2025-11-05', '2025-12-05', '2025-11-05 16:27:21'),
(3, 4, NULL, 'inactivo', NULL, NULL, '2025-11-05 16:35:12');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pagos`
--

CREATE TABLE `pagos` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `plan_id` int(11) NOT NULL,
  `monto` decimal(10,2) NOT NULL,
  `metodo` enum('efectivo','tarjeta','transferencia') NOT NULL DEFAULT 'efectivo',
  `fecha` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `nota` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `pagos`
--

INSERT INTO `pagos` (`id`, `usuario_id`, `plan_id`, `monto`, `metodo`, `fecha`, `nota`) VALUES
(4, 2, 1, '25000.00', 'transferencia', '2025-11-05 14:10:07', 'Pago por transferencia en el local.'),
(5, 2, 2, '39000.00', 'transferencia', '2025-11-05 14:12:32', '');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `planes`
--

CREATE TABLE `planes` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `precio` decimal(10,2) NOT NULL,
  `periodo` enum('mensual','anual') NOT NULL DEFAULT 'mensual',
  `activo` tinyint(1) NOT NULL DEFAULT '1',
  `item1_icono` enum('check','x') DEFAULT 'check',
  `item1_texto` varchar(255) DEFAULT NULL,
  `item2_icono` enum('check','x') DEFAULT 'check',
  `item2_texto` varchar(255) DEFAULT NULL,
  `item3_icono` enum('check','x') DEFAULT 'check',
  `item3_texto` varchar(255) DEFAULT NULL,
  `item4_icono` enum('check','x') DEFAULT 'check',
  `item4_texto` varchar(255) DEFAULT NULL,
  `item5_icono` enum('check','x') DEFAULT 'check',
  `item5_texto` varchar(255) DEFAULT NULL,
  `item6_icono` enum('check','x') DEFAULT 'check',
  `item6_texto` varchar(255) DEFAULT NULL,
  `creado_en` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `planes`
--

INSERT INTO `planes` (`id`, `nombre`, `precio`, `periodo`, `activo`, `item1_icono`, `item1_texto`, `item2_icono`, `item2_texto`, `item3_icono`, `item3_texto`, `item4_icono`, `item4_texto`, `item5_icono`, `item5_texto`, `item6_icono`, `item6_texto`, `creado_en`) VALUES
(1, 'Plan Básico', '25000.00', 'mensual', 1, 'check', 'Acceso ilimitado al gimnasio', 'check', '2 consultas con entrenador fitness', 'check', 'Seguimiento nutricional', 'x', '1 suplemento gratuito', 'x', '3 días por semana', 'x', 'Entrenador personal', '2025-10-28 20:53:25'),
(2, 'Plan Full', '39000.00', 'mensual', 1, 'check', 'Acceso ilimitado al gimnasio', 'check', '4 consultas con entrenador fitness', 'check', 'Seguimiento nutricional', 'check', '3 suplementos gratuitos', 'check', '5 días por semana', 'x', 'Entrenador personal', '2025-10-28 20:53:25'),
(3, 'Plan Premium', '60000.00', 'mensual', 1, 'check', 'Acceso ilimitado al gimnasio', 'check', '7 consultas con entrenador fitness', 'check', 'Seguimiento nutricional', 'check', '5 suplementos gratuitos', 'check', 'Tarjeta de acceso al gimnasio', 'check', 'Entrenador personal', '2025-10-28 20:53:25'),
(4, 'Paquete Atleta', '105000.00', 'mensual', 1, 'check', 'Acceso ilimitado al gimnasio', 'check', 'Indumentaria gratuita', 'check', 'Todos los programas de entrenamiento incluidos', 'check', 'Asesoría fitness gratuita', 'check', 'Suplemento gratuito', 'check', 'Tarjeta de acceso al gimnasio', '2025-10-28 20:53:25'),
(5, 'Plan Básico', '240000.00', 'anual', 1, 'check', 'Acceso ilimitado al gimnasio', 'check', '2 consultas con entrenador fitness', 'check', 'Seguimiento nutricional', 'x', '1 suplemento gratuito', 'x', '3 días por semana', 'x', 'Entrenador personal', '2025-10-28 20:53:25'),
(6, 'Plan Full', '375000.00', 'anual', 1, 'check', 'Acceso ilimitado al gimnasio', 'check', '4 consultas con entrenador fitness', 'check', 'Seguimiento nutricional', 'check', '3 suplementos gratuitos', 'check', '5 días por semana', 'x', 'Entrenador personal', '2025-10-28 20:53:25'),
(7, 'Plan Premium', '576000.00', 'anual', 1, 'check', 'Acceso ilimitado al gimnasio', 'check', '7 consultas con entrenador fitness', 'check', 'Seguimiento nutricional', 'check', '5 suplementos gratuitos', 'check', 'Tarjeta de acceso al gimnasio', 'check', 'Entrenador personal', '2025-10-28 20:53:25'),
(8, 'Paquete Atleta', '1000000.00', 'anual', 1, 'check', 'Acceso ilimitado al gimnasio', 'check', 'Indumentaria gratuita', 'check', 'Todos los programas de entrenamiento incluidos', 'check', 'Asesoría fitness gratuita', 'check', 'Suplemento gratuito', 'check', 'Tarjeta de acceso al gimnasio', '2025-10-28 20:53:25');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos`
--

CREATE TABLE `productos` (
  `id` int(11) NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `precio` decimal(10,2) NOT NULL,
  `categoria` enum('ropa','suplementos','accesorios','otros') NOT NULL,
  `img` varchar(255) DEFAULT NULL,
  `descripcion` text,
  `stock` int(11) NOT NULL DEFAULT '0',
  `activo` tinyint(1) NOT NULL DEFAULT '1',
  `fecha_creacion` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `fecha_actualizacion` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `productos`
--

INSERT INTO `productos` (`id`, `nombre`, `precio`, `categoria`, `img`, `descripcion`, `stock`, `activo`, `fecha_creacion`, `fecha_actualizacion`) VALUES
(1, 'Leche', '1000.00', 'suplementos', 'assets/img/1761243543_2017-05-30 17.19.14.jpg', 'suplemento fortificador de musculos', 40, 1, '2025-10-23 17:19:12', '2025-10-23 18:19:03'),
(4, 'Agua Mineral', '1500.00', 'suplementos', 'assets/img/1761245652_20211125_225903.jpg', 'Agua mineral para clientes del gimnacio', 10000, 1, '2025-10-23 18:54:12', '2025-10-23 18:54:39'),
(5, 'Pesas individuales para cada mano', '9000.00', 'accesorios', 'assets/img/1761259741_2015-05-10 23.23.53.jpg', 'pesas individuales para manos', 50, 1, '2025-10-23 22:49:01', '2025-10-26 13:16:38'),
(6, 'Mister Damian', '300.00', 'ropa', 'assets/img/1761928824_2017-10-19 13.01.12.jpg', 'hola damian', 500, 1, '2025-10-31 16:31:32', '2025-10-31 16:40:24'),
(7, 'yo es la liosta', '100.00', 'accesorios', 'assets/img/1761928327_2017-05-26 21.41.28.jpg', 'enanitos', 1500, 1, '2025-10-31 16:32:07', '2025-10-31 16:32:07'),
(8, 'alumnos', '300.00', 'otros', 'assets/img/1761928765_2016-12-11 01.29.17.jpg', 'aklumnos pasando clases', 400, 1, '2025-10-31 16:39:25', '2025-10-31 16:39:25'),
(9, 'ifts4', '10.00', 'otros', 'assets/img/1761930113_2016-02-29 03.28.14.jpg', 'estudiantes en la tecnijcatura', 1000, 1, '2025-10-31 17:01:53', '2025-10-31 17:01:53'),
(10, 'la casa', '50.00', 'accesorios', 'assets/img/1761930268_18119595_1431224936949060_5020425141435804975_n.jpg', 'hola', 500, 1, '2025-10-31 17:04:28', '2025-10-31 17:04:28');
-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `usuario` varchar(100) NOT NULL,
  `contrasena` varchar(255) NOT NULL,
  `rol` enum('admin','entrenador','miembro') NOT NULL DEFAULT 'miembro',
  `nombre` varchar(150) DEFAULT NULL,
  `apellido` varchar(100) DEFAULT NULL,
  `email` varchar(150) DEFAULT NULL,
  `telefono` varchar(50) DEFAULT NULL,
  `foto` varchar(255) DEFAULT NULL,
  `creado_en` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `usuario`, `contrasena`, `rol`, `nombre`, `email`, `creado_en`) VALUES
(1, 'LuisC', '123456', 'admin', 'Luis C Parra', 'luiscocabia@hotmail.com', '2025-10-22 14:40:15'),
(2, 'Daira', '12345', 'entrenador', 'Daira Barreto', 'dairabarreto@gmail.com', '2025-10-22 15:01:19'),
(3, 'Damian', '123', 'recepcion', 'Damian Flores', 'holadamkian@hotmail.com', '2025-10-23 16:52:33'),
(4, 'Flores', '12', 'entrenador', 'Flores Danilo', 'danilo@gmail.com', '2025-11-04 16:17:59');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `miembros`
--
ALTER TABLE `miembros`
  ADD PRIMARY KEY (`id`),
  ADD KEY `usuario_id` (`usuario_id`),
  ADD KEY `plan_id` (`plan_id`);

--
-- Indices de la tabla `pagos`
--
ALTER TABLE `pagos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `plan_id` (`plan_id`),
  ADD KEY `fk_pagos_usuarios` (`usuario_id`);

--
-- Indices de la tabla `planes`
--
ALTER TABLE `planes`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `productos`
--
ALTER TABLE `productos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `usuario` (`usuario`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `miembros`
--
ALTER TABLE `miembros`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `pagos`
--
ALTER TABLE `pagos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `planes`
--
ALTER TABLE `planes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `productos`
--
ALTER TABLE `productos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `miembros`
--
ALTER TABLE `miembros`
  ADD CONSTRAINT `miembros_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `miembros_ibfk_2` FOREIGN KEY (`plan_id`) REFERENCES `planes` (`id`) ON DELETE SET NULL;

--
-- Filtros para la tabla `pagos`
--
ALTER TABLE `pagos`
  ADD CONSTRAINT `fk_pagos_usuario` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_pagos_usuarios` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `pagos_ibfk_2` FOREIGN KEY (`plan_id`) REFERENCES `planes` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
