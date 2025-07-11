CREATE TABLE compras_productores (
    id_productor INT PRIMARY KEY AUTO_INCREMENT,
    nombre VARCHAR(255) NOT NULL,
    identificacion VARCHAR(50) UNIQUE NOT NULL,
    telefono VARCHAR(20),
    direccion TEXT,
    estado INT NOT NULL,
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE compras_camiones (
    id_camion INT PRIMARY KEY AUTO_INCREMENT,
    placa VARCHAR(20) UNIQUE NOT NULL,
    id_productor INT NOT NULL,
    estado INT NOT NULL,
    FOREIGN KEY (id_productor) REFERENCES compras_productores(id_productor) ON DELETE CASCADE
);

CREATE TABLE compras_pesajes (
    id_pesaje INT PRIMARY KEY AUTO_INCREMENT,
    id_camion INT NOT NULL,
    peso_bruto DECIMAL(10,2) NOT NULL,  -- Peso con carga
    peso_tara DECIMAL(10,2),  -- Peso sin carga (después de descargar)
    peso_neto DECIMAL(10,2) GENERATED ALWAYS AS (peso_bruto - peso_tara) STORED,  -- Calculado automáticamente
    fecha_pesaje TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    estado INT NOT NULL,
    FOREIGN KEY (id_camion) REFERENCES compras_camiones(id_camion) ON DELETE CASCADE
);

CREATE TABLE compras_pagos (
    id_pago INT PRIMARY KEY AUTO_INCREMENT,
    id_pesaje INT NOT NULL,
    monto DECIMAL(10,2) NOT NULL,
    fecha_pago TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    estado INT NOT NULL,
    FOREIGN KEY (id_pesaje) REFERENCES compras_pesajes(id_pesaje) ON DELETE CASCADE
);

CREATE TABLE compras_prestamos (
    id_prestamo INT PRIMARY KEY AUTO_INCREMENT,
    id_productor INT NOT NULL,
    monto_prestamo DECIMAL(10,2) NOT NULL,
    fecha_prestamo DATE NOT NULL,
    estado INT NOT NULL,
    FOREIGN KEY (id_productor) REFERENCES compras_productores(id_productor) ON DELETE CASCADE
);

CREATE TABLE compras_abonos (
    id_abono INT PRIMARY KEY AUTO_INCREMENT,
    id_prestamo INT NOT NULL,
    monto_abono DECIMAL(10,2) NOT NULL,
    fecha_abono TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    estado INT NOT NULL,
    FOREIGN KEY (id_prestamo) REFERENCES compras_prestamos(id_prestamo) ON DELETE CASCADE
);

ALTER TABLE compras_abonos ADD CONSTRAINT chk_abono_valido 
CHECK (monto_abono > 0);

ALTER TABLE compras_pesajes ADD CONSTRAINT chk_pesaje_valido 
CHECK (peso_tara >= 0 AND peso_tara <= peso_bruto);


