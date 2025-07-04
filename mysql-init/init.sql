-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Servidor: db:3306
-- Tiempo de generación: 02-07-2025 a las 16:53:58
-- Versión del servidor: 8.4.3
-- Versión de PHP: 8.2.27

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
CREATE DEFINER=`root`@`localhost` PROCEDURE `ObtenerConteoTramitesDeArea` (IN `p_area_id` INT)   BEGIN
    SELECT
        (SELECT COUNT(*) FROM tramite WHERE p_area_id IS NULL OR tramite.area_destino = p_area_id),
        (SELECT COUNT(*) FROM tramite WHERE (p_area_id IS NULL OR tramite.area_destino = p_area_id) AND tramite_estado = 'ACEPTADO'),
        (SELECT COUNT(*) FROM tramite WHERE (p_area_id IS NULL OR tramite.area_destino = p_area_id) AND tramite_estado = 'PENDIENTE'),
        (SELECT COUNT(*) FROM tramite WHERE (p_area_id IS NULL OR tramite.area_destino = p_area_id) AND tramite_estado = 'RECHAZADO');
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `SP_CAMBIAR_ESTADO_TRAMITE` (IN `ID_TRAMITE` VARCHAR(15), IN `NUEVO_ESTADO` VARCHAR(20), IN `DESCRIPCION` TEXT, IN `ID_USUARIO` INT, IN `AREA_DESTINO_ID` INT)   BEGIN
    DECLARE ID_MOVIMIENTO INT;
    DECLARE AREA_ACTUAL INT;

    -- Obtener el área actual del usuario que hace la acción
    SELECT area_id INTO AREA_ACTUAL
    FROM usuario
    WHERE usu_id = ID_USUARIO;

    -- Obtener el último movimiento PENDIENTE y marcarlo como atendido (opcional)
    SELECT movimiento_id INTO ID_MOVIMIENTO
    FROM movimiento
    WHERE tramite_id = ID_TRAMITE AND mov_estado = 'PENDIENTE'
    ORDER BY mov_fecharegistro DESC
    LIMIT 1;

    -- (Opcional) Puedes marcar el movimiento anterior como atendido
    IF ID_MOVIMIENTO IS NOT NULL THEN
        UPDATE movimiento SET mov_estado = 'PROCESADO'
        WHERE movimiento_id = ID_MOVIMIENTO;
    END IF;

    -- Insertar nuevo movimiento con el área actual del usuario
    INSERT INTO movimiento (
        tramite_id, area_origen_id, area_destino_id, mov_descripcion,
        mov_estado, usu_id, mov_fecharegistro
    ) VALUES (
        ID_TRAMITE, AREA_ACTUAL, AREA_DESTINO_ID, DESCRIPCION,
        NUEVO_ESTADO, ID_USUARIO, NOW()
    );

    -- Actualizar el estado del trámite
    UPDATE tramite
    SET tramite_estado = NUEVO_ESTADO
    WHERE tramite_id = ID_TRAMITE;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `SP_CARGAR_SEGUIMIENTO_TRAMITE` (IN `NUMERO` VARCHAR(12), IN `DNI` VARCHAR(12))   BEGIN
SELECT tramite_id, tramite_nrodocumento, remitente_dni, CONCAT(tramite.remitente_nombre,' ',tramite.remitente_apepat,' ',tramite.remitente_apemat), tramite.tramite_fecharegistro, tramite_asunto, td.tipodo_descripcion, tramite_doc_ruc, tramite_doc_razon
FROM tramite
INNER JOIN tipo_documento td ON tramite.tipodocumento_id = td.tipodocumento_id
WHERE tramite.tramite_id = NUMERO and tramite.remitente_dni = DNI;
END$$

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

CREATE DEFINER=`root`@`localhost` PROCEDURE `SP_CARGAR_SELECT_AREA` ()   BEGIN
    SELECT area_id, area_nombre
    FROM area
    WHERE area_estado = 'ACTIVO'
    ORDER BY area_nombre ASC;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `SP_CARGAR_SELECT_PERSONA` ()   BEGIN
    SELECT persona_id, CONCAT(per_nombre, ' ', per_apepat, ' ', per_apemat) AS persona_nombre
    FROM persona
    WHERE per_estado = 'ACTIVO'
    ORDER BY per_nombre ASC;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `SP_CARGAR_SELECT_TIPO` ()   BEGIN
SELECT tipodocumento_id , tipodo_descripcion
FROM tipo_documento
WHERE tipodo_estado = 'ACTIVO'
ORDER BY tipodo_descripcion ASC;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `SP_LISTAR_AREA` ()   BEGIN
SELECT
area.area_id,
area.area_nombre,
area.area_fecha_registro,
area.area_estado
FROM area;

END$$

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

CREATE DEFINER=`root`@`localhost` PROCEDURE `SP_LISTAR_TIPO_DOCUMENTO` ()   BEGIN
SELECT
tipo_documento.tipodocumento_id,
tipo_documento.tipodo_descripcion,
tipo_documento.tipodo_estado,
tipo_documento.tipodo_fregistro
FROM tipo_documento;
END$$

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
        t.area_destino,
        -- ✅ Subconsulta para obtener la fecha del último movimiento PENDIENTE
        (
          SELECT mov_fecharegistro
          FROM movimiento
          WHERE tramite_id = t.tramite_id AND mov_estado = 'PENDIENTE'
          ORDER BY mov_fecharegistro DESC
          LIMIT 1
        ) AS pendiente_fecha
        
    FROM 
        tramite t
    INNER JOIN 
        tipo_documento td ON t.tipodocumento_id = td.tipodocumento_id -- Relación con tipo_documento
    INNER JOIN 
        area a_origen ON t.area_origen = a_origen.area_id -- Relación con área de origen
    LEFT JOIN 
        area a_destino ON t.area_destino = a_destino.area_id -- Relación con área actual (puede ser NULL)
    ORDER BY t.tramite_fecharegistro DESC;
END$$

CREATE DEFINER=`root`@`%` PROCEDURE `sp_listar_tramites_enviados` (IN `IDUSUARIO` INT)   BEGIN
    DECLARE IDAREA INT;

    -- Obtener el área del usuario
    SELECT area_id INTO IDAREA
    FROM usuario
    WHERE usu_id = IDUSUARIO;

    -- Listar trámites enviados desde el área del usuario hacia otras áreas
    SELECT 
        t.tramite_id,
        t.remitente_dni,
        CONCAT(t.remitente_nombre, ' ', t.remitente_apepat, ' ', t.remitente_apemat) AS datos_remitente,
        t.tipodocumento_id,
        td.tipodo_descripcion,
        t.tramite_nrodocumento,
        a_origen.area_nombre AS area_origen_nombre,
        a_destino.area_nombre AS area_destino_nombre,
        t.tramite_estado,
        t.remitente_nombre,
        t.remitente_apepat,
        t.remitente_apemat,
        t.remitente_celular,
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
        t.area_destino,
        (
            SELECT mov_fecharegistro
            FROM movimiento
            WHERE tramite_id = t.tramite_id AND mov_estado = 'PENDIENTE'
            ORDER BY mov_fecharegistro DESC
            LIMIT 1
        ) AS pendiente_fecha

    FROM tramite t
    INNER JOIN tipo_documento td ON t.tipodocumento_id = td.tipodocumento_id
    INNER JOIN area a_origen ON t.area_origen = a_origen.area_id
    LEFT JOIN area a_destino ON t.area_destino = a_destino.area_id
    WHERE 
        t.area_origen = IDAREA -- Trámite iniciado desde el área del usuario
        AND t.area_destino IS NOT NULL
        AND t.area_destino <> IDAREA -- Se envió a otra área
        AND t.tramite_estado <> 'FINALIZADO' -- Opcional: excluye finalizados si deseas
    ORDER BY t.tramite_fecharegistro DESC;
