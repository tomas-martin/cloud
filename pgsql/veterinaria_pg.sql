DROP TABLE IF EXISTS historial_medico;
DROP TABLE IF EXISTS turnos;
DROP TABLE IF EXISTS mascotas;
DROP TABLE IF EXISTS usuario_cita;

CREATE TABLE usuario_cita (
  id SERIAL PRIMARY KEY,
  nombre VARCHAR(50) NOT NULL,
  apellido VARCHAR(50) NOT NULL,
  telefono VARCHAR(20) NOT NULL,
  dni VARCHAR(8) NOT NULL,
  correo VARCHAR(100) NOT NULL
);

CREATE TABLE mascotas (
  id SERIAL PRIMARY KEY,
  nombre VARCHAR(50) NOT NULL,
  tipo TEXT NOT NULL,
  usuarioId INT NOT NULL,
  CONSTRAINT fk_mascota_usuario FOREIGN KEY (usuarioId) REFERENCES usuario_cita(id)
);

CREATE TABLE historial_medico (
  id SERIAL PRIMARY KEY,
  mascotaId INT NOT NULL,
  fecha DATE NOT NULL,
  descripcion TEXT NOT NULL,
  tratamiento TEXT,
  CONSTRAINT fk_historial_mascota FOREIGN KEY (mascotaId) REFERENCES mascotas(id)
);

CREATE TABLE turnos (
  id SERIAL PRIMARY KEY,
  servicio TEXT NOT NULL,
  fecha DATE NOT NULL,
  hora TIME NOT NULL,
  comentarios TEXT,
  mascotaId INT NOT NULL,
  usuarioId INT NOT NULL,
  CONSTRAINT fk_turno_mascota FOREIGN KEY (mascotaId) REFERENCES mascotas(id),
  CONSTRAINT fk_turno_usuario FOREIGN KEY (usuarioId) REFERENCES usuario_cita(id)
);
