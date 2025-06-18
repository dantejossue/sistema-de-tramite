-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost:3306
-- Tiempo de generación: 30-05-2025 a las 01:59:08
-- Versión del servidor: 8.4.3
-- Versión de PHP: 8.3.16

--
-- BASE DE DATOS DEL SISTEMA DE TRAMITE DOCUMENTARIO
--
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `sis_tramite`
--
CREATE DATABASE IF NOT EXISTS `sis_tramite` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci;
USE `sis_tramite`;

DELIMITER $$
--
-- Procedimientos
--
DROP PROCEDURE IF EXISTS `SP_CAMBIAR_ESTADO_TRAMITE`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `SP_CAMBIAR_ESTADO_TRAMITE` (IN `ID_TRAMITE` VARCHAR(15), IN `NUEVO_ESTADO` VARCHAR(20), IN `DESCRIPCION` TEXT, IN `ID_USUARIO` INT, IN `AREA_ORIGEN_ID` INT, IN `AREA_DESTINO_ID` INT)   BEGIN
    DECLARE ID_MOVIMIENTO INT;

    -- Actualiza el último movimiento pendiente
    SELECT movimiento_id INTO ID_MOVIMIENTO
    FROM movimiento
    WHERE tramite_id = ID_TRAMITE AND mov_estado = 'PENDIENTE'
    ORDER BY mov_fecharegistro DESC
    LIMIT 1;

    -- Insertar nuevo movimiento con el nuevo estado
    INSERT INTO movimiento (
        tramite_id, area_origen_id, area_destino_id, mov_descripcion,
        mov_estado, usu_id, mov_fecharegistro
    ) VALUES (
        ID_TRAMITE, AREA_ORIGEN_ID, AREA_DESTINO_ID, DESCRIPCION,
        NUEVO_ESTADO, ID_USUARIO, NOW()
    );

    -- Actualizar el estado en la tabla tramite
    UPDATE tramite
    SET tramite_estado = NUEVO_ESTADO
    WHERE tramite_id = ID_TRAMITE;
END$$

DROP PROCEDURE IF EXISTS `SP_CARGAR_SEGUIMIENTO_TRAMITE`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `SP_CARGAR_SEGUIMIENTO_TRAMITE` (IN `NUMERO` VARCHAR(12), IN `DNI` VARCHAR(12))   BEGIN
SELECT tramite_id, remitente_dni, CONCAT(tramite.remitente_nombre,' ',tramite.remitente_apepat,' ',tramite.remitente_apemat), tramite.tramite_fecharegistro 
FROM tramite
WHERE tramite.tramite_id = NUMERO and tramite.remitente_dni = DNI;
END$$

DROP PROCEDURE IF EXISTS `SP_CARGAR_SEGUIMIENTO_TRAMITE_DETALLE`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `SP_CARGAR_SEGUIMIENTO_TRAMITE_DETALLE` (IN `NUMERO` VARCHAR(12))   BEGIN
SELECT 
movimiento.movimiento_id, 
movimiento.tramite_id, 
area.area_nombre, 
movimiento.mov_fecharegistro, 
movimiento.mov_descripcion, 
movimiento.mov_estado 
FROM 
movimiento 
INNER JOIN 
area ON movimiento.area_destino_id = area.area_id
WHERE movimiento.tramite_id = NUMERO;
END$$

DROP PROCEDURE IF EXISTS `SP_CARGAR_SELECT_AREA`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `SP_CARGAR_SELECT_AREA` ()   BEGIN
    SELECT area_id, area_nombre
    FROM area
    WHERE area_estado = 'ACTIVO'
    ORDER BY area_nombre ASC;
END$$

DROP PROCEDURE IF EXISTS `SP_CARGAR_SELECT_PERSONA`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `SP_CARGAR_SELECT_PERSONA` ()   BEGIN
    SELECT persona_id, CONCAT(per_nombre, ' ', per_apepat, ' ', per_apemat) AS persona_nombre
    FROM persona
    WHERE per_estado = 'ACTIVO'
    ORDER BY per_nombre ASC;
END$$

DROP PROCEDURE IF EXISTS `SP_CARGAR_SELECT_TIPO`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `SP_CARGAR_SELECT_TIPO` ()   BEGIN
SELECT tipodocumento_id , tipodo_descripcion
FROM tipo_documento
WHERE tipodo_estado = 'ACTIVO'
ORDER BY tipodo_descripcion ASC;
END$$

DROP PROCEDURE IF EXISTS `SP_LISTAR_AREA`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `SP_LISTAR_AREA` ()   BEGIN
SELECT
area.area_id,
area.area_nombre,
area.area_fecha_registro,
area.area_estado
FROM area;

END$$

DROP PROCEDURE IF EXISTS `SP_LISTAR_PERSONA`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `SP_LISTAR_PERSONA` ()   BEGIN
SELECT
persona.persona_id,                     -- ID del personal
persona.per_foto,                
persona.per_nrodocumento,
persona.per_nombre,       -- Enviamos el nombre separado
persona.per_apepat,       -- Enviamos apellido paterno separado
persona.per_apemat,       -- Enviamos apellido materno separado
CONCAT(persona.per_nombre,' ',persona.per_apepat,' ',persona.per_apemat) AS per,  -- Nombre completo de la persona
persona.per_movil,
persona.per_email,
persona.per_direccion,
persona.per_estado,
persona.per_fechanacimiento

FROM persona;
END$$

DROP PROCEDURE IF EXISTS `SP_LISTAR_TIPO_DOCUMENTO`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `SP_LISTAR_TIPO_DOCUMENTO` ()   BEGIN
SELECT
tipo_documento.tipodocumento_id,
tipo_documento.tipodo_descripcion,
tipo_documento.tipodo_estado,
tipo_documento.tipodo_fregistro
FROM tipo_documento;
END$$

