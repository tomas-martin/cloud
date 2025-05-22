-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1:3306
-- Tiempo de generación: 30-08-2023 a las 02:24:10
-- Versión del servidor: 8.0.31
-- Versión de PHP: 8.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `sistemaveterinario`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tb_productos`
--

DROP TABLE IF EXISTS `tb_productos`;
CREATE TABLE IF NOT EXISTS `tb_productos` (
  `id_producto` int NOT NULL AUTO_INCREMENT,
  `codigo` varchar(50) COLLATE utf8mb4_spanish_ci NOT NULL,
  `nombre_producto` varchar(255) COLLATE utf8mb4_spanish_ci NOT NULL,
  `descripcion` text COLLATE utf8mb4_spanish_ci NOT NULL,
  `imagen` text COLLATE utf8mb4_spanish_ci NOT NULL,
  `stock` int NOT NULL,
  `stock_minimo` int NOT NULL,
  `stock_maximo` int NOT NULL,
  `precio_compra` int NOT NULL,
  `precio_venta` int NOT NULL,
  `fecha_de_ingreso` date NOT NULL,
  `fyh_creacion` datetime NOT NULL,
  `fyh_actualizacion` datetime NOT NULL,
  `id_usuario` int NOT NULL,
  PRIMARY KEY (`id_producto`),
  KEY `id_usuario` (`id_usuario`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

--
-- Volcado de datos para la tabla `tb_productos`
--

INSERT INTO `tb_productos` (`id_producto`, `codigo`, `nombre_producto`, `descripcion`, `imagen`, `stock`, `stock_minimo`, `stock_maximo`, `precio_compra`, `precio_venta`, `fecha_de_ingreso`, `fyh_creacion`, `fyh_actualizacion`, `id_usuario`) VALUES
(13, 'P-00004', 'CROQUETAS', 'CROQUETAS PARA PERRO ADULTO MAYO A 10 AÑOS', '2023-08-21-08-39-02logo.png', 0, 133, 200, 45, 56, '2023-08-21', '2023-08-21 08:39:02', '0000-00-00 00:00:00', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tb_usuarios`
--

DROP TABLE IF EXISTS `tb_usuarios`;
CREATE TABLE IF NOT EXISTS `tb_usuarios` (
  `id_usuario` int NOT NULL AUTO_INCREMENT,
  `nombre_completo` varchar(255) COLLATE utf8mb4_spanish_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_spanish_ci NOT NULL,
  `password` text COLLATE utf8mb4_spanish_ci NOT NULL,
  `token` varchar(11) COLLATE utf8mb4_spanish_ci DEFAULT NULL,
  `cargo` varchar(50) COLLATE utf8mb4_spanish_ci NOT NULL,
  `fyh_creacion` datetime NOT NULL,
  `fyh_actualizacion` datetime DEFAULT NULL,
  PRIMARY KEY (`id_usuario`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

--
-- Volcado de datos para la tabla `tb_usuarios`
--

INSERT INTO `tb_usuarios` (`id_usuario`, `nombre_completo`, `email`, `password`, `token`, `cargo`, `fyh_creacion`, `fyh_actualizacion`) VALUES
(1, 'Freddy Eddy Hilari Michua', 'hilariweb@gmail.com', '$2y$10$lisJvmSMViQgstO8Ja3S5ORyupjd.lvIX9Hn64/aoNqB7f05ed9f6', NULL, 'CLIENTE', '2023-07-04 01:07:23', '2023-08-01 23:27:34'),
(2, 'Juan Mendoza', 'prueba@gmail.com', '$2y$10$dLUyAyZYUuGqA1hmoojVzOYNRh4DonHSQpkJzG7GP1VXOHwv2qzK6', NULL, 'ADMINISTRADOR', '2023-07-06 13:51:19', NULL),
(5, 'JOSE HILARI', 'jhilari@gmail.com', '$2y$10$tQuyXkR15j74rlGp2sNHvO.dkp.TRrCd.jirn8NTUeW7XQSRMycLC', NULL, 'CLIENTE', '2023-07-12 00:00:00', NULL),
(11, 'freddy 5', 'hilariweb2023@gmail.com', '$2y$10$9/9dAYW.l5J7km8Ga7S/pu1Tu9KzT1fKvuzd9hgEsXvEH0pzCebfm', NULL, 'CLIENTE', '2023-07-18 20:25:36', NULL),
(12, 'freddy 5', 'freddy.hilari.michu32323a@gmail.com', '$2y$10$DxqbdjhjpdKJsLRhj1esGuxECSkTU4ffCfKyeQnUpd/xQnRPnkvPS', NULL, 'CLIENTE', '2023-07-18 21:36:14', NULL),
(13, 'freddy 5', 'freddy.hilari.michu3232332323a@gmail.com', '$2y$10$DxqbdjhjpdKJsLRhj1esGuxECSkTU4ffCfKyeQnUpd/xQnRPnkvPS', NULL, 'CLIENTE', '2023-07-18 21:36:14', NULL),
(14, 'freddy 5', 'freddy.hila@gmail.com', '$2y$10$DxqbdjhjpdKJsLRhj1esGuxECSkTU4ffCfKyeQnUpd/xQnRPnkvPS', NULL, 'CLIENTE', '2023-07-18 21:36:14', NULL);

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `tb_productos`
--
ALTER TABLE `tb_productos`
  ADD CONSTRAINT `tb_productos_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `tb_usuarios` (`id_usuario`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
