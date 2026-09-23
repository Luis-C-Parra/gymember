-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 11-11-2025 a las 03:40:09
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
(1, 7, 1, 'activo', '2025-11-10', '2025-12-10', '2025-11-10 17:02:33'),
(2, 8, 2, 'activo', '2025-11-10', '2025-12-10', '2025-11-10 17:02:33'),
(3, 9, 3, 'activo', '2025-11-10', '2026-11-10', '2025-11-10 17:02:33'),
(4, 10, 1, 'activo', '2025-11-10', '2025-12-10', '2025-11-10 17:02:33'),
(5, 11, 2, 'activo', '2025-11-10', '2026-11-10', '2025-11-10 17:02:33'),
(6, 12, 3, 'activo', '2025-11-10', '2025-12-10', '2025-11-10 17:02:33'),
(7, 13, 1, 'activo', '2025-11-10', '2025-12-10', '2025-11-10 17:02:33'),
(8, 15, 2, 'inactivo', '2025-11-10', '2025-12-10', '2025-11-10 17:02:33'),
(9, 1, 2, 'activo', '2025-11-10', '2025-12-10', '2025-11-10 18:47:31');

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
(1, 7, 1, '25000.00', 'efectivo', '2025-11-10 17:02:33', 'Pago inicial Plan Básico'),
(2, 8, 2, '39000.00', 'tarjeta', '2025-11-10 17:02:33', 'Pago mensual Plan Full'),
(3, 9, 3, '60000.00', 'transferencia', '2025-11-10 17:02:33', 'Pago anual Plan Premium'),
(4, 10, 1, '25000.00', 'efectivo', '2025-11-10 17:02:33', 'Pago Básico mensual'),
(5, 11, 2, '39000.00', 'tarjeta', '2025-11-10 17:02:33', 'Pago Full anual'),
(6, 12, 3, '60000.00', 'transferencia', '2025-11-10 17:02:33', 'Pago Premium mensual'),
(7, 13, 1, '25000.00', 'efectivo', '2025-11-10 17:02:33', 'Pago Básico mensual'),
(8, 15, 2, '39000.00', 'tarjeta', '2025-11-10 17:02:33', 'Pago Full mensual'),
(9, 1, 2, '39000.00', 'transferencia', '2025-11-10 22:47:31', '');

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
(12, 'Suplemento Vitaminico', '11000.00', 'suplementos', 'assets/img/1762800067_suplementos.jpg', 'Pack de suplemento vitamínico para atletas con estilo', 1000, 1, '2025-11-10 18:41:07', '2025-11-10 18:41:07'),
(13, 'Kit Novato', '20000.00', 'accesorios', 'assets/img/1762800669_kit novato.webp', 'con este kit se puede iniciar en el mundo de la musculación', 2000, 1, '2025-11-10 18:51:09', '2025-11-10 18:51:09'),
(14, 'kit principiante', '11000.00', 'accesorios', 'assets/img/1762800759_kit musculacion leve.jpg', 'Con este equipo para principiante, se podrá iniciar en el mundo del entrenamiento para mantener una postura adecuada', 3000, 1, '2025-11-10 18:52:39', '2025-11-10 18:52:39'),
(15, 'guantes de Box', '75590.00', 'accesorios', 'assets/img/1762801194_box.webp', 'Guantes de Boxeo PROYEC Forza\\\\r\\\\n\\\\r\\\\nLos Proyec Forza representan la combinación perfecta entre rendimiento, protección y durabilidad, desarrollados por una de las marcas líderes del mercado argentino en equipamiento deportivo. Diseñados para entrenamientos intensos de boxeo, kickboxing y Muay Thai, ofrecen un equilibrio ideal entre confort y resistencia.\\\\r\\\\n\\\\r\\\\nCaracterísticas técnicas\\\\r\\\\n\\\\r\\\\nMaterial: PU digitalizado de alta resistencia, con acabado profesional y textura antideslizante.\\\\r\\\\n\\\\r\\\\nRelleno: Espuma compactada inyectada de triple densidad, que absorbe los impactos y brinda máxima seguridad en cada golpe.\\\\r\\\\n\\\\r\\\\nCosturas reforzadas: Doble costura en toda la estructura, asegurando una vida útil prolongada incluso con uso intensivo.\\\\r\\\\n\\\\r\\\\nAjuste de precisión: Correa con abrojo de velcro de 8 cm para una sujeción firme y estable en la muñeca.', 100, 1, '2025-11-10 18:59:54', '2025-11-10 19:16:56'),
(16, 'Mega combo Box', '132298.00', 'accesorios', 'assets/img/1762801325_mega box.webp', 'La Mega Combo Box de vcdeportes es la solución perfecta para quienes buscan un entrenamiento completo y efectivo en casa. Este saco de boxeo colgante, con una altura de 1.20 cm y un diámetro de 35 cm, está diseñado para soportar un uso intensivo, brindando la resistencia necesaria para tus sesiones de entrenamiento.', 50, 1, '2025-11-10 19:02:05', '2025-11-10 19:02:05'),
(17, 'True Made Whey Protein 1kg Ena+vaso 2 En 1 Shaker Generation', '59131.00', 'suplementos', 'assets/img/1762802197_suplemento.webp', 'EL NOGAL SUPLEMENTOS\\r\\nLA MAYOR VARIEDAD DE MARCAS Y SUPLEMENTOS DEPORTIVOS AL MEJOR PRECIO DE ZONA OESTE.\\r\\n\\r\\nTe brindamos apoyo, confianza y asesoramiento.\\r\\n\\r\\nTrue Made Whey Protein 1kg Ena+Vaso 2 en 1 Shaker Generation Fit', 50, 1, '2025-11-10 19:16:37', '2025-11-10 19:16:37'),
(18, 'Cinta Residencial Caminadora 3300 Entretenimiento Integrado Bluetooth 1.75 Hp 12 Programas Plegable 14km Inclinacion manual', '693023.00', 'accesorios', 'assets/img/1762802305_cinta de correr.webp', 'Cinta Residencial 3300 – Compacta, rebatible y con conectividad inteligente\\\\r\\\\n\\\\r\\\\nLa Cinta de Correr Residencial 3300 es la opción ideal para quienes buscan entrenar en casa con comodidad y tecnología, sin ocupar demasiado espacio. Su diseño rebatible con cilindro hidráulico facilita el guardado, convirtiéndola en la compañera perfecta para tu rutina diaria.  Integrado Bluetooth 1.75 Hp 12 Programas Plegable 14km Inclinacion manual', 10, 1, '2025-11-10 19:18:25', '2025-11-10 19:25:21'),
(19, 'Kit Mancuernas Y Barra + 30 Kg ', '78950.00', 'accesorios', 'assets/img/1762802481_mancuerdas.webp', '¡Entrená a tu manera con este kit completo de mancuernas + barra conversora!\\\\r\\\\nIdeal para quienes buscan versatilidad, comodidad y calidad en un solo set. Perfecto para entrenamientos de fuerza en casa, en el gimnasio o al aire libre.\\\\r\\\\n\\\\r\\\\n¿Qué incluye el kit?\\\\r\\\\n\\\\r\\\\n2 mancuernas con topes de máxima fijación\\\\r\\\\n\\\\r\\\\n1 barra semimaciza conversora de hierro con grips antideslizantes\\\\r\\\\n\\\\r\\\\n30 kg en discos (4 discos de 2,5 kg + 4 discos de 5 kg)', 10, 1, '2025-11-10 19:21:21', '2025-11-10 19:24:55'),
(20, 'Colchoneta Plegable Libro Gimnasia Fitness 1,30m * 50cm* 3cm', '18018.00', 'accesorios', 'assets/img/1762802576_colchoneta plegable.webp', 'Colchoneta para Gimnasia, ideal también para Yoga, Fitness, Ejercitación, Pilates. Gracias al relleno de espuma de polietileno de alta densidad, resulta muy confortable a la hora de realizar ejercicios, confeccionado con Lona Bagun o Tela Cordura en la parte superior y tela no textil gruesa en la base hacen la combinación perfecta para lucir estéticas y ser durables.\\r\\nManejamos stock en varios colores y en cantidades para cumplir con las necesidades de gimnasios, clubes, profesores independientes y escuelas.', 20, 1, '2025-11-10 19:22:56', '2025-11-10 19:22:56'),
(21, 'Accesorios varios', '20000.00', 'otros', 'assets/img/1762802671_accesorios varios.png', 'completa tu kit con alguno de nuestros accesorios', 30, 1, '2025-11-10 19:24:31', '2025-11-10 19:24:31'),
(22, 'mancuerdas 1kg', '15000.00', 'accesorios', 'assets/img/1762805047_mancuerdas 1kg.webp', 'ejercitación acorde a su fuerza y peso', 30, 1, '2025-11-10 20:04:07', '2025-11-10 20:04:07'),
(23, 'Mancuerdas 2kg', '40000.00', 'accesorios', 'assets/img/1762805076_mancuerdas 2kg.webp', 'ejercitación acorde a su fuerza y peso', 15, 1, '2025-11-10 20:04:36', '2025-11-10 20:04:36'),
(24, 'Mancuerdas 500grs', '18000.00', 'accesorios', 'assets/img/1762805108_mancuerdas 500gr.webp', 'ejercitación acorde a su fuerza y peso', 9, 1, '2025-11-10 20:05:08', '2025-11-10 20:05:08'),
(25, 'Suplemento ditetico', '30000.00', 'suplementos', 'assets/img/1762805255_vitaminas y suplementos.jpg', 'Suplemento diario para ejercitacion', 20, 1, '2025-11-10 20:07:35', '2025-11-10 20:07:35');

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