DROP PROCEDURE IF EXISTS `SP_LISTAR_TRAMITES`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `SP_LISTAR_TRAMITES` ()   BEGIN
    SELECT 
        t.tramite_id,
        t.remitente_dni, -- DNI del remitente
        CONCAT(t.remitente_nombre, ' ', t.remitente_apepat, ' ', t.remitente_apemat) AS datos_remitente, -- Nombre completo del remitente
        t.tipodocumento_id,
        td.tipodo_descripcion,
        t.tramite_nrodocumento, -- Número del documento
        a_origen.area_nombre AS area_origen_id, -- Área donde se originó el trámite
        a_destino.area_nombre AS area_destino_id, -- Área donde está actualmente el trámite o a la que fue derivado
        t.tramite_estado, -- Estado del trámite
        t.remitente_nombre,
		t.remitente_apepat,
	    t.remitente_apemat,
		t.remitente_celular,
		t.remitente_email,
		t.remitente_email,
		t.remitente_direccion,
		t.tramite_doc_razon,
		t.tramite_doc_ruc,
		t.tramite_doc_representacion,
		t.tramite_folio,
		t.tramite_asunto,
		t.tramite_fecharegistro,
		t.tramite_archivo,
        t.area_origen,
        t.area_destino
    FROM 
        tramite t
    INNER JOIN 
        tipo_documento td ON t.tipodocumento_id = td.tipodocumento_id -- Relación con tipo_documento
    INNER JOIN 
        area a_origen ON t.area_origen = a_origen.area_id -- Relación con área de origen
    LEFT JOIN 
        area a_destino ON t.area_destino = a_destino.area_id; -- Relación con área actual (puede ser NULL)

END$$

DROP PROCEDURE IF EXISTS `SP_LISTAR_TRAMITE_AREA`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `SP_LISTAR_TRAMITE_AREA` (IN `IDUSUARIO` INT)   BEGIN
DECLARE IDAREA INT;
SELECT area_id INTO IDAREA FROM usuario WHERE usu_id = IDUSUARIO;
SELECT
t.tramite_id,
t.remitente_dni, -- DNI del remitente
CONCAT(t.remitente_nombre, ' ', t.remitente_apepat, ' ', t.remitente_apemat) AS datos_remitente, -- Nombre completo del remitente
t.tipodocumento_id,
td.tipodo_descripcion,
t.tramite_nrodocumento, -- Número del documento
td.tipodo_descripcion, -- Tipo de documento
a_origen.area_nombre AS area_origen_id, -- Área donde se originó el trámite
a_destino.area_nombre AS area_destino_id, -- Área donde está actualmente el trámite o a la que fue derivado
t.tramite_estado,
t.remitente_nombre,
t.remitente_apepat,
t.remitente_apemat,
t.remitente_celular,
t.remitente_email,
t.remitente_email,
t.remitente_direccion,
t.tramite_doc_razon,
t.tramite_doc_ruc,
t.tramite_doc_representacion,
t.tramite_folio,
t.tramite_asunto,
t.tramite_fecharegistro,
t.tramite_archivo,
t.area_origen,
t.area_destino
FROM
tramite t
INNER JOIN
tipo_documento td ON t.tipodocumento_id = td.tipodocumento_id -- Relación con tipo_documento
INNER JOIN
area a_origen ON t.area_origen = a_origen.area_id -- Relación con área de origen
LEFT JOIN
area a_destino ON t.area_destino = a_destino.area_id -- Relación con área actual (puede ser NULL)
WHERE t.area_destino = IDAREA;
END$$

DROP PROCEDURE IF EXISTS `SP_LISTAR_TRAMITE_SEGUIMIENTO`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `SP_LISTAR_TRAMITE_SEGUIMIENTO` (IN `NID` CHAR(12))   BEGIN
SELECT
movimiento.movimiento_id,
movimiento.tramite_id, 
movimiento.area_origen_id,
area.area_nombre,
movimiento.mov_fecharegistro, 
movimiento.mov_descripcion, 
movimiento.mov_estado
FROM
movimiento 
INNER JOIN
area ON movimiento.area_origen_id = area.area_id 
WHERE movimiento.tramite_id = NID;

END$$

DROP PROCEDURE IF EXISTS `SP_LISTAR_USUARIO`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `SP_LISTAR_USUARIO` ()   BEGIN
SELECT
u.usu_id,
u.usu_usuario,
u.usu_contra,
a.area_nombre,
u.usu_rol,
u.usu_observacion,
u.persona_id,
u.area_id,
CONCAT(p.per_nombre,' ',p.per_apepat,' ',p.per_apemat) AS npersona,
u.usu_estado
FROM usuario u
INNER JOIN area a ON u.area_id = a.area_id
INNER JOIN persona p ON u.persona_id = p.persona_id;
END$$

DROP PROCEDURE IF EXISTS `SP_MODIFICAR_AREA`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `SP_MODIFICAR_AREA` (IN `ID` INT, IN `NAREA` VARCHAR(255), IN `ESTADO` VARCHAR(29))   BEGIN
    DECLARE AREAACTUAL VARCHAR(255);
    DECLARE CANTIDAD INT;

    -- Obtener el nombre actual del área
    SELECT area_nombre INTO AREAACTUAL FROM area WHERE area_id = ID;

    -- Verificar si el nombre actual es diferente al nuevo nombre
    IF AREAACTUAL <> NAREA THEN
        -- Contar cuántos registros tienen el mismo nombre que NAREA
        SELECT COUNT(*) INTO CANTIDAD FROM area WHERE area_nombre = NAREA;

        -- Si no hay áreas con el mismo nombre, actualizar
        IF CANTIDAD = 0 THEN
            UPDATE area 
            SET 
                area_estado = ESTADO, 
                area_nombre = NAREA
            WHERE area_id = ID;
            SELECT 1; -- Éxito
        ELSE
            SELECT 2; -- Nombre ya existente
        END IF;
    ELSE
        -- Si el nombre es el mismo, solo actualizar el estado
        UPDATE area 
        SET area_estado = ESTADO
        WHERE area_id = ID;
        SELECT 1; -- Éxito
    END IF;
END$$

