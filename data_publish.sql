-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 16-09-2021 a las 19:49:20
-- Versión del servidor: 10.4.21-MariaDB
-- Versión de PHP: 7.3.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `data_publish`
--

DELIMITER $$
--
-- Procedimientos
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_delete_publicacion` (IN `CknIdPublicacion` INT, IN `CknIdUsuario` INT)  BEGIN
           /*******************************************************************************
          * Nombre..........:sp_update_publicacion
          * Propósito.......:Stored que actualiza la informacion de la publicacion
          * Autor...........:Jose Francisco Requena
          * Empresa.........:Geniat
          * F. Creación.....:16/09/2021
          * Ejemplo:
          *    CALL sp_update_publicacion`( );
          *    
          ********************************************************************************/
 
          SET @sMensaje ='';
          SET @nCodigo = 1;
          SET @nIdPublicacion = 0;
          SET @nIdRegistro = 0;
          SET @next_step = 0; 
 
          SELECT nIdPublicacion INTO @nIdPublicacion
          FROM dat_publicacion
          WHERE nIdPublicacion = CknIdPublicacion AND nIdUsuario = CknIdUsuario;
               
          IF @nIdPublicacion > 0 THEN
               SET @next_step = 1;
          ELSE
          		SET @sMensaje = 'No se encontro información relacionada a la publicación';
          END IF;
 
          IF @next_step = 1 THEN
              DELETE FROM dat_publicacion 
				   	WHERE nIdPublicacion = CknIdPublicacion 
								AND nIdUsuario = CknIdUsuario; 
               
               SET @nIdRegistro = ROW_COUNT();
          
               IF @nIdRegistro > 0 THEN
                    SET @nCodigo = 0;
                    SET @sMensaje = 'Publiación eliminada exitosamente';
               ELSE
                    SET @sMensaje = 'No se pudo eliminar la publicación del usuario, vuelva a intentarlo';
               END IF;
 
          END IF;
          
          SELECT @nCodigo AS nCodigo, @sMensaje AS sMensaje, @nIdPublicacion AS nIdPublicacion;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_insert_publicacion` (IN `CknIdUsuario` INT, IN `CksTitulo` VARCHAR(50), IN `CksDescripcion` TINYTEXT)  BEGIN
           /*******************************************************************************
          * Nombre..........:sp_insert_publicacion
          * Propósito.......:Crea un registro de publicacion de un usuario
          * Autor...........:Jose Francisco Requena
          * Empresa.........:Geniat
          * F. Creación.....:16/09/2021
          * Ejemplo:
          *    CALL sp_insert_publicacion`( );
          *    
          ********************************************************************************/
 
          SET @sMensaje ='';
          SET @nCodigo = 1;
          SET @nIdUsuario = 0;
          SET @nIdRegistro = 0;
          SET @next_step = 0; 
 
          SELECT nIdUsuario INTO @nIdUsuario
          FROM dat_usuario
          WHERE nIdUsuario = CknIdUsuario;
               
          IF @nIdUsuario > 0 THEN
               SET @next_step = 1;
          ELSE
          		SET @sMensaje = 'No se encontro información relacionada al usuario';
          END IF;
 
          IF @next_step = 1 THEN
               INSERT INTO dat_publicacion(`nIdUsuario`, `sTitulo`, `sDescripcion`, dFecRegistro)
               VALUES (CknIdUsuario, CksTitulo, CksDescripcion, NOW());
               
               SET @nIdRegistro = LAST_INSERT_ID();
          
               IF @nIdRegistro > 0 THEN
                    SET @nCodigo = 0;
                    SET @sMensaje = 'Publicación registrada exitosamente';
               ELSE
                    SET @sMensaje = 'No se pudo registrar la publicación del usuario, vuelva a intentarlo';
               END IF;
 
          END IF;
          
          SELECT @nCodigo AS nCodigo, @sMensaje AS sMensaje, @nIdRegistro AS nIdPublicacion;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_insert_usuario` (IN `CksNombre` VARCHAR(30), IN `CksApellidoPaterno` VARCHAR(30), IN `CksApellidoMaterno` VARCHAR(30), IN `CksCorreo` VARCHAR(30), IN `CksPassword` VARCHAR(30), IN `CksRol` VARCHAR(30))  BEGIN
           /*******************************************************************************
          * Nombre..........:sp_insert_usuario
          * Propósito.......:Crea un registro de usuario
          * Autor...........: Jose Francisco Requena Aguayo 
          * Empresa.........:Geniat
          * F. Creación.....:15/09/2021
          * Ejemplo:
          *    CALL sp_insert_usuario`( );
          *    
          ********************************************************************************/
 
          SET @sMensaje ='';
          SET @nCodigo = 1;
          SET @nIdUsuario = 0;
          SET @nIdRol = 0;
          SET @nIdRegistro = 0;
          SET @next_step = 0; 
 
          SELECT nIdUsuario INTO @nIdUsuario
          FROM dat_usuario
          WHERE sCorreo = CksCorreo;
               
          IF @nIdUsuario > 0 THEN
               SET @sMensaje = 'El correo ya se encuentra registrado';
          ELSE
               SET @next_step = 1;
          END IF;
          
          IF @next_step = 1 THEN
		          SELECT nIdRol INTO @nIdRol
		          FROM cat_roles
		          WHERE nIdRol = CksRol;
		               
		          IF @nIdRol > 0 THEN
		               SET @next_step = 2;
		          ELSE
		               SET @sMensaje = 'No se encontro información del Rol seleccionado';
		          END IF;
          END IF;
 
          IF @next_step = 2 THEN
               INSERT INTO dat_usuario(`sNombre`, `sApellidoPaterno`, `sApellidoMaterno`, `sCorreo`, `sPassword`, `nIdRol`, dFecRegistro)
               VALUES (CksNombre, CksApellidoPaterno, CksApellidoMaterno, CksCorreo , CksPassword, CksRol, NOW());
               
               SET @nIdRegistro = LAST_INSERT_ID();
          
               IF @nIdRegistro > 0 THEN
                    SET @nCodigo = 0;
                    SET @sMensaje = 'Usuario registrado exitosamente';
               ELSE
                    SET @sMensaje = 'No se pudo registrar el usuario, vuelva a intentarlo';
               END IF;
 
          END IF;
          
          SELECT @nCodigo AS nCodigo, @sMensaje AS sMensaje;
 
     END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_select_login` (IN `CksCorreo` CHAR(30), IN `CksPassword` TEXT)  BEGIN
/*******************************************************************************
          * Nombre..........:sp_select_login
          * Propósito.......:Validamos si el usuario existe
          * Autor...........:Jose Francisco Requena 
          * Empresa.........:Geniat
          * F. Creación.....:15/09/2021
          * Ejemplo:
          *    CALL sp_select_login`('pedro@gmail.com','pedro123');
          *    
          ********************************************************************************/
			SET @sMensaje ='No se encontro información usuario';
          SET @nCodigo = 1;
          SET @nIdUsuario = 0;
          SET @next_step = 0; 
	
	SELECT nIdUsuario,
			 nIdRol,
			 sNombre
			INTO @nIdUsuario,
				  @nIdRol,
				  @sNombre
	FROM data_publish.dat_usuario 
	WHERE sCorreo= CksCorreo AND sPassword=CksPassword;
	
	IF(@nIdUsuario > 0)THEN
		SET @nCodigo = 0;
		SET @sMensaje = CONCAT('Bienvenido ', @sNombre);
	END IF;	
	SELECT @nCodigo AS nCodigo, @sMensaje AS sMensaje, @nIdRol AS nIdRol, @nIdUsuario AS nIdUsuario, @sNombre AS sNombre;
	
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_select_publicacion` ()  BEGIN
           /*******************************************************************************
          * Nombre..........:sp_select_publicacion
          * Propósito.......:Stored para consultar publicaciones
          * Autor...........:Jose Francisco Requena
          * Empresa.........:Geniat
          * F. Creación.....:16/09/2021
          * Ejemplo:
          *    CALL sp_select_publicacion`( );
          *    
          ********************************************************************************/
					SELECT dp.sTitulo,
							 dp.sDescripcion,
							 dp.dFecRegistro,
							 CONCAT (du.sNombre, ' ', du.sApellidoPaterno, ' ', du.sApellidoMaterno) AS sUsuarioNombreCompleto,
							 cr.sNombre AS sNombreRol
					FROM dat_usuario AS du
					INNER JOIN dat_publicacion AS dp ON dp.nIdUsuario = du.nIdUsuario
					INNER JOIN cat_roles AS cr ON cr.nIdRol = du.nIdRol;

 
          
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_update_publicacion` (IN `CknIdPublicacion` INT, IN `CknIdUsuario` INT, IN `CksTitulo` VARCHAR(50), IN `CksDescripcion` TINYTEXT)  BEGIN
           /*******************************************************************************
          * Nombre..........:sp_update_publicacion
          * Propósito.......:Stored que actualiza la informacion de la publicacion
          * Autor...........:Jose Francisco Requena
          * Empresa.........:Geniat
          * F. Creación.....:16/09/2021
          * Ejemplo:
          *    CALL sp_update_publicacion`( );
          *    
          ********************************************************************************/
 
          SET @sMensaje ='';
          SET @nCodigo = 1;
          SET @nIdPublicacion = 0;
          SET @nIdRegistro = 0;
          SET @next_step = 0; 
 
          SELECT nIdPublicacion INTO @nIdPublicacion
          FROM dat_publicacion
          WHERE nIdPublicacion = CknIdPublicacion AND nIdUsuario = CknIdUsuario;
               
          IF @nIdPublicacion > 0 THEN
               SET @next_step = 1;
          ELSE
          		SET @sMensaje = 'No se encontro información relacionada a la publicación';
          END IF;
 
          IF @next_step = 1 THEN
              UPDATE dat_publicacion 
				  			SET `sDescripcion` = CksDescripcion,
				   			 `sTitulo` = CksTitulo
				   	WHERE nIdPublicacion = CknIdPublicacion AND nIdUsuario = CknIdUsuario; 
               
               SET @nIdRegistro = @nIdPublicacion;
          
               IF @nIdRegistro > 0 THEN
                    SET @nCodigo = 0;
                    SET @sMensaje = 'Publiación actualizada exitosamente';
               ELSE
                    SET @sMensaje = 'No se pudo actualizar la publicación del usuario, vuelva a intentarlo';
               END IF;
 
          END IF;
          
          SELECT @nCodigo AS nCodigo, @sMensaje AS sMensaje, @nIdRegistro AS nIdPublicacion;
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cat_roles`
--

CREATE TABLE `cat_roles` (
  `nIdRol` int(11) NOT NULL,
  `sNombre` varchar(50) NOT NULL,
  `sDescripcion` tinytext NOT NULL,
  `dFecRegistro` datetime NOT NULL,
  `dFecMovimiento` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Tabla donde se identifican los roles';

--
-- Volcado de datos para la tabla `cat_roles`
--

INSERT INTO `cat_roles` (`nIdRol`, `sNombre`, `sDescripcion`, `dFecRegistro`, `dFecMovimiento`) VALUES
(1, 'Rol básico', 'Permiso de acceso', '2021-09-16 11:13:41', '2021-09-16 16:13:36'),
(2, 'Rol medio', 'Permiso de acceso y consulta', '2021-09-16 11:13:42', '2021-09-16 16:13:36'),
(3, 'Rol medio alto', 'Permiso de de acceso y agregar', '2021-09-16 11:13:42', '2021-09-16 16:13:36'),
(4, 'Rol alto medio', 'Permiso de acceso, consulta, agregar y actualizar', '2021-09-16 11:13:47', '2021-09-16 16:13:36'),
(5, 'Rol alto', 'Permiso de acceso, consulta, agregar, actualizar y eliminar', '2021-09-16 11:13:46', '2021-09-16 16:13:36');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `dat_publicacion`
--

CREATE TABLE `dat_publicacion` (
  `nIdPublicacion` int(11) NOT NULL,
  `nIdUsuario` int(11) NOT NULL,
  `sTitulo` varchar(50) NOT NULL,
  `sDescripcion` tinytext NOT NULL,
  `dFecRegistro` datetime NOT NULL,
  `dFecMovimiento` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Tabla donde se guardan las publicaciones echas por el usuario';

--
-- Volcado de datos para la tabla `dat_publicacion`
--

INSERT INTO `dat_publicacion` (`nIdPublicacion`, `nIdUsuario`, `sTitulo`, `sDescripcion`, `dFecRegistro`, `dFecMovimiento`) VALUES
(2, 1, 'Llevar en anillo a la montalla del destino', 'El señor de los anillos', '2021-09-16 11:47:50', '2021-09-16 16:47:50'),
(3, 1, 'El señor de los anillos', 'Llevar en anillo a la montalla del destino', '2021-09-16 11:49:58', '2021-09-16 16:49:58');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `dat_usuario`
--

CREATE TABLE `dat_usuario` (
  `nIdUsuario` int(11) NOT NULL,
  `sNombre` varchar(30) NOT NULL,
  `sApellidoPaterno` varchar(30) NOT NULL,
  `sApellidoMaterno` varchar(30) NOT NULL,
  `sCorreo` varchar(30) NOT NULL,
  `sPassword` text NOT NULL,
  `nIdRol` int(11) NOT NULL,
  `dFecRegistro` datetime NOT NULL,
  `dFecMovimiento` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `dat_usuario`
--

INSERT INTO `dat_usuario` (`nIdUsuario`, `sNombre`, `sApellidoPaterno`, `sApellidoMaterno`, `sCorreo`, `sPassword`, `nIdRol`, `dFecRegistro`, `dFecMovimiento`) VALUES
(1, 'Master', 'Master P', 'Master M', 'master@gmail.com', 'master', 5, '2021-09-16 11:36:41', '2021-09-16 16:36:42');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `cat_roles`
--
ALTER TABLE `cat_roles`
  ADD PRIMARY KEY (`nIdRol`);

--
-- Indices de la tabla `dat_publicacion`
--
ALTER TABLE `dat_publicacion`
  ADD PRIMARY KEY (`nIdPublicacion`),
  ADD KEY `FK1_dat_usuario_nIdUsuario` (`nIdUsuario`);

--
-- Indices de la tabla `dat_usuario`
--
ALTER TABLE `dat_usuario`
  ADD PRIMARY KEY (`nIdUsuario`),
  ADD KEY `FK1_cat_roles_nIdRol` (`nIdRol`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `cat_roles`
--
ALTER TABLE `cat_roles`
  MODIFY `nIdRol` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `dat_publicacion`
--
ALTER TABLE `dat_publicacion`
  MODIFY `nIdPublicacion` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `dat_usuario`
--
ALTER TABLE `dat_usuario`
  MODIFY `nIdUsuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `dat_publicacion`
--
ALTER TABLE `dat_publicacion`
  ADD CONSTRAINT `FK1_dat_usuario_nIdUsuario` FOREIGN KEY (`nIdUsuario`) REFERENCES `dat_usuario` (`nIdUsuario`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Filtros para la tabla `dat_usuario`
--
ALTER TABLE `dat_usuario`
  ADD CONSTRAINT `FK1_cat_roles_nIdRol` FOREIGN KEY (`nIdRol`) REFERENCES `cat_roles` (`nIdRol`) ON DELETE NO ACTION ON UPDATE NO ACTION;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