INSERT INTO `usuarios` (`id`, `usuario`, `contrasena`, `rol`, `nombre`, `apellido`, `email`, `telefono`, `foto`, `creado_en`) VALUES
(1, 'LuisC', '$2y$10$F1sjHGDOmGjE4HapJojYh.CO3tuUs78iZrrEdoYK3ymEohQ28Gvtq', 'admin', 'Luis', 'Cocabia', 'luiscocabia@gmail.com', '1124076812', '6912a0ba6f523_2Gemini_Generated_Image_a3lrmba3lrmba3lr.png', '2025-11-10 17:02:33'),
(2, 'DairaB', '$2y$10$2ewfC05A3KcuF/5XD6rE/eblu.EMRBUYXQUTYk1GVlWnV8PSbSfSy', 'admin', 'Daira', 'Barreto', 'barretodairatamara@gmail.com', '1122334455', NULL, '2025-11-10 17:02:33'),
(3, 'DamianF', '$2y$10$foYJJ5GPaqBfC2Y4M2N7YuzwP.ZCukWvfLSL.HrRV6I6O1e7pr1bG', 'admin', 'Damian', 'Flores', 'luciodamianflores@gmail.com', '1133445566', NULL, '2025-11-10 17:02:33'),
(4, 'FlorV', '$2y$10$sq9U8QbQ7cUpIqBWQMNkb.OCL7HT62iVkWbAz5bPHn5pG/GcCkuIe', 'entrenador', 'Rocío', 'Quintana', 'rocioquintana@gmail.com', '1156677889', NULL, '2025-11-10 17:02:33'),
(5, 'CarlosT', '$2y$10$6eU7/hg5GWVxWoAQU2g/OOY0YmXjCGnrqaIBeaywoIfERwZazNzPq', 'entrenador', 'Carlos', 'Torres', 'carlostorres@gmail.com', '1188997766', NULL, '2025-11-10 17:02:33'),
(6, 'Marisol', '$2y$10$9gtJ6DGVx4HFE76Tihs4ueWYPe43McC381SmCDWPMxquMtwARPi1q', 'entrenador', 'María', 'Santos', 'marinasantos@gmail.com', '1167891234', NULL, '2025-11-10 17:02:33'),
(7, 'ValentinM', '$2y$10$l6YRwD4MjLLuDVCZ2fWIoezM5wTyBy5YevvSaRE9dvyD0Jxc4Faie', 'miembro', 'Valentín', 'Martínez', 'valenmartinez@gmail.com', '1176543210', NULL, '2025-11-10 17:02:33'),
(8, 'LuciaP', '$2y$10$j6Qa7ljUdMlpXGnHWaJ8hO4RMvUPd2p.Ud6nkODolHDoSrsp0oV0G', 'miembro', 'Lucía', 'Pérez', 'luciaperez@gmail.com', '1187654321', NULL, '2025-11-10 17:02:33'),
(9, 'AndresG', '$2y$10$MQrhRULVALR4L33SaQSW6.i1PiF32l4gvSsSU8M6vCs1DCjHyQqDS', 'miembro', 'Andrés', 'Gómez', 'andresgomez@gmail.com', '1145678822', NULL, '2025-11-10 17:02:33'),
(10, 'CamilaR', '$2y$10$g3d7YkESAXZBK0iBjHEj5ONlbFZQFSPkteZmBq6R8mFXjW1LjR/He', 'miembro', 'Camila', 'Rodríguez', 'camilarodriguez@gmail.com', '1199887766', NULL, '2025-11-10 17:02:33'),
(11, 'SantiagoL', '$2y$10$63fccQFFdsSHQuEQSpEFH.zz4u2dRYNWZCIK1Yp1LBVUDFbDel3Ji', 'miembro', 'Santiago', 'López', 'santiagolopez@gmail.com', '1112233445', NULL, '2025-11-10 17:02:33'),
(12, 'KariokyJ', '$2y$10$9cCyummpsvg8R1voi633kuaAl3Mcm7rh7fiQvjMRQYwpesfwIg.CW', 'miembro', 'Karioky', 'Jael', 'kariokyjael@gmail.com', '1145239987', NULL, '2025-11-10 17:02:33'),
(13, 'AlejandroD', '$2y$10$Y84hrR7njWEGfA69hKcqwe/VV5/yLes7PvWsjXzYy9tc7NUOerbZ.', 'miembro', 'Alejandro', 'Díaz', 'alejandrodiaz@gmail.com', '1145698745', NULL, '2025-11-10 17:02:33'),
(14, 'CamiloR', '$2y$10$4y5NyIAIdELNctHSHmYGqurw8JqSu9QSYxSzl6bLyAHT9zjw7JGEK', 'miembro', 'Camilo', 'Rueda', 'camilorueda@gmail.com', '1198456712', NULL, '2025-11-10 17:02:33'),
(15, 'DaianaY', '$2y$10$ax2JoV0AaOjTyLNdkXvNvOBDdtdrGd.VSkrj6h4GfzzaOmMbQMV7W', 'miembro', 'Daiana', 'Yanel', 'daianayanel@gmail.com', '1157859451', NULL, '2025-11-10 17:02:33');

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de la tabla `pagos`
--
ALTER TABLE `pagos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de la tabla `planes`
--
ALTER TABLE `planes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `productos`
--
ALTER TABLE `productos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

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