DROP PROCEDURE IF EXISTS `SP_MODIFICAR_PERSONA`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `SP_MODIFICAR_PERSONA` (IN `p_id` INT, IN `p_nro` CHAR(8), IN `p_nom` VARCHAR(150), IN `p_apepa` VARCHAR(100), IN `p_apema` VARCHAR(100), IN `p_fnac` DATE, IN `p_movil` CHAR(9), IN `p_dire` VARCHAR(255), IN `p_email` VARCHAR(250), IN `p_esta` VARCHAR(20))   BEGIN
    DECLARE existe_dni INT;

    -- Verificar si el nuevo DNI ya está registrado en otra persona diferente a la actual
    SELECT COUNT(*) INTO existe_dni 
    FROM persona 
    WHERE per_nrodocumento = p_nro AND persona_id <> p_id;

    IF existe_dni = 0 THEN
        -- Actualizar los datos de la persona
        UPDATE persona 
        SET 
            per_nrodocumento = p_nro,
            per_nombre = p_nom,
            per_apepat = p_apepa,
            per_apemat = p_apema,
            per_fechanacimiento = p_fnac,
            per_movil = p_movil,
            per_direccion = p_dire,
            per_email = p_email,
            per_estado = p_esta
        WHERE persona_id = p_id;

        -- Confirmar que la actualización fue exitosa
        SELECT 1;
    ELSE
        -- Si el DNI ya está registrado en otra persona, no se actualiza
        SELECT 2;
    END IF;
END$$

DROP PROCEDURE IF EXISTS `SP_MODIFICAR_TIPO`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `SP_MODIFICAR_TIPO` (IN `ID` INT, IN `NTIPO` VARCHAR(255), IN `ESTADO` VARCHAR(29))   BEGIN
DECLARE TIPO_ACTUAL VARCHAR(255);
DECLARE CANTIDAD INT;

-- Obtener el nombre actual del tipo_documento
SELECT tipodo_descripcion INTO TIPO_ACTUAL FROM tipo_documento WHERE tipodocumento_id = ID;

-- Verificar si el nombre actual es diferente al nuevo nombre
IF TIPO_ACTUAL <> NTIPO THEN
    -- Contar cuántos registros tienen el mismo nombre que NTIPO
    SELECT COUNT(*) INTO CANTIDAD FROM tipo_documento WHERE tipodo_descripcion = NTIPO;

    -- Si no hay áreas con el mismo nombre, actualizar
    IF CANTIDAD = 0 THEN
        UPDATE tipo_documento
        SET
            tipodo_estado = ESTADO,
            tipodo_descripcion = NTIPO
        WHERE tipodocumento_id = ID;
        SELECT 1; -- Éxito
    ELSE
        SELECT 2; -- Nombre ya existente
    END IF;
ELSE
    -- Si el nombre es el mismo, solo actualizar el estado
    UPDATE tipo_documento
    SET tipodo_estado = ESTADO
    WHERE tipodocumento_id = ID;
    SELECT 1; -- Éxito
END IF;

END$$

DROP PROCEDURE IF EXISTS `SP_MODIFICAR_USUARIO`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `SP_MODIFICAR_USUARIO` (IN `p_usu_id` INT, IN `p_persona_id` INT, IN `p_area_id` INT, IN `p_usu_rol` VARCHAR(100))   BEGIN
        UPDATE usuario
        SET persona_id = p_persona_id,  -- Actualiza el ID de la persona
            area_id = p_area_id,        -- Actualiza el área del usuario
            usu_rol = p_usu_rol,        -- Actualiza el rol del usuario
            usu_fecupdate = CURRENT_TIMESTAMP  -- Actualiza la fecha de actualización
        WHERE usu_id = p_usu_id;
END$$

DROP PROCEDURE IF EXISTS `SP_MODIFICAR_USUARIO_CONTRA`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `SP_MODIFICAR_USUARIO_CONTRA` (IN `p_id` INT, IN `p_con` VARCHAR(255))   BEGIN
    -- Actualizar la contraseña en la base de datos
    UPDATE usuario 
    SET usu_contra = p_con
    WHERE usu_id = p_id;
    
END$$

DROP PROCEDURE IF EXISTS `SP_MODIFICAR_USUARIO_ESTATUS`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `SP_MODIFICAR_USUARIO_ESTATUS` (IN `p_id` INT, IN `p_estatus` ENUM('ACTIVO','INACTIVO'))   BEGIN
    -- Actualizar el estado del usuario
    UPDATE usuario 
    SET usu_estado = p_estatus
    WHERE usu_id = p_id;

END$$

DROP PROCEDURE IF EXISTS `SP_OBTENER_ARCHIVO_TRAMITE`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `SP_OBTENER_ARCHIVO_TRAMITE` (IN `p_id_tramite` VARCHAR(15))   BEGIN
    SELECT tramite_archivo AS archivo
    FROM tramite
    WHERE tramite_id = p_id_tramite;
END$$

DROP PROCEDURE IF EXISTS `SP_REGISTRAR_AREA`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `SP_REGISTRAR_AREA` (IN `NAREA` VARCHAR(255))   BEGIN
DECLARE area_existente int;

-- Verificar si el area ya	existe en la tabla 
SELECT COUNT(*) INTO area_existente
FROM area
WHERE area_nombre = NAREA;

IF area_existente > 0 THEN
    -- Si el area ya existe, devolvemos '2'
    SELECT 2;
ELSE
    -- Insertar el nuevo usuario
    INSERT INTO area(area_nombre,area_fecha_registro,area_estado)
    VALUES (NAREA, NOW(),'ACTIVO');

    -- Devolvemos '1' para indicar que el registro fue exitoso
    SELECT 1;
END IF;

END$$

