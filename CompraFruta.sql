

CREATE TABLE productores (
    id_productor INT PRIMARY KEY AUTO_INCREMENT,
    nombre VARCHAR(255) NOT NULL,
    identificacion VARCHAR(50) UNIQUE NOT NULL,
    telefono VARCHAR(20),
    direccion TEXT,
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
-- Resgitra los camiones que trasnportan la fruta
CREATE TABLE camiones (
    id_camion INT PRIMARY KEY AUTO_INCREMENT,
    placa VARCHAR(20) UNIQUE NOT NULL,
    id_productor INT NOT NULL,
    FOREIGN KEY (id_productor) REFERENCES productores(id_productor) ON DELETE CASCADE
);
-- Registra los pesajes en la bascula
CREATE TABLE pesajes (
    id_pesaje INT PRIMARY KEY AUTO_INCREMENT,
    id_camion INT NOT NULL,
    id_productor INT NOT NULL,
    peso_bruto DECIMAL(10,2) NOT NULL,  -- Peso con carga
    peso_tara DECIMAL(10,2),  -- Peso sin carga (después de descargar)
    peso_neto DECIMAL(10,2) GENERATED ALWAYS AS (peso_bruto - peso_tara) STORED,  -- Calculado automáticamente
    fecha_pesaje TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_camion) REFERENCES camiones(id_camion) ON DELETE CASCADE,
    FOREIGN KEY (id_productor) REFERENCES productores(id_productor) ON DELETE CASCADE
);
-- Registra los pagos realizados a los productores
CREATE TABLE pagos (
    id_pago INT PRIMARY KEY AUTO_INCREMENT,
    id_productor INT NOT NULL,
    id_pesaje INT NOT NULL,
    monto DECIMAL(10,2) NOT NULL,
    fecha_pago TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_productor) REFERENCES productores(id_productor) ON DELETE CASCADE,
    FOREIGN KEY (id_pesaje) REFERENCES pesajes(id_pesaje) ON DELETE CASCADE
);
-- Registra los préstamos otorgados a productores
CREATE TABLE prestamos (
    id_prestamo INT PRIMARY KEY AUTO_INCREMENT,
    id_productor INT NOT NULL,
    monto_prestamo DECIMAL(10,2) NOT NULL,
    saldo_pendiente DECIMAL(10,2) NOT NULL,
    fecha_prestamo DATE NOT NULL,
    FOREIGN KEY (id_productor) REFERENCES productores(id_productor) ON DELETE CASCADE
);
-- Registra los abonos de los productores a sus préstamos
CREATE TABLE abonos (
    id_abono INT PRIMARY KEY AUTO_INCREMENT,
    id_prestamo INT NOT NULL,
    monto_abono DECIMAL(10,2) NOT NULL,
    fecha_abono TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_prestamo) REFERENCES prestamos(id_prestamo) ON DELETE CASCADE
);