END$$

CREATE DEFINER=`root`@`%` PROCEDURE `SP_LISTAR_TRAMITES_FILTRO` (IN `_fechaini` DATE, IN `_fechafin` DATE, IN `_estado` VARCHAR(20), IN `_areaid` INT)   BEGIN
    SELECT 
        t.tramite_id,
        t.remitente_dni,
        CONCAT(t.remitente_nombre, ' ', t.remitente_apepat, ' ', t.remitente_apemat) AS datos_remitente,
        t.tipodocumento_id,
        td.tipodo_descripcion,
        t.tramite_nrodocumento,
        a_origen.area_nombre AS area_origen_id,
        a_destino.area_nombre AS area_destino_id,
        t.tramite_estado,
        t.remitente_nombre,
        t.remitente_apepat,
        t.remitente_apemat,
        t.remitente_celular,
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
        t.area_destino,
        (
            SELECT mov_fecharegistro
            FROM movimiento
            WHERE tramite_id = t.tramite_id AND mov_estado = 'PENDIENTE'
            ORDER BY mov_fecharegistro DESC
            LIMIT 1
        ) AS pendiente_fecha
    FROM tramite t
    INNER JOIN tipo_documento td ON t.tipodocumento_id = td.tipodocumento_id
    INNER JOIN area a_origen ON t.area_origen = a_origen.area_id
    LEFT JOIN area a_destino ON t.area_destino = a_destino.area_id
    WHERE 
        (_fechaini IS NULL OR DATE(t.tramite_fecharegistro) >= _fechaini)
        AND (_fechafin IS NULL OR DATE(t.tramite_fecharegistro) <= _fechafin)
        AND (_estado = '' OR t.tramite_estado = _estado)
        AND (_areaid = 0 OR t.area_destino = _areaid)
    ORDER BY t.tramite_fecharegistro DESC;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `SP_LISTAR_TRAMITE_AREA` (IN `IDUSUARIO` INT, IN `P_ESTADO` VARCHAR(50))   BEGIN
    DECLARE IDAREA INT;

    -- Obtener el área del usuario
    SELECT area_id INTO IDAREA
    FROM usuario
    WHERE usu_id = IDUSUARIO;

    -- Listar trámites correspondientes al área y estado
    SELECT
        t.tramite_id,
        t.remitente_dni,
        CONCAT(t.remitente_nombre, ' ', t.remitente_apepat, ' ', t.remitente_apemat) AS datos_remitente,
        t.tipodocumento_id,
        td.tipodo_descripcion,
        t.tramite_nrodocumento,
        a_origen.area_nombre AS area_origen_id,
        a_destino.area_nombre AS area_destino_id,
        t.tramite_estado,
        t.remitente_nombre,
        t.remitente_apepat,
        t.remitente_apemat,
        t.remitente_celular,
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
        t.area_destino,

        -- Fecha del último movimiento pendiente
        (
            SELECT mov_fecharegistro
            FROM movimiento
            WHERE tramite_id = t.tramite_id AND mov_estado = 'PENDIENTE'
            ORDER BY mov_fecharegistro DESC
            LIMIT 1
        ) AS pendiente_fecha

    FROM tramite t
    INNER JOIN tipo_documento td ON t.tipodocumento_id = td.tipodocumento_id
    INNER JOIN area a_origen ON t.area_origen = a_origen.area_id
    LEFT JOIN area a_destino ON t.area_destino = a_destino.area_id
    WHERE t.area_destino = IDAREA AND t.tramite_estado = P_ESTADO;

END$$

CREATE DEFINER=`root`@`%` PROCEDURE `SP_LISTAR_TRAMITE_AREA_DERIVADOS` (IN `IDUSUARIO` INT, IN `P_ESTADO` VARCHAR(50), IN `P_FECHAINI` DATE, IN `P_FECHAFIN` DATE)   BEGIN
    DECLARE IDAREA INT;

    -- Obtener el área del usuario actual
    SELECT area_id INTO IDAREA FROM usuario WHERE usu_id = IDUSUARIO;

    -- Consultar los trámites derivados a esta área, con filtros de fecha
    
    -- Consultar los trámites derivados a esta área, con filtros de fecha
    SELECT
        t.tramite_id,
        t.remitente_dni,
        CONCAT(t.remitente_nombre, ' ', t.remitente_apepat, ' ', t.remitente_apemat) AS datos_remitente,
        t.tipodocumento_id,
        td.tipodo_descripcion,
        t.tramite_nrodocumento,
        td.tipodo_descripcion,
        m.area_origen_id,
        m.area_destino_id,
        a_origen.area_nombre AS area_origen_nombre,
        a_destino.area_nombre AS area_destino_nombre,
        m.mov_estado,
        t.remitente_nombre,
        t.remitente_apepat,
        t.remitente_apemat,
        t.remitente_celular,
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
        t.area_destino,
        m.mov_fecharegistro AS derivado_fecha
    FROM
        tramite t
    INNER JOIN
        tipo_documento td ON t.tipodocumento_id = td.tipodocumento_id
    INNER JOIN
        area a_origen ON t.area_origen = a_origen.area_id
    INNER JOIN
        area a_destino ON t.area_destino = a_destino.area_id
    INNER JOIN
        movimiento m ON t.tramite_id = m.tramite_id
    WHERE 
        t.area_origen = IDAREA  -- Filtrar trámites derivados a esta área
        AND m.mov_estado = 'DERIVADO'  -- Solo trámites con estado 'DERIVADO' en movimiento
        AND (P_FECHAINI IS NULL OR m.mov_fecharegistro >= P_FECHAINI)  -- Si P_FECHAINI es NULL, no filtrar
        AND (P_FECHAFIN IS NULL OR m.mov_fecharegistro <= P_FECHAFIN);  -- Si P_FECHAFIN es NULL, no filtrar
END$$

CREATE DEFINER=`root`@`%` PROCEDURE `SP_LISTAR_TRAMITE_AREA_FILTRO` (IN `IDUSUARIO` INT, IN `P_ESTADO` VARCHAR(50), IN `P_FECHAINI` DATE, IN `P_FECHAFIN` DATE)   BEGIN
    DECLARE IDAREA INT;

    -- Obtener área del usuario
    SELECT area_id INTO IDAREA
    FROM usuario
    WHERE usu_id = IDUSUARIO;

    -- Consulta de trámites filtrados
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
        t.area_destino,
        (
            SELECT mov_fecharegistro
            FROM movimiento
            WHERE tramite_id = t.tramite_id AND mov_estado = 'PENDIENTE'
            ORDER BY mov_fecharegistro DESC
            LIMIT 1
        ) AS pendiente_fecha
    FROM tramite t
    INNER JOIN tipo_documento td ON t.tipodocumento_id = td.tipodocumento_id
    INNER JOIN area a_origen ON t.area_origen = a_origen.area_id
    LEFT JOIN area a_destino ON t.area_destino = a_destino.area_id
    WHERE 
        t.area_destino = IDAREA
        AND (P_ESTADO = '' OR t.tramite_estado = P_ESTADO)
        AND (P_FECHAINI IS NULL OR DATE(t.tramite_fecharegistro) >= P_FECHAINI)
        AND (P_FECHAFIN IS NULL OR DATE(t.tramite_fecharegistro) <= P_FECHAFIN)
    ORDER BY t.tramite_fecharegistro DESC;