DROP PROCEDURE IF EXISTS `SP_REGISTRAR_PERSONA`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `SP_REGISTRAR_PERSONA` (IN `p_nro` CHAR(8), IN `p_nom` VARCHAR(150), IN `p_apepa` VARCHAR(100), IN `p_apema` VARCHAR(100), IN `p_fnac` DATE, IN `p_movil` CHAR(9), IN `p_dire` VARCHAR(255), IN `p_email` VARCHAR(250))   BEGIN
    DECLARE existe_dni INT;

    -- Verificar si el número de documento ya existe
    SELECT COUNT(*) INTO existe_dni FROM persona WHERE per_nrodocumento = p_nro;

    IF existe_dni = 0 THEN
        -- Insertar nueva persona si no existe
        INSERT INTO persona (per_nrodocumento, per_nombre, per_apepat, per_apemat, per_fechanacimiento, per_movil, per_direccion, per_email, per_estado, per_fechacreacion)
        VALUES (p_nro, p_nom, p_apepa, p_apema, p_fnac, p_movil, p_dire, p_email, 'ACTIVO',CURRENT_DATE());

        SELECT 1; -- Registro exitoso
    ELSE
        SELECT 2; -- El número de documento ya existe
    END IF;
END$$

DROP PROCEDURE IF EXISTS `SP_REGISTRAR_TIPO`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `SP_REGISTRAR_TIPO` (IN `NTIPO` VARCHAR(255))   BEGIN
DECLARE tipodo_existente int;

-- Verificar si el tipodocumento ya	existe en la tabla
SELECT COUNT(*) INTO tipodo_existente
FROM tipo_documento
WHERE tipodo_descripcion = NTIPO;

IF tipodo_existente > 0 THEN
-- Si el tipodocumento ya existe, devolvemos '2'
SELECT 2;

ELSE
-- Insertar el nuevo tipodocumento
INSERT INTO tipo_documento(tipodo_descripcion,tipodo_estado,tipodo_fregistro)
VALUES (NTIPO, 'ACTIVO',NOW());

-- Devolvemos '1' para indicar que el registro fue exitoso
SELECT 1;

END IF;

END$$

DROP PROCEDURE IF EXISTS `SP_REGISTRAR_TRAMITE`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `SP_REGISTRAR_TRAMITE` (IN `DNI` CHAR(8), IN `NOMBRE` VARCHAR(150), IN `APEPAT` VARCHAR(50), IN `APEMAT` VARCHAR(50), IN `CEL` CHAR(9), IN `EMAIL` VARCHAR(150), IN `DIRECCION` VARCHAR(255), IN `REPRESENTACION` VARCHAR(50), IN `RUC` CHAR(12), IN `RAZON` VARCHAR(255), IN `AREAPRINCIPAL` INT, IN `AREADESTINO` INT, IN `TIPO` INT, IN `NRODOCUMENTO` VARCHAR(15), IN `ASUNTO` VARCHAR(255), IN `RUTA` VARCHAR(255), IN `FOLIO` INT, IN `IDUSUARIO` INT)   BEGIN
    DECLARE cantidad INT;
    DECLARE cod CHAR(12);

    -- Contar la cantidad de trámites registrados
    SET cantidad = (SELECT COUNT(*) FROM tramite);

    -- Generar el tramite_id según la cantidad existente
    IF cantidad >= 1 AND cantidad <= 8 THEN
        SET cod = CONCAT('D00000', cantidad + 1);
    ELSEIF cantidad >= 9 AND cantidad <= 98 THEN
        SET cod = CONCAT('D0000', cantidad + 1);
    ELSEIF cantidad >= 99 AND cantidad <= 998 THEN
        SET cod = CONCAT('D000', cantidad + 1);
    ELSEIF cantidad >= 999 AND cantidad <= 9998 THEN
        SET cod = CONCAT('D00', cantidad + 1);
    ELSEIF cantidad >= 9999 AND cantidad <= 99998 THEN
        SET cod = CONCAT('D0', cantidad + 1);
    ELSEIF cantidad >= 99999 AND cantidad <= 999998 THEN
        SET cod = CONCAT('D', cantidad + 1);
    ELSE
        SET cod = 'D000001';
    END IF;

    -- Insertar el nuevo trámite
    INSERT INTO tramite (
        tramite_id, remitente_dni, remitente_nombre, remitente_apepat, 
        remitente_apemat, remitente_celular, remitente_email, remitente_direccion, 
        tramite_doc_representacion, tramite_doc_ruc, tramite_doc_razon, area_origen, area_destino, tipodocumento_id, 
        tramite_nrodocumento, tramite_asunto, tramite_archivo, tramite_folio
    ) 
    VALUES (
        cod, DNI, NOMBRE, APEPAT, APEMAT, CEL, EMAIL, DIRECCION, REPRESENTACION, RUC, 
        RAZON, AREAPRINCIPAL, AREADESTINO, TIPO, NRODOCUMENTO, ASUNTO, RUTA, FOLIO
    );
    SELECT cod;
    
    INSERT INTO movimiento (
        tramite_id, area_origen_id, area_destino_id, mov_descripcion, usu_id,
        mov_archivo,mov_estado,mov_fecharegistro
    )
    VALUES (
         cod, AREAPRINCIPAL, AREADESTINO, ASUNTO, IDUSUARIO, RUTA, 'PENDIENTE',NOW()
         );
END$$

DROP PROCEDURE IF EXISTS `SP_REGISTRAR_TRAMITE_DERIVAR`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `SP_REGISTRAR_TRAMITE_DERIVAR` (IN `ID` CHAR(15), IN `ORIGEN` INT, IN `DESTINO` INT, IN `DESCRIPCION` VARCHAR(255), IN `IDUSUARIO` INT, IN `RUTA` VARCHAR(255))   BEGIN
    DECLARE IDMOVIMIENTO INT;
    DECLARE ARCHIVO_ANTERIOR VARCHAR(255);

    -- Obtener el último movimiento PENDIENTE del trámite actual
    SELECT movimiento_id INTO IDMOVIMIENTO
    FROM movimiento
    WHERE mov_estado = 'PENDIENTE' AND tramite_id = ID
    ORDER BY mov_fecharegistro DESC
    LIMIT 1;
    
    -- Marcarlo como DERIVADO
    UPDATE movimiento SET mov_estado = 'DERIVADO'
    WHERE movimiento_id = IDMOVIMIENTO;

    -- Actualizar datos del trámite
    UPDATE tramite SET area_origen = ORIGEN, area_destino = DESTINO
    WHERE tramite_id = ID;
    
    -- Si no hay un nuevo archivo, usamos el anterior
    IF RUTA IS NULL OR LENGTH(RUTA) = 0 THEN
    	SET RUTA = ARCHIVO_ANTERIOR;
    END IF;

    -- Registrar nuevo movimiento
    INSERT INTO movimiento (
        tramite_id, area_origen_id, area_destino_id, mov_descripcion, usu_id,
        mov_archivo, mov_estado, mov_fecharegistro
    )
    VALUES (
        ID, ORIGEN, DESTINO, DESCRIPCION, IDUSUARIO, RUTA, 'PENDIENTE', NOW()
    );
