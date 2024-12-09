-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 09-12-2024 a las 08:19:41
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `almacen`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `almacenes`
--

CREATE TABLE `almacenes` (
  `id` int(11) NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `punto_reorden` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `almacenes`
--

INSERT INTO `almacenes` (`id`, `nombre`, `punto_reorden`, `created_at`) VALUES
(1, 'Almacen las pitas', 0, '2024-11-29 23:02:43'),
(2, 'Almacen los leones', 0, '2024-11-29 23:16:48'),
(4, 'Almacen prueba 2', 0, '2024-12-09 07:09:21');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `movimientos`
--

CREATE TABLE `movimientos` (
  `id` int(11) NOT NULL,
  `tipo` enum('venta','compra','transferencia','desecho') NOT NULL,
  `fecha` datetime DEFAULT current_timestamp(),
  `cantidad` int(11) NOT NULL,
  `producto_id` int(11) NOT NULL,
  `ubicacion_origen_id` int(11) DEFAULT NULL,
  `ubicacion_destino_id` int(11) DEFAULT NULL,
  `proveedor_id` int(11) DEFAULT NULL,
  `descripcion` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `movimientos`
--

INSERT INTO `movimientos` (`id`, `tipo`, `fecha`, `cantidad`, `producto_id`, `ubicacion_origen_id`, `ubicacion_destino_id`, `proveedor_id`, `descripcion`) VALUES
(2, 'compra', '2024-12-06 18:43:10', 25, 2, NULL, 4, 2, ''),
(3, 'venta', '2024-12-06 19:50:05', 5, 2, 4, NULL, NULL, ''),
(4, 'compra', '2024-12-06 20:20:01', 5, 2, NULL, 4, 2, ''),
(5, 'venta', '2024-12-06 20:20:23', 10, 2, 4, NULL, NULL, ''),
(6, 'compra', '2024-12-06 20:20:37', 10, 2, NULL, 4, 2, ''),
(7, 'compra', '2024-12-06 20:26:35', 50, 3, NULL, 2, 2, ''),
(8, 'transferencia', '2024-12-06 20:27:13', 10, 2, 4, 2, NULL, ''),
(9, 'compra', '2024-12-07 13:43:34', 10, 2, NULL, 4, 2, ''),
(10, 'desecho', '2024-12-07 13:48:09', 25, 2, 4, NULL, NULL, 'Producto caducado'),
(11, 'desecho', '2024-12-07 13:55:13', 10, 2, 2, NULL, NULL, 'Sin razón especificada'),
(12, 'desecho', '2024-12-07 14:02:25', 10, 3, 2, NULL, NULL, 'Producto caducado'),
(18, 'compra', '2024-12-07 17:56:21', 150, 2, NULL, 4, 2, ''),
(19, 'venta', '2024-12-07 18:13:29', 150, 2, 4, NULL, NULL, ''),
(20, 'compra', '2024-12-07 18:13:49', 120, 2, NULL, 4, 2, ''),
(21, 'venta', '2024-12-07 18:28:54', 60, 2, 4, NULL, NULL, ''),
(22, 'compra', '2024-12-07 18:29:15', 50, 2, NULL, 4, 2, ''),
(23, 'compra', '2024-12-08 14:14:59', 50, 2, NULL, 2, 2, ''),
(24, 'compra', '2024-12-08 14:16:08', 50, 4, NULL, 3, 3, ''),
(25, 'compra', '2024-12-08 14:16:20', 20, 4, NULL, 4, 3, ''),
(26, 'compra', '2024-12-09 01:10:13', 50, 5, NULL, 7, 4, ''),
(27, 'venta', '2024-12-09 01:10:33', 25, 5, 7, NULL, NULL, ''),
(28, 'transferencia', '2024-12-09 01:10:55', 25, 5, 7, 4, NULL, ''),
(29, 'desecho', '2024-12-09 01:11:24', 70, 3, 4, NULL, NULL, 'Producto caducado');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos`
--

CREATE TABLE `productos` (
  `id` int(11) NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `descripcion` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `productos`
--

INSERT INTO `productos` (`id`, `nombre`, `descripcion`) VALUES
(2, 'Chips', NULL),
(3, 'Runners', NULL),
(4, 'Coca-Cola 500ml', NULL),
(5, 'Pepsi 500ml', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `proveedores`
--

CREATE TABLE `proveedores` (
  `id` int(11) NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `contacto` varchar(255) DEFAULT NULL,
  `telefono` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `proveedores`
--

INSERT INTO `proveedores` (`id`, `nombre`, `contacto`, `telefono`) VALUES
(2, 'Barcel', NULL, NULL),
(3, 'Coca-Cola', NULL, NULL),
(4, 'Pepsi', NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ubicaciones`
--

CREATE TABLE `ubicaciones` (
  `id` int(11) NOT NULL,
  `almacen_id` int(11) NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `capacidad_min` int(11) NOT NULL,
  `capacidad_max` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `stock_actual` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `ubicaciones`
--

INSERT INTO `ubicaciones` (`id`, `almacen_id`, `nombre`, `capacidad_min`, `capacidad_max`, `created_at`, `stock_actual`) VALUES
(1, 1, 'Guerrero', 1, 200, '2024-11-29 23:02:43', 0),
(2, 2, 'Morelos', 1, 200, '2024-11-29 23:16:48', 90),
(3, 2, 'Tamaulipas', 50, 300, '2024-11-29 23:16:48', 50),
(4, 2, 'Hidalgo', 25, 100, '2024-11-29 23:16:48', 85),
(6, 4, 'prueba 2', 1, 200, '2024-12-09 07:09:21', 0),
(7, 4, 'prueba 3', 0, 500, '2024-12-09 07:09:47', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `firstname` varchar(50) NOT NULL,
  `lastname` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(20) DEFAULT 'user',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `username`, `firstname`, `lastname`, `password`, `role`, `created_at`) VALUES
(1, 'Kogarashi', 'Luis Alejandro', 'Medina Cavazos', '$2y$10$Esl2ojsGRNdaIxIqKBkQ3Ojqcl29o8zIrja3ykiXjm1EdyKfe9Jz2', 'admin', '2024-11-28 15:21:25'),
(2, 'Kiara', 'Kiara', 'Ku Rojas', '$2y$10$E7t0FDdwmzmuNnrskv3GOOAA3uuw0Wdc5HKhoZh5KJnrbYGz4t2nC', 'user', '2024-11-28 15:26:40'),
(3, 'Nuevo', 'Antonio Salomon', 'Martinez Ibarra', '$2y$10$VFLDGCwhI3.5S2qN23K7Fu6LGYy3R.Ayid/a1rO7cE3/7gosO8fWK', 'user', '2024-12-09 07:13:25');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `almacenes`
--
ALTER TABLE `almacenes`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `movimientos`
--
ALTER TABLE `movimientos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `producto_id` (`producto_id`),
  ADD KEY `ubicacion_origen_id` (`ubicacion_origen_id`),
  ADD KEY `ubicacion_destino_id` (`ubicacion_destino_id`),
  ADD KEY `proveedor_id` (`proveedor_id`);

--
-- Indices de la tabla `productos`
--
ALTER TABLE `productos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `proveedores`
--
ALTER TABLE `proveedores`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `ubicaciones`
--
ALTER TABLE `ubicaciones`
  ADD PRIMARY KEY (`id`),
  ADD KEY `almacen_id` (`almacen_id`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `almacenes`
--
ALTER TABLE `almacenes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `movimientos`
--
ALTER TABLE `movimientos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT de la tabla `productos`
--
ALTER TABLE `productos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `proveedores`
--
ALTER TABLE `proveedores`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `ubicaciones`
--
ALTER TABLE `ubicaciones`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `movimientos`
--
ALTER TABLE `movimientos`
  ADD CONSTRAINT `movimientos_ibfk_1` FOREIGN KEY (`producto_id`) REFERENCES `productos` (`id`),
  ADD CONSTRAINT `movimientos_ibfk_2` FOREIGN KEY (`ubicacion_origen_id`) REFERENCES `ubicaciones` (`id`),
  ADD CONSTRAINT `movimientos_ibfk_3` FOREIGN KEY (`ubicacion_destino_id`) REFERENCES `ubicaciones` (`id`),
  ADD CONSTRAINT `movimientos_ibfk_4` FOREIGN KEY (`proveedor_id`) REFERENCES `proveedores` (`id`);

--
-- Filtros para la tabla `ubicaciones`
--
ALTER TABLE `ubicaciones`
  ADD CONSTRAINT `ubicaciones_ibfk_1` FOREIGN KEY (`almacen_id`) REFERENCES `almacenes` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
