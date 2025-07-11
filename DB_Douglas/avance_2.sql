INSERT INTO compras_transporte (identificador,id_Productor,id_tipo_transporte,estado)
VALUES ('HUY7656',2,3,1);

SELECT * FROM compras_productores;

INSERT INTO compras_pesajes (id_transporte,peso_bruto,peso_tara)
SELECT * FROM compras_pesajes

CREATE TABLE compras_estado (
    id INT PRIMARY KEY AUTO_INCREMENT,
    descripcion VARCHAR(255) NOT NULL,
    fechaCreado DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    fechaModificado DATETIME NULL,
    CreadoPor INT NOT NULL,
    modificadoPor INT NULL
);

INSERT INTO compras_estado (descripcion, creadoPor) VALUES ('Completado',1);
INSERT INTO compras_estado (descripcion, creadoPor) VALUES ('Descarga',1);
INSERT INTO compras_estado (descripcion, creadoPor) VALUES ('Cancelado',1);

-- CRUD DESCARGA
INSERT INTO compras_pesajes (id_transporte, peso_bruto,estado,creadoPor)
VALUES 						(1,				550,		1,		1);

UPDATE compras_pesajes 
SET peso_tara = 250,estado = 2,fechaModificado = NOW(),modificadoPor = 1, peso_neto = peso_bruto - peso_tara
WHERE id = 3;

SELECT * FROM compras_pesajes;
SELECT * FROM compras_estado;

-- CRUD PAGO
SELECT * FROM compras_pagos;

SELECT * FROM compras_transporte;
CALL compras_guardar_pesaje_y_pago(
    4,       -- p_id_pesaje
    305,   -- p_peso_tara
    2000.75,  -- p_monto
    1,       -- p_estado
    10,      -- p_creadoPor
    NOW(),-- p_fechaActual (puedes cambiarlo según necesites)
    1        -- p_estado_pago
);

-- 
SELECT * FROM compras_transporte;
SELECT * FROM contabilidad_metodosPago;

SELECT p.id,cp.nombre,t.identificador,p.fecha_pesaje,p.peso_bruto,ISNULL(p.peso_tara,'Esperando...'),
ISNULL(p.peso_neto,'Esperando...'),
ISNULL(p.estado,'Esperando...'),
cp.idProductor,
e.descripcion FROM compras_pesajes AS p
INNER JOIN compras_transporte AS t ON p.id_transporte = t.id
INNER JOIN 	compras_productores AS cp ON t.id_productor = cp.idProductor
INNER JOIN compras_estado AS e ON p.estado = e.id;

UPDATE compras_pesajes SET estado = 2 WHERE id > 1;


DROP PROCEDURE compras_guardar_pesaje_y_pago