END$$

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

CREATE DEFINER=`root`@`localhost` PROCEDURE `SP_MODIFICAR_USUARIO` (IN `p_usu_id` INT, IN `p_persona_id` INT, IN `p_area_id` INT, IN `p_usu_rol` VARCHAR(100))   BEGIN
        UPDATE usuario
        SET persona_id = p_persona_id,  -- Actualiza el ID de la persona
            area_id = p_area_id,        -- Actualiza el área del usuario
            usu_rol = p_usu_rol,        -- Actualiza el rol del usuario
            usu_fecupdate = CURRENT_TIMESTAMP  -- Actualiza la fecha de actualización
        WHERE usu_id = p_usu_id;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `SP_MODIFICAR_USUARIO_CONTRA` (IN `p_id` INT, IN `p_con` VARCHAR(255))   BEGIN
    -- Actualizar la contraseña en la base de datos
    UPDATE usuario 
    SET usu_contra = p_con
    WHERE usu_id = p_id;
    
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `SP_MODIFICAR_USUARIO_ESTATUS` (IN `p_id` INT, IN `p_estatus` ENUM('ACTIVO','INACTIVO'))   BEGIN
    -- Actualizar el estado del usuario
    UPDATE usuario 
    SET usu_estado = p_estatus
    WHERE usu_id = p_id;

END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `SP_OBTENER_ARCHIVO_TRAMITE` (IN `p_id_tramite` VARCHAR(15))   BEGIN
    SELECT tramite_archivo AS archivo
    FROM tramite
    WHERE tramite_id = p_id_tramite;
END$$

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

    -- Retornar el código generado
    SELECT cod;

    -- Insertar movimiento
    INSERT INTO movimiento (
        tramite_id, area_origen_id, area_destino_id, mov_descripcion, usu_id,
        mov_archivo, mov_estado, mov_fecharegistro
    )
    VALUES (
        cod, AREAPRINCIPAL, AREADESTINO, ASUNTO, IDUSUARIO, RUTA, 'PENDIENTE', NOW()
    );
END$$

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

CREATE DEFINER=`root`@`%` PROCEDURE `SP_REGISTRAR_TRAMITE_EXTERNO` (IN `DNI` CHAR(8), IN `NOMBRE` VARCHAR(150), IN `APEPAT` VARCHAR(50), IN `APEMAT` VARCHAR(50), IN `CEL` CHAR(9), IN `EMAIL` VARCHAR(150), IN `DIRECCION` VARCHAR(255), IN `REPRESENTACION` VARCHAR(50), IN `RUC` CHAR(12), IN `RAZON` VARCHAR(255), IN `AREAPRINCIPAL` INT, IN `AREADESTINO` INT, IN `TIPO` INT, IN `NRODOCUMENTO` VARCHAR(15), IN `ASUNTO` VARCHAR(255), IN `RUTA` VARCHAR(255), IN `FOLIO` INT, IN `IDUSUARIO` INT, IN `MONTO_PAGO` DECIMAL(10,2))   BEGIN
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
        tramite_id, remitente_dni, remitente_nombre, remitente_apepat, remitente_apemat,
        remitente_celular, remitente_email, remitente_direccion,
        tramite_doc_representacion, tramite_doc_ruc, tramite_doc_razon,
        area_origen, area_destino, tipodocumento_id,
        tramite_nrodocumento, tramite_asunto, tramite_archivo,
        tramite_folio, tramite_monto_pago
    ) VALUES (
        cod, DNI, NOMBRE, APEPAT, APEMAT, CEL, EMAIL, DIRECCION,
        REPRESENTACION, RUC, RAZON,
        AREAPRINCIPAL, AREADESTINO, TIPO,
        NRODOCUMENTO, ASUNTO, RUTA,
        FOLIO, MONTO_PAGO
    );

    -- Insertar movimiento
    INSERT INTO movimiento (
        tramite_id, area_origen_id, area_destino_id, mov_descripcion,
        usu_id, mov_archivo, mov_estado, mov_fecharegistro
    ) VALUES (
        cod, AREAPRINCIPAL, AREADESTINO, ASUNTO,
        IDUSUARIO, RUTA, 'PENDIENTE', NOW()
    );

    SELECT cod;
END$$

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

CREATE DEFINER=`root`@`localhost` PROCEDURE `SP_TRAER_WIDGET` ()   BEGIN
SELECT
(SELECT COUNT(*) FROM tramite),
(SELECT COUNT(*) FROM tramite WHERE tramite.tramite_estado='ACEPTADO'),
(SELECT COUNT(*) FROM tramite WHERE tramite.tramite_estado='PENDIENTE'),
(SELECT COUNT(*) FROM tramite WHERE tramite.tramite_estado='RECHAZADO')
FROM tramite LIMIT 1;

END$$

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
        u.usu_rol,
        CONCAT_WS(' ',p.per_nombre,p.per_apepat,p.per_apemat) as usu_persona
    FROM usuario u
    LEFT JOIN area a ON u.area_id = a.area_id
    LEFT JOIN persona p ON u.persona_id = p.persona_id
    WHERE usu_usuario = p_usu_usuario;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `SP_WIDGET_POR_AREA` (IN `IDUSUARIO` INT)   BEGIN
    DECLARE IDAREA INT;

    -- Obtener el área del usuario
    SELECT area_id INTO IDAREA
    FROM usuario
    WHERE usu_id = IDUSUARIO;

    -- Conteo de trámites filtrados por esa área como destino
    SELECT
        (SELECT COUNT(*) FROM tramite WHERE tramite.area_destino = IDAREA),
        (SELECT COUNT(*) FROM tramite WHERE tramite.area_destino = IDAREA AND tramite.tramite_estado = 'ACEPTADO'),
        (SELECT COUNT(*) FROM tramite WHERE tramite.area_destino = IDAREA AND tramite.tramite_estado = 'PENDIENTE'),
        (SELECT COUNT(*) FROM tramite WHERE tramite.area_destino = IDAREA AND tramite.tramite_estado = 'RECHAZADO');
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `area`
--

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
(17, 'MESA DE PARTES', '2025-03-14 15:50:25', 'ACTIVO'),
(18, 'DEPORTES', '2025-05-30 16:12:02', 'ACTIVO'),
(19, 'EXTERNO', '2025-06-17 16:29:42', 'ACTIVO');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `empresa`
--