END$$

DROP PROCEDURE IF EXISTS `SP_REGISTRAR_USUARIO`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `SP_REGISTRAR_USUARIO` (IN `p_usu_usuario` VARCHAR(250), IN `p_usu_contra` VARCHAR(250), IN `p_persona_id` INT, IN `p_area_id` INT, IN `p_usu_rol` VARCHAR(100))   BEGIN
    DECLARE usuario_existente INT;
    DECLARE persona_asignada INT;

    -- Verificar si el usuario ya existe en la tabla
    SELECT COUNT(*) INTO usuario_existente 
    FROM usuario 
    WHERE usu_usuario = p_usu_usuario;

    -- Verificar si la persona ya tiene un usuario asignado
    SELECT COUNT(*) INTO persona_asignada 
    FROM usuario 
    WHERE persona_id = p_persona_id;

    IF usuario_existente > 0 THEN
        -- Si el usuario ya existe, devolvemos '2'
        SELECT 2;
    ELSEIF persona_asignada > 0 THEN
        -- Si la persona ya tiene un usuario, devolvemos '3'
        SELECT 3;
    ELSE
        -- Insertar el nuevo usuario
        INSERT INTO usuario (usu_usuario, usu_contra, persona_id, area_id, usu_rol, usu_estado)
        VALUES (p_usu_usuario, p_usu_contra, p_persona_id, p_area_id, p_usu_rol, 'ACTIVO');

        -- Devolvemos '1' para indicar que el registro fue exitoso
        SELECT 1;
    END IF;
END$$

DROP PROCEDURE IF EXISTS `SP_TRAER_WIDGET`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `SP_TRAER_WIDGET` ()   BEGIN
SELECT
(SELECT COUNT(*) FROM tramite),
(SELECT COUNT(*) FROM tramite WHERE tramite.tramite_estado='FINALIZADO')
FROM tramite LIMIT 1;

END$$

DROP PROCEDURE IF EXISTS `SP_VERIFICAR_USUARIO`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `SP_VERIFICAR_USUARIO` (IN `p_usu_usuario` VARCHAR(250))   BEGIN
    -- Buscar al usuario por su nombre de usuario
    SELECT 
        u.usu_id, 
        u.usu_usuario, 
        u.usu_contra, 
        u.usu_estado, 
        u.persona_id, 
        u.area_id, 
        a.area_nombre,
        u.usu_rol
    FROM usuario u
    LEFT JOIN area a ON u.area_id = a.area_id
    WHERE usu_usuario = p_usu_usuario;
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `area`
--

