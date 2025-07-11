DELIMITER //

CREATE PROCEDURE compras_guardar_pesaje_y_pago(
    IN p_id_pesaje INT,
    IN p_peso_tara DECIMAL(10,2),
    IN p_monto DECIMAL(10,2),
    IN p_estado INT,
    IN p_creadoPor INT
)
BEGIN
    -- Manejo de errores
    DECLARE EXIT HANDLER FOR SQLEXCEPTION
    BEGIN
        ROLLBACK;
        SIGNAL SQLSTATE '45000' 
        SET MESSAGE_TEXT = 'Error al insertar datos en compras_pesajes o compras_pagos';
    END;

    -- Inicia la transacción
    START TRANSACTION;

    -- Actualiza la tabla compras_pesajes correctamente
    UPDATE compras_pesajes 
    SET
        peso_tara = p_peso_tara,
        estado = p_estado,
        fechaModificado = NOW(),
        modificadoPor = p_creadoPor,
        peso_neto = peso_bruto - p_peso_tara
    WHERE id = p_id_pesaje;

    -- Inserta en la tabla compras_pagos
    INSERT INTO compras_pagos (id_pesaje, monto, estado, fechaCreado, creadoPor)
    VALUES (p_id_pesaje, p_monto, 1, CURRENT_TIMESTAMP, p_creadoPor);

    -- Confirma la transacción
    COMMIT;
END //

DELIMITER ;
CALL compras_guardar_pesaje_y_pago(
            28, 
            4000, 
3900,            1, 
            1)