CREATE TABLE `empresa` (
  `empresa_id` int NOT NULL,
  `emp_razon` varchar(250) NOT NULL,
  `emp_email` varchar(250) NOT NULL,
  `emp_cod` varchar(10) NOT NULL,
  `emp_telefono` varchar(20) NOT NULL,
  `emp_direccion` varchar(250) NOT NULL,
  `emp_logo` varchar(255) NOT NULL,
  `emp_ugel` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `empresa`
--

INSERT INTO `empresa` (`empresa_id`, `emp_razon`, `emp_email`, `emp_cod`, `emp_telefono`, `emp_direccion`, `emp_logo`, `emp_ugel`) VALUES
(1, 'IEP MIXTO SAN LUIS', 'IEP21001@gmail.com', '123', '98756412', 'San Luis', 'logo mixto.png', 'ugel-08.jpg');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `movimiento`
--

CREATE TABLE `movimiento` (
  `movimiento_id` int NOT NULL,
  `tramite_id` char(12) NOT NULL,
  `area_origen_id` int DEFAULT NULL,
  `area_destino_id` int NOT NULL,
  `mov_fecharegistro` datetime DEFAULT CURRENT_TIMESTAMP,
  `mov_descripcion` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT 'Sin descripción',
  `mov_estado` varchar(20) DEFAULT NULL,
  `usu_id` int DEFAULT NULL,
  `mov_archivo` varchar(255) DEFAULT NULL,
  `mov_descripcion_original` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `movimiento`
--

INSERT INTO `movimiento` (`movimiento_id`, `tramite_id`, `area_origen_id`, `area_destino_id`, `mov_fecharegistro`, `mov_descripcion`, `mov_estado`, `usu_id`, `mov_archivo`, `mov_descripcion_original`) VALUES
(94, 'D000001', 17, 15, '2025-06-07 17:04:06', 'NECESITO SILLAS PARA LA OFICINA DE MESA DE PARTES', 'DERIVADO', 11, 'controller/tramite/documentos/ARCH76202517776.PDF', NULL),
(95, 'D000002', 17, 8, '2025-06-07 17:05:02', 'INFORMO QUE NO HAY SILLAS PARA MESA DE PARTES', 'DERIVADO', 11, 'controller/tramite/documentos/ARCH76202517967.PDF', NULL),
(96, 'D000002', 8, 15, '2025-06-07 17:06:12', 'REVISE Y ME AUTORIZA  DESDE APOYO ESTUDIANTIL, CUANDO ANTES ESTABA EN MESA DE PARTES', 'DERIVADO', 9, NULL, NULL),
(97, 'D000001', 17, 15, '2025-06-07 17:07:54', 'YA LE NOTIFIQUé AL INTERNO SU CASO Y LE ENVIé EL DOCUMENTO CORRESPONDIENTE', 'ACEPTADO', 13, NULL, NULL),
(98, 'D000002', 8, 15, '2025-06-07 17:10:05', 'NO ME CONVENCISTE GA', 'RECHAZADO', 13, NULL, NULL),
(99, 'D000003', 15, 18, '2025-06-07 17:13:18', 'SOLICITO REPORTE DE LOS TRAMITES QUE USTED TIENE EN EL AREA DE DEPORTES', 'PENDIENTE', 13, 'controller/tramite/documentos/ARCH76202517991.PDF', NULL),
(100, 'D000004', 19, 17, '2025-06-17 16:30:20', 'SOLICITO FICHA TECNICA', 'PENDIENTE', NULL, 'controller/tramite_externo/documentos/ARCH176202511104.PDF', NULL),
(101, 'D000004', 19, 17, '2025-06-17 16:32:04', 'VOY A RECHAZAR LA SOLICITUD PORQUE DEBERIA SER SUBSANADO', 'RECHAZADO', 11, NULL, NULL),
(102, 'D000005', 19, 17, '2025-06-17 16:34:43', 'SOLICITO INFORME', 'PENDIENTE', NULL, 'controller/tramite_externo/documentos/ARCH17620251185.PDF', NULL),
(103, 'D000005', 19, 17, '2025-06-17 16:35:13', 'ACEPTO TRAMITE DESDE MESA DE PARTES', 'ACEPTADO', 11, NULL, NULL),
(104, 'D000006', 1, 6, '2025-06-17 20:30:47', 'ESTOY REALIZANDO UN TRAMITE DESDE EL AREA ADMINISTRATIVA', 'DERIVADO', 1, 'controller/tramite/documentos/ARCH176202515498.PDF', NULL),
(105, 'D000006', 6, 17, '2025-06-17 21:12:38', 'DERIVO DESDE RRH A MESA DE PARTES PARA SU REVISION', 'PENDIENTE', 10, NULL, NULL),
(106, 'D000007', 19, 17, '2025-06-17 21:18:19', 'SOLICITO TAL COSA DESDE EXTERNO', 'DERIVADO', NULL, 'controller/tramite_externo/documentos/ARCH176202516346.PDF', NULL),
(107, 'D000007', 17, 6, '2025-06-17 21:35:34', 'DERIVO DESDE MESA DE PARTES A RRHH\r\n', 'PENDIENTE', 11, NULL, NULL),
(108, 'D000003', 15, 18, '2025-06-18 02:40:16', 'DERIVO TRAMITE DE DEPORTES A MESA DE PARTES', 'ACEPTADO', 14, NULL, NULL),
(109, 'D000002', 15, 18, '2025-06-18 03:02:46', 'DERIVO DESDE DIRECCION A DEPORTES\r\n', 'PENDIENTE', 13, NULL, NULL),
(110, 'D000001', 17, 15, '2025-06-18 03:16:20', 'ACEPTO TRAMITE', 'ACEPTADO', 13, NULL, NULL),
(111, 'D000001', 15, 18, '2025-06-18 03:17:53', 'DERIVEO DESDE DIRECCIONA  DEPORTES\r\n', 'ACEPTADO', 13, NULL, NULL),
(112, 'D000001', 15, 18, '2025-06-18 03:41:01', 'ACEPTO NUEVAMENTE', 'ACEPTADO', 14, NULL, NULL),
(113, 'D000002', 15, 18, '2025-06-18 03:41:53', 'RECHAZO TRAMITE', 'RECHAZADO', 14, NULL, NULL),
(114, 'D000008', 6, 18, '2025-06-18 03:54:09', 'RECLAMO DE SILLAS PARA RRHH', 'PROCESADO', 10, 'controller/tramite/documentos/ARCH176202522780.PDF', NULL),
(115, 'D000009', 6, 18, '2025-06-17 23:12:23', '123', 'PENDIENTE', 10, 'controller/tramite/documentos/ARCH17620252315.PDF', NULL),
(116, 'D000010', 19, 17, '2025-06-18 22:16:08', 'RECLAMO TAL COSA DESDE EXTERNO', 'DERIVADO', NULL, 'controller/tramite_externo/documentos/ARCH186202522961.PDF', NULL),
(117, 'D000010', 17, 18, '2025-06-18 22:18:15', 'POR FAVOR VERIFIQUE ESTE RECLAMO (MESA DE PARTES)', 'PENDIENTE', 11, NULL, NULL),
(118, 'D000010', 17, 18, '2025-06-18 22:20:55', 'ACEPTO TRAMITE DESDE DE MESA DE PARTES DESDE DIRECCION', 'ACEPTADO', 14, NULL, NULL),
(119, 'D000011', 19, 17, '2025-06-18 22:37:10', 'SOLICITO VACANCIA', 'DERIVADO', NULL, 'controller/tramite_externo/documentos/ARCH186202522457.PDF', NULL),
(120, 'D000011', 17, 15, '2025-06-18 22:37:45', 'DICEN QEU SOLICITAN VACANCIA, VERIFICAR', 'PENDIENTE', 11, NULL, NULL),
(121, 'D000011', 17, 15, '2025-06-18 22:38:34', 'ACEPTO, YA SE LE ENIARA UNA RESOLUCION A LA PERSONA (DIRECCION)', 'ACEPTADO', 13, NULL, NULL),
(122, 'D000012', 19, 17, '2025-06-18 22:55:45', 'SOLICITO SILLAS PARA EL SALONM', 'DERIVADO', NULL, 'controller/tramite_externo/documentos/ARCH18620252259.PDF', NULL),
(123, 'D000012', 17, 15, '2025-06-18 22:57:27', 'DERIVO ED MSP A DIRECCION', 'PROCESADO', 11, NULL, NULL),
(124, 'D000012', 15, 15, '2025-06-18 23:02:16', 'ACEPTO', 'ACEPTADO', 13, NULL, NULL),
(125, 'D000013', 15, 17, '2025-06-18 23:03:35', 'HAGO TRAMITE DE DIRECCION A MESA DE PARTES', 'DERIVADO', 13, 'controller/tramite/documentos/ARCH186202523489.PDF', NULL),
(126, 'D000013', 17, 18, '2025-06-18 23:04:30', 'PROBANDO DERIVACION DE MS A DEPORTES', 'PROCESADO', 11, NULL, NULL),
(127, 'D000013', 18, 18, '2025-06-18 23:04:59', 'TE RECHAZO', 'RECHAZADO', 14, NULL, NULL),
(128, 'D000008', 18, 18, '2025-06-19 11:44:50', 'ACEPTO TRAMITE', 'ACEPTADO', 14, NULL, NULL),
(129, 'D000014', 19, 17, '2025-06-19 12:25:46', 'SOLICITO VACANTE PARA MI HIJO ', 'DERIVADO', NULL, 'controller/tramite_externo/documentos/ARCH196202512344.PDF', NULL),
(130, 'D000014', 17, 2, '2025-06-19 12:33:02', 'VERIFIQUE TRAMITE (MP)', 'PROCESADO', 11, NULL, NULL),
(131, 'D000014', 2, 2, '2025-06-19 13:03:14', 'SE ENCONTRó UNA OBSERVACIóN, POR FAVOR SUBSANE EL TRáMITE', 'RECHAZADO', 15, NULL, NULL),
(132, 'D000015', 19, 17, '2025-06-21 19:23:46', 'REGISTRO DE TRAMITE DESDE EL EXTERIOR', 'PROCESADO', NULL, 'controller/tramite_externo/documentos/ARCH216202519921.PDF', NULL),
(133, 'D000015', 17, 17, '2025-06-21 19:25:48', 'ACEPTO TRAMITE DE JUANITA', 'ACEPTADO', 11, NULL, NULL),
(134, 'D000016', 19, 17, '2025-06-22 19:01:01', 'SOLICITUD DE EMPLEO', 'DERIVADO', NULL, 'controller/tramite_externo/documentos/ARCH226202519334.PDF', NULL),
(135, 'D000016', 17, 6, '2025-06-22 21:22:00', 'REVISAR TRAMITE', 'PENDIENTE', 11, NULL, NULL),
(136, 'D000017', 19, 17, '2025-06-23 21:16:26', 'PRESENTACION DE CV', 'DERIVADO', NULL, 'controller/tramite_externo/documentos/ARCH236202521908.PDF', NULL),
(137, 'D000017', 17, 6, '2025-06-23 21:29:10', 'REVISAR CV', 'PENDIENTE', 11, NULL, NULL),
(138, 'D000018', 6, 2, '2025-06-23 21:40:32', 'SOLICITO RESOLUCION LO MAS PRONTO POSIBLE', 'PENDIENTE', 10, 'controller/tramite/documentos/ARCH236202521130.PDF', NULL),
(139, 'D000019', 8, 18, '2025-06-27 10:40:38', 'SOLICITUD DE INFORME SOBRE ESTADO DEL ENTORNO DEPORTIVO', 'DERIVADO', 9, 'controller/tramite/documentos/ARCH276202510818.PDF', NULL),
(140, 'D000019', 18, 2, '2025-06-27 10:46:05', 'VERIFICAR TRAMITE POR FAVOR', 'PROCESADO', 14, NULL, NULL),
(141, 'D000020', 19, 17, '2025-06-29 19:29:59', 'RECLAMO DESDE MESA DE PARTES VIRTUAL', 'DERIVADO', NULL, 'controller/tramite_externo/documentos/ARCH296202519723.PDF', NULL),
(142, 'D000020', 17, 2, '2025-06-29 19:31:17', 'DERIVANDO DESDE MESA DE PARTES A ACADEMICA', 'PROCESADO', 11, NULL, NULL),
(143, 'D000020', 2, 2, '2025-06-29 19:31:55', 'ACEPTO TRAMITE DESDE ACADEMICA', 'ACEPTADO', 15, NULL, NULL),
(144, 'D000019', 2, 2, '2025-06-29 19:38:59', 'RECHAZO TRAMITE', 'RECHAZADO', 15, NULL, NULL),
(145, 'D000021', 19, 17, '2025-06-29 19:42:32', 'SOLICITO UINFORME', 'PENDIENTE', NULL, 'controller/tramite_externo/documentos/ARCH296202519937.PDF', NULL),
(146, 'D000022', 17, 18, '2025-06-29 19:43:49', 'SOLICITO INFORME', 'PROCESADO', 11, 'controller/tramite/documentos/ARCH296202519851.PDF', NULL),
(147, 'D000022', 18, 18, '2025-06-29 19:49:14', 'ACEPTO TRAMITE', 'ACEPTADO', 14, NULL, NULL),
(148, 'D000023', 19, 17, '2025-06-30 09:45:11', 'SOLICITO DOCUMENTO E INFORME', 'DERIVADO', NULL, 'controller/tramite_externo/documentos/ARCH30620259819.PDF', NULL),
(149, 'D000023', 17, 2, '2025-06-30 09:47:33', 'DERIVO DESDE MESA DE PARTES  A ACADEMICA', 'PROCESADO', 11, NULL, NULL),
(150, 'D000023', 2, 2, '2025-06-30 09:49:52', 'RECHAZO TRAMITE, SUBSANAR EL TRAMITE POR FAVOR', 'RECHAZADO', 15, NULL, NULL),
(151, 'D000024', 19, 17, '2025-06-30 23:38:41', 'SOLICITO VACANCIA', 'PENDIENTE', 10, 'controller/tramite_externo/documentos/ARCH306202523824.PDF', NULL),
(152, 'D000025', 19, 17, '2025-07-01 00:36:40', 'INFORMO DESDE MPV', 'PENDIENTE', NULL, 'controller/tramite_externo/documentos/ARCH17202502.PDF', NULL),
(153, 'D000026', 19, 17, '2025-07-01 00:39:24', 'SOLICITO DESDE MESA DE PARTES UNA SILLA', 'DERIVADO', NULL, 'controller/tramite_externo/documentos/ARCH1720250449.PDF', NULL),
(154, 'D000026', 17, 2, '2025-07-01 00:40:14', 'DERIVO DESDE MP', 'PROCESADO', 11, NULL, NULL),
(155, 'D000027', 1, 2, '2025-07-01 00:41:08', 'RECLAMO DESDE ADMIN', 'PROCESADO', 1, 'controller/tramite/documentos/ARCH1720250764.PDF', NULL),
(156, 'D000027', 2, 2, '2025-07-01 00:41:57', 'ACEPTO', 'ACEPTADO', 15, NULL, NULL),
(157, 'D000028', 2, 18, '2025-07-01 00:42:49', 'SOLCIITO CAMPO DEPORTIVO', 'PENDIENTE', 15, 'controller/tramite/documentos/ARCH1720250127.PDF', NULL),
(158, 'D000026', 2, 2, '2025-07-01 00:45:11', 'RECHAZO', 'RECHAZADO', 15, NULL, NULL),
(159, 'D000029', 19, 17, '2025-07-01 08:52:10', 'SOLIITO FICHA DE CONFORMIDAD', 'DERIVADO', NULL, 'controller/tramite_externo/documentos/ARCH172025877.PDF', NULL),
(160, 'D000029', 17, 2, '2025-07-01 08:54:42', 'VERIFICAR', 'PENDIENTE', 11, NULL, NULL),
(161, 'D000030', 19, 17, '2025-07-01 17:20:59', 'SOLICITO DESDE MPV', 'PENDIENTE', NULL, 'controller/tramite_externo/documentos/ARCH17202517751.PDF', NULL),
(162, 'D000031', 19, 17, '2025-07-01 17:31:39', 'SOLICITO DESDE MESA DE PARTES UNA SILLA', 'PENDIENTE', NULL, 'controller/tramite_externo/documentos/ARCH1720251772.PDF', NULL),
(163, 'D000032', 19, 17, '2025-07-01 17:38:14', 'SOLICITO DESDE MESA DE PARTES UNA SILLA', 'PENDIENTE', NULL, 'controller/tramite_externo/documentos/ARCH17202517144.PDF', NULL),
(164, 'D000033', 17, 18, '2025-07-01 17:45:58', 'INFORMO DESDE MP', 'PENDIENTE', 11, 'controller/tramite/documentos/ARCH17202517836.PDF', NULL),
(165, 'D000034', 17, 18, '2025-07-01 17:46:36', 'INFORMO DESDE MP', 'PENDIENTE', 11, 'controller/tramite/documentos/ARCH17202517571.PDF', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `persona`
--

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
(12, 'SECRETARIO NOMBRE', 'ASDASD', 'ASDASD', '2025-05-23', '2025-04-29', '76546546', '68798465', 'ASDASD@GMAIL.COM', 'ACTIVO', 'LAURA CALLERAS', NULL),
(13, 'PEREZ', 'PEREZ', 'PEREZ', '2025-05-30', '2025-05-05', '74465465', '987654321', 'EMPLEADO@GMAIL.COM', 'ACTIVO', 'LAURA CALLER', NULL),
(14, 'TEST ACADEMICA', 'DDD', 'DDD', '2025-06-19', '2025-06-19', '79798798', '987987987', 'MARIA@GMAIL.COM', 'ACTIVO', 'LAURA CALLER', 'controller/persona/FOTO/foto_14.jpg'),
(15, 'MESA ', 'DE', ' PARTES ', '2025-06-23', '2025-06-24', '74444444', '984984984', 'MESADEPARTES@GMAIL.COM', 'ACTIVO', 'SAN LUIS - LOS CHAMOS', 'controller/persona/FOTO/foto_15.jpeg');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipo_documento`
--

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
(2, 'INFORME', 'ACTIVO', '2025-03-08 12:28:28'),
(5, 'RECLAMO', 'ACTIVO', '2025-05-30 11:11:33');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tramite`
--

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
  `area_destino` int DEFAULT NULL,
  `tramite_monto_pago` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Volcado de datos para la tabla `tramite`
--

INSERT INTO `tramite` (`tramite_id`, `remitente_dni`, `remitente_nombre`, `remitente_apepat`, `remitente_apemat`, `remitente_celular`, `remitente_email`, `remitente_direccion`, `tramite_doc_razon`, `tramite_doc_ruc`, `tramite_doc_representacion`, `tipodocumento_id`, `tramite_nrodocumento`, `tramite_folio`, `tramite_asunto`, `tramite_archivo`, `tramite_fecharegistro`, `tramite_estado`, `tramite_nroexpediente`, `area_origen`, `area_destino`, `tramite_monto_pago`) VALUES
('D000001', '78946511', 'JEFERSON', 'PERES', 'SANCHEZ', '987654321', 'EMPLEADO@GMAIL.COM', 'SAN LUIS - LOS CHAMOS', '', '', 'A NOMBRE PROPIO', 5, '001', 2, 'NECESITO SILLAS PARA LA OFICINA DE MESA DE PARTES', 'controller/tramite/documentos/ARCH76202517776.PDF', '2025-06-07 17:04:06', 'ACEPTADO', NULL, 15, 18, NULL),
('D000002', '78946511', 'JEFERSON', 'PERES', 'SANCHEZ', '987654321', 'EMPLEADO@GMAIL.COM', 'SAN LUIS - LOS CHAMOS', '', '', 'A NOMBRE PROPIO', 2, '001', 1, 'INFORMO QUE NO HAY SILLAS PARA MESA DE PARTES', 'controller/tramite/documentos/ARCH76202517967.PDF', '2025-06-07 17:05:02', 'RECHAZADO', NULL, 15, 18, NULL),
('D000003', '76546546', 'SECRETARIO NOMBRE', 'ASDASD', 'ASDASD', '68798465', 'ASDASD@GMAIL.COM', 'LAURA CALLERAS', '', '', 'A NOMBRE PROPIO', 1, '001', 2, 'SOLICITO REPORTE DE LOS TRAMITES QUE USTED TIENE EN EL AREA DE DEPORTES', 'controller/tramite/documentos/ARCH76202517991.PDF', '2025-06-07 17:13:18', 'ACEPTADO', NULL, 15, 18, NULL),
('D000004', '74432978', 'DANTE ', 'CAMPOS', 'OCHOA', '98765423', 'DANTECAMPOS669@GMAIL.COM', 'LAURA CALLER', '', '', 'NATURAL', 1, '001', 1, 'SOLICITO FICHA TECNICA', 'controller/tramite_externo/documentos/ARCH176202511104.PDF', '2025-06-17 16:30:20', 'RECHAZADO', NULL, 19, 17, NULL),
('D000005', '12312312', 'MARIA', 'PERES', 'SANCHEZ', '964890773', 'MARIA@GMAIL.COM', 'SAN LUIS - LOS CHAMOS', '', '', 'NATURAL', 1, '001', 1, 'SOLICITO INFORME', 'controller/tramite_externo/documentos/ARCH17620251185.PDF', '2025-06-17 16:34:43', 'ACEPTADO', NULL, 19, 17, NULL),
('D000006', '78956455', 'JUANITA', 'PERES', 'QUEVEDO', '987345667', 'JUANA@GMAIL.COM', 'SAN LUIS - LOS CHAMOS', '', '', 'A NOMBRE PROPIO', 1, '001', 5, 'ESTOY REALIZANDO UN TRAMITE DESDE EL AREA ADMINISTRATIVA', 'controller/tramite/documentos/ARCH176202515498.PDF', '2025-06-17 20:30:47', 'PENDIENTE', NULL, 6, 17, NULL),
('D000007', '74432978', 'JEFERSON', 'PEREZ', 'SANCHEZ', '987345667', 'EMPLEADO@GMAIL.COM', 'SAN LUIS - LOS CHAMOS', '', '', 'NATURAL', 1, '001', 2, 'SOLICITO TAL COSA DESDE EXTERNO', 'controller/tramite_externo/documentos/ARCH176202516346.PDF', '2025-06-17 21:18:19', 'PENDIENTE', NULL, 17, 6, NULL),
('D000008', '76152312', 'MARIA', 'CAMPOS', 'OCHOA', '987345667', 'MARIA@GMAIL.COM', 'LAURA CALLER', '', '', 'A NOMBRE PROPIO', 5, '001', 1, 'RECLAMO DE SILLAS PARA RRHH', 'controller/tramite/documentos/ARCH176202522780.PDF', '2025-06-18 03:54:09', 'ACEPTADO', NULL, 6, 18, NULL),
('D000009', '76152312', 'MARIA', 'CAMPOS', 'OCHOA', '987345667', 'MARIA@GMAIL.COM', 'LAURA CALLER', '', '', 'A NOMBRE PROPIO', 5, '001', 12, '123', 'controller/tramite/documentos/ARCH17620252315.PDF', '2025-06-17 23:12:23', 'PENDIENTE', NULL, 6, 18, NULL),
('D000010', '74432978', 'DANTE ', 'CAMPOS', 'OCHOA', '98765423', 'DANTECAMPOS669@GMAIL.COM', 'SAN LUIS - LOS CHAMOS', '', '', 'NATURAL', 5, '001', 2, 'RECLAMO TAL COSA DESDE EXTERNO', 'controller/tramite_externo/documentos/ARCH186202522961.PDF', '2025-06-18 22:16:08', 'ACEPTADO', NULL, 17, 18, NULL),
('D000011', '74432978', 'DANTE ', 'CAMPOS', 'OCHOA', '98765423', 'DANTECAMPOS669@GMAIL.COM', 'SAN LUIS - LOS CHAMOS', '', '', 'NATURAL', 1, '001', 2, 'SOLICITO VACANCIA', 'controller/tramite_externo/documentos/ARCH186202522457.PDF', '2025-06-18 22:37:10', 'ACEPTADO', NULL, 17, 15, NULL),
('D000012', '12312312', 'JEFERSON', 'ASDASD', 'SANCHEZ', '987345667', 'JUANA@GMAIL.COM', 'SAN LUIS - LOS CHAMOS', '', '', 'NATURAL', 1, '001', 2, 'SOLICITO SILLAS PARA EL SALONM', 'controller/tramite_externo/documentos/ARCH18620252259.PDF', '2025-06-18 22:55:45', 'ACEPTADO', NULL, 17, 15, NULL),
('D000013', '76546546', 'SECRETARIO NOMBRE', 'ASDASD', 'ASDASD', '68798465', 'ASDASD@GMAIL.COM', 'LAURA CALLERAS', '', '', 'A NOMBRE PROPIO', 5, '001', 2, 'HAGO TRAMITE DE DIRECCION A MESA DE PARTES', 'controller/tramite/documentos/ARCH186202523489.PDF', '2025-06-18 23:03:35', 'RECHAZADO', NULL, 17, 18, NULL),
('D000014', '73831093', 'DIEGO GIANFRANCO', 'VICENTE', 'GUERRA', '987654321', 'DIEGINHO669@GMAIL.COM', 'SAN LUIS - LOS CHAMOS', '', '', 'NATURAL', 1, '001', 2, 'SOLICITO VACANTE PARA MI HIJO ', 'controller/tramite_externo/documentos/ARCH196202512344.PDF', '2025-06-19 12:25:46', 'RECHAZADO', NULL, 17, 2, NULL),
('D000015', '75465465', 'JUANITA', 'PEREZ', 'ASDASD', '987345667', 'MARIA@GMAIL.COM', 'SAN LUIS - LOS CHAMOS', '', '', 'NATURAL', 5, '122', 1, 'REGISTRO DE TRAMITE DESDE EL EXTERIOR', 'controller/tramite_externo/documentos/ARCH216202519921.PDF', '2025-06-21 19:23:46', 'ACEPTADO', NULL, 19, 17, NULL),
('D000016', '76876876', 'EDUARDO', 'CANDELA', 'HUANCA', '964169846', 'EDUARDO@GMAIL.COM', 'LA QUEBRADA', 'LOS INTOCABLES', '46546546546', 'JURIDICA', 1, '2', 5, 'SOLICITUD DE EMPLEO', 'controller/tramite_externo/documentos/ARCH226202519334.PDF', '2025-06-22 19:01:01', 'PENDIENTE', NULL, 17, 6, NULL),
('D000017', '74432978', 'DANTE ', 'CAMPOS', 'OCHOA', '964890773', 'DANTECAMPOS669@GMAIL.COM', 'LAURA CALLERAS', 'DANDEV ', '13131313131', 'JURIDICA', 2, '001', 12, 'PRESENTACION DE CV', 'controller/tramite_externo/documentos/ARCH236202521908.PDF', '2025-06-23 21:16:25', 'PENDIENTE', NULL, 17, 6, NULL),
('D000018', '76152312', 'MARIA', 'CAMPOS', 'OCHOA', '987345667', 'MARIA@GMAIL.COM', 'LAURA CALLER', '', '', 'A NOMBRE PROPIO', 1, '001', 2, 'SOLICITO RESOLUCION LO MAS PRONTO POSIBLE', 'controller/tramite/documentos/ARCH236202521130.PDF', '2025-06-23 21:40:32', 'PENDIENTE', NULL, 6, 2, NULL),
('D000019', '78956455', 'JUANITA', 'PEREZ', 'QUEVEDO', '98765423', 'JUANA@GMAIL.COM', 'IMPERIAL', '', '', 'A NOMBRE PROPIO', 1, '001', 1, 'SOLICITUD DE INFORME SOBRE ESTADO DEL ENTORNO DEPORTIVO', 'controller/tramite/documentos/ARCH276202510818.PDF', '2025-06-27 10:40:38', 'RECHAZADO', NULL, 18, 2, NULL),
('D000020', '76152312', 'MARIA', 'PERES', 'SANCHEZ', '987654321', 'MARIA@GMAIL.COM', 'SAN LUIS - LOS CHAMOS', 'MARIADEV', '12312312564', 'JURIDICA', 5, '001', 12, 'RECLAMO DESDE MESA DE PARTES VIRTUAL', 'controller/tramite_externo/documentos/ARCH296202519723.PDF', '2025-06-29 19:29:59', 'ACEPTADO', NULL, 17, 2, NULL),
('D000021', '74432978', 'DANTE ', 'CAMPOS', 'OCHOA', '964890773', 'DANTECAMPOS669@GMAIL.COM', 'LAURA CALLER', '', '', 'NATURAL', 1, '001', 1, 'SOLICITO UINFORME', 'controller/tramite_externo/documentos/ARCH296202519937.PDF', '2025-06-29 19:42:32', 'PENDIENTE', NULL, 19, 17, NULL),
('D000022', '78946511', 'JEFERSON', 'PERES', 'SANCHEZ', '987654321', 'EMPLEADO@GMAIL.COM', 'SAN LUIS - LOS CHAMOS', '', '', 'A NOMBRE PROPIO', 1, '122', 1, 'SOLICITO INFORME', 'controller/tramite/documentos/ARCH296202519851.PDF', '2025-06-29 19:43:49', 'ACEPTADO', NULL, 17, 18, NULL),
('D000023', '75555656', 'TEST ACADEMICA', 'DDD', 'DDD', '987987987', 'MARIA@GMAIL.COM', 'LAURA CALLER', '', '', 'NATURAL', 1, '01', 12, 'SOLICITO DOCUMENTO E INFORME', 'controller/tramite_externo/documentos/ARCH30620259819.PDF', '2025-06-30 09:45:11', 'RECHAZADO', NULL, 17, 2, NULL),
('D000024', '79798798', 'JEFER', 'DDD', 'DDD', '987987987', 'MARIA@GMAIL.COM', 'LAURA CALLER', 'JEFER DEV', '12312312312', 'JURIDICA', 1, '1', 1, 'SOLICITO VACANCIA', 'controller/tramite_externo/documentos/ARCH306202523824.PDF', '2025-06-30 23:38:41', 'PENDIENTE', NULL, 19, 17, NULL),
('D000025', '74465465', 'JEFER', 'DDD', 'DDD', '987654321', 'MARIA@GMAIL.COM', 'LAURA CALLER', 'JEFER DEV', '65465465465', 'JURIDICA', 2, '2', 1, 'INFORMO DESDE MPV', 'controller/tramite_externo/documentos/ARCH17202502.PDF', '2025-07-01 00:36:40', 'PENDIENTE', NULL, 19, 17, NULL),
('D000026', '79798798', 'JEFER', 'DDD', 'DDD', '987987987', 'MARIA@GMAIL.COM', 'LAURA CALLER', 'JEFER DEV', '65465464665', 'JURIDICA', 2, '2', 1, 'SOLICITO DESDE MESA DE PARTES UNA SILLA', 'controller/tramite_externo/documentos/ARCH1720250449.PDF', '2025-07-01 00:39:24', 'RECHAZADO', NULL, 17, 2, NULL),
('D000027', '78946511', 'JEFERSON', 'PERES', 'SANCHEZ', '987654321', 'EMPLEADO@GMAIL.COM', 'SAN LUIS - LOS CHAMOS', '', '', 'A NOMBRE PROPIO', 5, '2', 1, 'RECLAMO DESDE ADMIN', 'controller/tramite/documentos/ARCH1720250764.PDF', '2025-07-01 00:41:08', 'ACEPTADO', NULL, 1, 2, NULL),
('D000028', '79798798', 'TEST ACADEMICA', 'DDD', 'DDD', '987987987', 'MARIA@GMAIL.COM', 'LAURA CALLER', '', '', 'A NOMBRE PROPIO', 1, '2', 1, 'SOLCIITO CAMPO DEPORTIVO', 'controller/tramite/documentos/ARCH1720250127.PDF', '2025-07-01 00:42:49', 'PENDIENTE', NULL, 2, 18, NULL),
('D000029', '74465465', 'KAYRO', 'MANRIQUE', 'DIAZ', '987987987', 'KAYRO@GMAIL.COM', 'IMPERIAL', 'KAYRODEV', '12312312564', 'JURIDICA', 1, '001', 12, 'SOLIITO FICHA DE CONFORMIDAD', 'controller/tramite_externo/documentos/ARCH172025877.PDF', '2025-07-01 08:52:10', 'PENDIENTE', NULL, 17, 2, NULL),
('D000030', '12312312', 'MARIA', 'CAMPOS', 'OCHOA', '987987987', 'JUANA@GMAIL.COM', 'SAN LUIS - LOS CHAMOS', '', '', 'NATURAL', 1, '001', 2, 'SOLICITO DESDE MPV', 'controller/tramite_externo/documentos/ARCH17202517751.PDF', '2025-07-01 17:20:59', 'PENDIENTE', NULL, 19, 17, NULL),
('D000031', '78946511', 'JEFER', 'PERES', 'SANCHEZ', '987654321', 'EMPLEADO@GMAIL.COM', 'SAN LUIS - LOS CHAMOS', 'JEFERCITO VENTAS', '98797987987', 'JURIDICA', 1, '2', 10, 'SOLICITO DESDE MESA DE PARTES UNA SILLA', 'controller/tramite_externo/documentos/ARCH1720251772.PDF', '2025-07-01 17:31:39', 'PENDIENTE', NULL, 19, 17, 10.00),
('D000032', '12312312', 'PEREZ', 'PEREZ', 'PEREZ', '987654321', 'EMPLEADO@GMAIL.COM', 'LAURA CALLER', '', '', 'NATURAL', 1, '2', 1, 'SOLICITO DESDE MESA DE PARTES UNA SILLA', 'controller/tramite_externo/documentos/ARCH17202517144.PDF', '2025-07-01 17:38:14', 'PENDIENTE', NULL, 19, 17, 10.00),
('D000033', '78946511', 'JEFERSON', 'PERES', 'SANCHEZ', '987654321', 'EMPLEADO@GMAIL.COM', 'SAN LUIS - LOS CHAMOS', '', '', 'A NOMBRE PROPIO', 2, '3', 1, 'INFORMO DESDE MP', 'controller/tramite/documentos/ARCH17202517836.PDF', '2025-07-01 17:45:58', 'PENDIENTE', NULL, 17, 18, NULL),
('D000034', '78946511', 'JEFERSON', 'PERES', 'SANCHEZ', '987654321', 'EMPLEADO@GMAIL.COM', 'SAN LUIS - LOS CHAMOS', '', '', 'A NOMBRE PROPIO', 2, '4', 1, 'INFORMO DESDE MP', 'controller/tramite/documentos/ARCH17202517571.PDF', '2025-07-01 17:46:36', 'PENDIENTE', NULL, 17, 18, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario`
--

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
(13, 'JEFF', '$2y$12$70oKPQ0UzDKEWSgA/MNnA.FJMza6tRkEVpeqGazpOLgCupvrRequq', '2025-05-23 16:40:26', '2025-05-23 16:40:26', 12, NULL, 'ACTIVO', 15, 'SECRETARIO(A)'),
(14, 'PERES', '$2y$12$2TKlGsaBNwaRtFcBhHWPsOpNJuQI0J6Phbl5Xu1FUlBKeaZJh79T.', '2025-05-30 16:04:41', '2025-06-07 22:13:58', 13, NULL, 'ACTIVO', 18, 'SECRETARIO(A)'),
(15, 'ACADEMICA', '$2y$12$DOBJUfD6B5t18HagzzmH0uZtPygeGvTK1dycIgXqQU2Sy8wLURQtO', '2025-06-19 17:37:34', '2025-06-19 17:37:34', 14, NULL, 'ACTIVO', 2, 'SECRETARIO(A)'),
(16, 'MESA', '$2y$12$IDxUz1iSlLdZxH.xsDGuOO0uWXaaO/7ozgs0ocw68zDjw26twsNdO', '2025-06-24 02:38:33', '2025-06-24 02:38:33', 15, NULL, 'ACTIVO', 17, 'SECRETARIO(A)');

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
  MODIFY `area_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT de la tabla `empresa`
--
ALTER TABLE `empresa`
  MODIFY `empresa_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `movimiento`
--
ALTER TABLE `movimiento`
  MODIFY `movimiento_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=166;

--
-- AUTO_INCREMENT de la tabla `persona`
--
ALTER TABLE `persona`
  MODIFY `persona_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT de la tabla `tipo_documento`
--
ALTER TABLE `tipo_documento`
  MODIFY `tipodocumento_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `usuario`
--
ALTER TABLE `usuario`
  MODIFY `usu_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

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
ALTER USER 'root'@'localhost' IDENTIFIED BY 'admin';
FLUSH PRIVILEGES;

-- Crea tu base de datos si no existe
CREATE DATABASE IF NOT EXISTS sis_tramite;