DROP TABLE IF EXISTS `area`;
CREATE TABLE `area` (
  `area_id` int NOT NULL,
  `area_nombre` varchar(50) NOT NULL,
  `area_fecha_registro` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `area_estado` enum('ACTIVO','INACTIVO') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `area`
--

INSERT INTO `area` (`area_id`, `area_nombre`, `area_fecha_registro`, `area_estado`) VALUES
(1, 'ADMINISTRATIVA', '2025-03-07 01:22:04', 'ACTIVO'),
(2, 'ACADÉMICA', '2025-03-08 15:50:23', 'ACTIVO'),
(6, 'RECURSOS HUMANOS', '2025-03-08 16:26:56', 'ACTIVO'),
(8, 'APOYO ESTUDIANTIL', '2025-03-08 17:32:58', 'ACTIVO'),
(12, 'SECRETARíA', '2025-03-14 04:28:06', 'INACTIVO'),
(15, 'DIRECCIÓN', '2025-03-14 04:32:57', 'ACTIVO'),
(16, 'SUBDIRECCIÓN', '2025-03-14 04:33:04', 'ACTIVO'),
(17, 'MESA DE PARTES', '2025-03-14 15:50:25', 'ACTIVO');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `empresa`
--

DROP TABLE IF EXISTS `empresa`;
CREATE TABLE `empresa` (
  `empresa_id` int NOT NULL,
  `emp_razon` varchar(250) NOT NULL,
  `emp_email` varchar(250) NOT NULL,
  `emp_cod` varchar(10) NOT NULL,
  `emp_telefono` varchar(20) NOT NULL,
  `emp_direccion` varchar(250) NOT NULL,
  `emp_logo` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `empresa`
--

INSERT INTO `empresa` (`empresa_id`, `emp_razon`, `emp_email`, `emp_cod`, `emp_telefono`, `emp_direccion`, `emp_logo`) VALUES
(1, 'IEP 21001', 'IEP21001@gmail.com', '123', '98756412', 'imperial', '21001.png');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `movimiento`
--

DROP TABLE IF EXISTS `movimiento`;
CREATE TABLE `movimiento` (
  `movimiento_id` int NOT NULL,
  `tramite_id` char(12) NOT NULL,
  `area_origen_id` int DEFAULT NULL,
  `area_destino_id` int NOT NULL,
  `mov_fecharegistro` datetime DEFAULT CURRENT_TIMESTAMP,
  `mov_descripcion` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT 'Sin descripción',
  `mov_estado` enum('PENDIENTE','CONFORME','INCONFORME','ACEPTADO','DERIVADO','FINALIZADO','RECHAZADO') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `usu_id` int DEFAULT NULL,
  `mov_archivo` varchar(255) DEFAULT NULL,
  `mov_descripcion_original` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `movimiento`
--

INSERT INTO `movimiento` (`movimiento_id`, `tramite_id`, `area_origen_id`, `area_destino_id`, `mov_fecharegistro`, `mov_descripcion`, `mov_estado`, `usu_id`, `mov_archivo`, `mov_descripcion_original`) VALUES
(76, 'D000001', 17, 6, '2025-05-12 11:10:59', 'INFORMO EL CV DE DANTE DESDE MP A RRHH', 'DERIVADO', 11, 'controller/tramite/documentos/ARCH125202511623.PDF', NULL),
(77, 'D000001', 6, 8, '2025-05-12 11:12:38', 'DE DERIVO DE RRHH A APOYO SIN ANEXAR DOC EN LA DERIVACION PERO SI CUENTAS CON UN ARCHIVO INICIAL', 'DERIVADO', 10, NULL, NULL),
(78, 'D000002', 1, 17, '2025-05-12 11:56:12', 'SFDGS', 'DERIVADO', 1, 'controller/tramite/documentos/ARCH125202511866.PDF', NULL),
(79, 'D000002', 17, 6, '2025-05-12 13:47:12', 'DERIVANDO DESDE MESA DE PARTES A RRHH CON UN DOCUMENTO DEL MOVIMIENTO', 'ACEPTADO', 11, NULL, NULL),
(80, 'D000002', 17, 6, '2025-05-12 16:35:47', 'ACEPTO TRAMITE D000002', 'ACEPTADO', 10, NULL, NULL),
(81, 'D000001', 6, 8, '2025-05-12 17:07:49', 'ACEPTO D000001', 'ACEPTADO', 9, NULL, NULL),
(82, 'D000001', 8, 6, '2025-05-12 17:42:14', 'DERIVANDO UN TRAMITE ACEPTADO DESDE APOYO ESTUDIANTIL A RRHH', 'PENDIENTE', 9, NULL, NULL),
(83, 'D000002', 6, 8, '2025-05-12 17:50:50', 'DERIVO OTRO TRAMITE ACEPTADO DESDE RRHH A APOYO ', 'PENDIENTE', 10, NULL, NULL),
(84, 'D000003', 17, 6, '2025-05-13 02:33:50', 'SOLICITO HORARIO GAA DESDE MESA PARTES A RRHH', 'PENDIENTE', 11, 'controller/tramite/documentos/ARCH13520252920.PDF', NULL),
(85, 'D000003', 17, 6, '2025-05-13 02:41:33', 'AHORA LO RECHAZO GAAAAAAA', 'RECHAZADO', 10, NULL, NULL),
(86, 'D000004', 15, 17, '2025-05-23 11:44:21', 'ESTOY REGISTRANDO UN TRAMITE DE SOLICITUD DESDE DIRECCION', 'DERIVADO', 13, 'controller/tramite/documentos/ARCH235202511712.PDF', NULL),
(87, 'D000004', 17, 8, '2025-05-23 11:51:22', 'DERIVANDO DESDE MESA DE PARTES HASTA APOYO ESTUDIANTIL SIN ANEXAR DOC', 'PENDIENTE', 11, NULL, NULL),
(88, 'D000004', 17, 8, '2025-05-23 11:56:04', 'ACEPTO TARMITE QUE SE REALIZO EN DIRECCION DESDEAPOYO ESTUDIANTIL', 'ACEPTADO', 9, NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `persona`
--

DROP TABLE IF EXISTS `persona`;
CREATE TABLE `persona` (
  `persona_id` int NOT NULL,
  `per_nombre` varchar(150) DEFAULT NULL,
  `per_apepat` varchar(100) DEFAULT NULL,
  `per_apemat` varchar(100) DEFAULT NULL,
  `per_fechacreacion` date DEFAULT NULL,
  `per_fechanacimiento` date DEFAULT NULL,
  `per_nrodocumento` char(8) DEFAULT NULL,
  `per_movil` char(9) DEFAULT NULL,
  `per_email` varchar(250) DEFAULT NULL,
  `per_estado` enum('ACTIVO','INACTIVO') DEFAULT NULL,
  `per_direccion` varchar(255) DEFAULT NULL,
  `per_foto` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `persona`
--

INSERT INTO `persona` (`persona_id`, `per_nombre`, `per_apepat`, `per_apemat`, `per_fechacreacion`, `per_fechanacimiento`, `per_nrodocumento`, `per_movil`, `per_email`, `per_estado`, `per_direccion`, `per_foto`) VALUES
(1, 'DANTE JOSSUE', 'CAMPOS', 'OCHOA', '2025-03-06', '2004-01-09', '74432978', '964890773', 'dantecampos669@gmail.com', 'ACTIVO', 'San Luis, Laura Caller', 'controller/persona/FOTO/foto_1.jpeg'),
(8, 'JUANITA', 'PEREZ', 'QUEVEDO', '2025-03-14', '2025-03-05', '78956455', '98765423', 'JUANA@GMAIL.COM', 'ACTIVO', 'IMPERIAL', 'controller/persona/FOTO/foto_8.jpeg'),
(9, 'MARIA', 'CAMPOS', 'OCHOA', '2025-03-14', '2025-03-14', '76152312', '987345667', 'MARIA@GMAIL.COM', 'ACTIVO', 'LAURA CALLER', 'controller/persona/FOTO/foto_9.png'),
(10, 'JEFERSON', 'PERES', 'SANCHEZ', '2025-05-09', '2025-04-30', '78946511', '987654321', 'EMPLEADO@GMAIL.COM', 'ACTIVO', 'SAN LUIS - LOS CHAMOS', 'controller/persona/FOTO/foto_10.jpg'),
(11, 'DIEGO GIANFRANCO', 'VICENTE', 'GUERRA', '2025-05-23', '2001-12-01', '73831093', '971987987', 'DIEGINHO669@GMAIL.COM', 'ACTIVO', 'SAN VICENTE', 'controller/persona/FOTO/foto_11.jpg'),
(12, 'SECRETARIO NOMBRE', 'ASDASD', 'ASDASD', '2025-05-23', '2025-04-29', '76546546', '68798465', 'ASDASD@GMAIL.COM', 'ACTIVO', 'LAURA CALLERAS', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipo_documento`
--

DROP TABLE IF EXISTS `tipo_documento`;
CREATE TABLE `tipo_documento` (
  `tipodocumento_id` int NOT NULL,
  `tipodo_descripcion` varchar(50) NOT NULL,
  `tipodo_estado` enum('ACTIVO','INACTIVO') DEFAULT NULL,
  `tipodo_fregistro` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `tipo_documento`
--

INSERT INTO `tipo_documento` (`tipodocumento_id`, `tipodo_descripcion`, `tipodo_estado`, `tipodo_fregistro`) VALUES
(1, 'SOLICITUD', 'ACTIVO', '2025-03-08 12:07:51'),
(2, 'INFORME', 'ACTIVO', '2025-03-08 12:28:28');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tramite`
--

DROP TABLE IF EXISTS `tramite`;
CREATE TABLE `tramite` (
  `tramite_id` char(12) NOT NULL,
  `remitente_dni` char(8) NOT NULL,
  `remitente_nombre` varchar(150) NOT NULL,
  `remitente_apepat` varchar(50) NOT NULL,
  `remitente_apemat` varchar(50) NOT NULL,
  `remitente_celular` char(9) NOT NULL,
  `remitente_email` varchar(150) NOT NULL,
  `remitente_direccion` varchar(255) NOT NULL,
  `tramite_doc_razon` varchar(255) NOT NULL,
  `tramite_doc_ruc` char(12) NOT NULL,
  `tramite_doc_representacion` varchar(50) NOT NULL,
  `tipodocumento_id` int NOT NULL,
  `tramite_nrodocumento` varchar(15) NOT NULL DEFAULT '',
  `tramite_folio` int NOT NULL,
  `tramite_asunto` varchar(255) NOT NULL,
  `tramite_archivo` varchar(255) NOT NULL,
  `tramite_fecharegistro` datetime DEFAULT CURRENT_TIMESTAMP,
  `tramite_estado` enum('PENDIENTE','ACEPTADO','RECHAZADO','FINALIZADO') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `tramite_nroexpediente` varchar(20) DEFAULT NULL,
  `area_origen` int NOT NULL,
  `area_destino` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `tramite`
--

INSERT INTO `tramite` (`tramite_id`, `remitente_dni`, `remitente_nombre`, `remitente_apepat`, `remitente_apemat`, `remitente_celular`, `remitente_email`, `remitente_direccion`, `tramite_doc_razon`, `tramite_doc_ruc`, `tramite_doc_representacion`, `tipodocumento_id`, `tramite_nrodocumento`, `tramite_folio`, `tramite_asunto`, `tramite_archivo`, `tramite_fecharegistro`, `tramite_estado`, `tramite_nroexpediente`, `area_origen`, `area_destino`) VALUES
('D000001', '78946511', 'JEFERSON', 'PERES', 'SANCHEZ', '987654321', 'EMPLEADO@GMAIL.COM', 'SAN LUIS - LOS CHAMOS', '', '', 'A NOMBRE PROPIO', 2, '001', 1, 'INFORMO EL CV DE DANTE DESDE MP A RRHH', 'controller/tramite/documentos/ARCH125202511623.PDF', '2025-05-12 11:10:59', 'ACEPTADO', NULL, 8, 6),
('D000002', '78946511', 'DANTE ', 'CAMPOS', 'OCHOA', '987345667', 'DANTECAMPOS669@GMAIL.COM', 'SAN LUIS - LOS CHAMOS', 'DANDEV GAA', '123123', 'PERSONA JURíDICA', 1, '001', 2, 'SFDGS', 'controller/tramite/documentos/ARCH125202511866.PDF', '2025-05-12 11:56:12', 'ACEPTADO', NULL, 6, 8),
('D000003', '78946511', 'JEFERSON', 'PERES', 'SANCHEZ', '987654321', 'EMPLEADO@GMAIL.COM', 'SAN LUIS - LOS CHAMOS', 'DANTE EL MAS PRO GAAA', '312312', 'PERSONA JURíDICA', 1, '001', 1, 'SOLICITO HORARIO GAA DESDE MESA PARTES A RRHH', 'controller/tramite/documentos/ARCH13520252920.PDF', '2025-05-13 02:33:50', 'RECHAZADO', NULL, 17, 6),
('D000004', '76546546', 'SECRETARIO NOMBRE', 'ASDASD', 'ASDASD', '68798465', 'ASDASD@GMAIL.COM', 'LAURA CALLERAS', '', '', 'A NOMBRE PROPIO', 1, '001', 1, 'ESTOY REGISTRANDO UN TRAMITE DE SOLICITUD DESDE DIRECCION', 'controller/tramite/documentos/ARCH235202511712.PDF', '2025-05-23 11:44:21', 'ACEPTADO', NULL, 17, 8);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario`
--

DROP TABLE IF EXISTS `usuario`;
CREATE TABLE `usuario` (
  `usu_id` int NOT NULL,
  `usu_usuario` varchar(250) NOT NULL,
  `usu_contra` varchar(250) NOT NULL,
  `usu_feccreacion` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `usu_fecupdate` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `persona_id` int NOT NULL,
  `usu_observacion` varchar(250) DEFAULT NULL,
  `usu_estado` enum('ACTIVO','INACTIVO') DEFAULT NULL,
  `area_id` int NOT NULL,
  `usu_rol` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `usuario`
--

INSERT INTO `usuario` (`usu_id`, `usu_usuario`, `usu_contra`, `usu_feccreacion`, `usu_fecupdate`, `persona_id`, `usu_observacion`, `usu_estado`, `area_id`, `usu_rol`) VALUES
(1, 'admin', '$2y$12$Uito/Y5Pycu7uANkrHbA9uh0.DXBXvN.5fRHstaD064RjHMzlBBbq', '2025-03-06 05:00:00', '2025-03-08 21:21:20', 1, 'Primer administrador de prueba para el desarollo de este sistema', 'ACTIVO', 1, 'ADMINISTRADOR(A)'),
(9, 'JUANA', '$2y$12$NKvVAXY1AMzbqAwb298wdeI75Ahu.naLsSWjx63CVdJc466/.VASG', '2025-03-14 16:47:50', '2025-05-10 23:07:20', 8, NULL, 'ACTIVO', 8, 'SECRETARIO(A)'),
(10, 'MARIA', '$2y$12$4JG2YXmQq9iHljJD6Mip0O1a1ZcMiQRKPW0Y1q32xOdZjTtQB6ON2', '2025-03-14 16:52:27', '2025-03-14 16:52:27', 9, NULL, 'ACTIVO', 6, 'SECRETARIO(A)'),
(11, 'JEFER', '$2y$12$66SNwxvD8S0Ln7GVW2VJseowJbQfzYHtfYGQQ0.MQkFG5kOB35UwO', '2025-05-10 20:47:33', '2025-05-23 16:46:38', 10, NULL, 'ACTIVO', 17, 'SECRETARIO(A)'),
(12, 'DIEGOVG', '$2y$12$mEiqDHKh3gQvNLQiJkxjwuyPVfOK3t2tSUpeYnT2micZccvDuhUF.', '2025-05-23 16:34:27', '2025-05-23 16:34:27', 11, NULL, 'ACTIVO', 1, 'ADMINISTRADOR(A)'),
(13, 'JEFF', '$2y$12$70oKPQ0UzDKEWSgA/MNnA.FJMza6tRkEVpeqGazpOLgCupvrRequq', '2025-05-23 16:40:26', '2025-05-23 16:40:26', 12, NULL, 'ACTIVO', 15, 'SECRETARIO(A)');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `area`
--
ALTER TABLE `area`
  ADD PRIMARY KEY (`area_id`),
  ADD UNIQUE KEY `area_nombre` (`area_nombre`);

--
-- Indices de la tabla `empresa`
--
ALTER TABLE `empresa`
  ADD PRIMARY KEY (`empresa_id`);

--
-- Indices de la tabla `movimiento`
--
ALTER TABLE `movimiento`
  ADD PRIMARY KEY (`movimiento_id`),
  ADD KEY `tramite_id` (`tramite_id`),
  ADD KEY `area_origen_id` (`area_origen_id`),
  ADD KEY `area_destino_id` (`area_destino_id`),
  ADD KEY `usu_id` (`usu_id`);

--
-- Indices de la tabla `persona`
--
ALTER TABLE `persona`
  ADD PRIMARY KEY (`persona_id`);

--
-- Indices de la tabla `tipo_documento`
--
ALTER TABLE `tipo_documento`
  ADD PRIMARY KEY (`tipodocumento_id`);

--
-- Indices de la tabla `tramite`
--
ALTER TABLE `tramite`
  ADD PRIMARY KEY (`tramite_id`),
  ADD UNIQUE KEY `tramite_nroexpediente` (`tramite_nroexpediente`),
  ADD KEY `tipodocumento_id` (`tipodocumento_id`),
  ADD KEY `area_origen` (`area_origen`),
  ADD KEY `area_destino` (`area_destino`);

--
-- Indices de la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`usu_id`),
  ADD KEY `persona_id` (`persona_id`),
  ADD KEY `area_id` (`area_id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `area`
--
ALTER TABLE `area`
  MODIFY `area_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT de la tabla `empresa`
--
ALTER TABLE `empresa`
  MODIFY `empresa_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `movimiento`
--
ALTER TABLE `movimiento`
  MODIFY `movimiento_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=89;

--
-- AUTO_INCREMENT de la tabla `persona`
--
ALTER TABLE `persona`
  MODIFY `persona_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT de la tabla `tipo_documento`
--
ALTER TABLE `tipo_documento`
  MODIFY `tipodocumento_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `usuario`
--
ALTER TABLE `usuario`
  MODIFY `usu_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `movimiento`
--
ALTER TABLE `movimiento`
  ADD CONSTRAINT `movimiento_ibfk_1` FOREIGN KEY (`tramite_id`) REFERENCES `tramite` (`tramite_id`),
  ADD CONSTRAINT `movimiento_ibfk_2` FOREIGN KEY (`area_origen_id`) REFERENCES `area` (`area_id`),
  ADD CONSTRAINT `movimiento_ibfk_3` FOREIGN KEY (`area_destino_id`) REFERENCES `area` (`area_id`),
  ADD CONSTRAINT `movimiento_ibfk_4` FOREIGN KEY (`usu_id`) REFERENCES `usuario` (`usu_id`);

--
-- Filtros para la tabla `tramite`
--
ALTER TABLE `tramite`
  ADD CONSTRAINT `tramite_ibfk_1` FOREIGN KEY (`tipodocumento_id`) REFERENCES `tipo_documento` (`tipodocumento_id`),
  ADD CONSTRAINT `tramite_ibfk_2` FOREIGN KEY (`area_origen`) REFERENCES `area` (`area_id`),
  ADD CONSTRAINT `tramite_ibfk_3` FOREIGN KEY (`area_destino`) REFERENCES `area` (`area_id`);

--
-- Filtros para la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD CONSTRAINT `usuario_ibfk_1` FOREIGN KEY (`persona_id`) REFERENCES `persona` (`persona_id`),
  ADD CONSTRAINT `usuario_ibfk_2` FOREIGN KEY (`area_id`) REFERENCES `area` (`area_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

-- MODIFICAR EL PROCEDIMIENTO SP_LISTAR_TRAMITES y en SP_LISTAR_TRAMITE_AREA:
-- Subconsulta para obtener la fecha del último movimiento PENDIENTE, lo colocan debajo del campo 't.area_destino,'
        (
          SELECT mov_fecharegistro
          FROM movimiento
          WHERE tramite_id = t.tramite_id AND mov_estado = 'PENDIENTE'
          ORDER BY mov_fecharegistro DESC
          LIMIT 1
        ) AS pendiente_fecha