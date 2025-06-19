require('dotenv').config(); // Carga las variables de entorno desde .env

const express = require('express');
const mysql = require('mysql2/promise');
const cors = require('cors');
const Joi = require('joi');

const app = express();

// Configurar CORS para permitir conexión desde Vercel
app.use(cors({
  origin: ['https://tu-dominio-vercel.vercel.app', 'http://localhost:3000'],
  credentials: true
}));

app.use(express.json());

// Configuración de la base de datos usando variables de entorno
const dbConfig = {
  host: process.env.DB_HOST || 'mysql',
  user: process.env.DB_USER || 'root',
  password: process.env.DB_PASSWORD || 'root',
  database: process.env.DB_NAME || 'veterinaria',
};

// Crear un pool de conexiones para mejorar el rendimiento
const pool = mysql.createPool(dbConfig);

// Esquema de validación de datos con Joi
const reservaSchema = Joi.object({
  nombre: Joi.string().required(),
  apellido: Joi.string().required(),
  telefono: Joi.string().required(),
  correo: Joi.string().email().required(),
  dni: Joi.string().required(),
  nombre_mascota: Joi.string().required(),
  tipo_mascota: Joi.string().required(),
  servicio: Joi.string().required(),
  fecha: Joi.date().required(),
  hora: Joi.string().required(),
  comentarios: Joi.string().optional(),
});

// Función para manejar errores de manera centralizada
const handleError = (res, error, message = 'Error inesperado') => {
  console.error(error); // Imprime el error en la consola
  res.status(500).json({ error: message });
};

// Ruta de prueba para verificar conexión
app.get('/api/health', async (req, res) => {
  try {
    const connection = await pool.getConnection();
    await connection.execute('SELECT 1');
    connection.release();
    res.json({ status: 'OK', message: 'Backend funcionando correctamente' });
  } catch (error) {
    res.status(500).json({ status: 'ERROR', message: 'Error de conexión a la base de datos' });
  }
});

// POST /api/reservas → Crear nueva reserva
app.post('/api/reservas', async (req, res) => {
  // Validar los datos del formulario
  const { error } = reservaSchema.validate(req.body);
  if (error) {
    return res.status(400).json({ error: 'Datos inválidos: ' + error.details[0].message });
  }

  const {
    nombre,
    apellido,
    telefono,
    correo,
    dni,
    nombre_mascota,
    tipo_mascota,
    servicio,
    fecha,
    hora,
    comentarios,
  } = req.body;

  let connection;

  try {
    // Obtener una conexión del pool
    connection = await pool.getConnection();
    await connection.beginTransaction(); // Iniciar la transacción

    // 1. Buscar o crear usuario
    const [usuarios] = await connection.execute(
      'SELECT id FROM usuario_cita WHERE dni = ?',
      [dni]
    );

    let usuarioId;
    if (usuarios.length > 0) {
      usuarioId = usuarios[0].id;
    } else {
      const [insertUsuario] = await connection.execute(
        'INSERT INTO usuario_cita (nombre, apellido, telefono, dni, correo) VALUES (?, ?, ?, ?, ?)',
        [nombre, apellido, telefono, dni, correo]
      );
      usuarioId = insertUsuario.insertId;
    }

    // 2. Crear mascota
    const [insertMascota] = await connection.execute(
      'INSERT INTO mascotas (nombre, tipo, usuarioId) VALUES (?, ?, ?)',
      [nombre_mascota, tipo_mascota, usuarioId]
    );
    const mascotaId = insertMascota.insertId;

    // 3. Crear turno
    await connection.execute(
      'INSERT INTO turnos (servicio, fecha, hora, comentarios, mascotaId, usuarioId) VALUES (?, ?, ?, ?, ?, ?)',
      [servicio, fecha, hora, comentarios, mascotaId, usuarioId]
    );

    await connection.commit(); // Confirmar la transacción
    res.status(201).json({ mensaje: 'Reserva creada con éxito' });
  } catch (err) {
    if (connection) {
      await connection.rollback(); // Deshacer todo si ocurre un error
    }
    handleError(res, err, 'Error al crear la reserva');
  } finally {
    if (connection) {
      connection.release(); // Liberar la conexión para que otro cliente la use
    }
  }
});

// GET /api/reservas/:dni → Obtener reservas de un usuario
app.get('/api/reservas/:dni', async (req, res) => {
  const { dni } = req.params;

  let connection;

  try {
    // Obtener una conexión del pool
    connection = await pool.getConnection();

    // Buscar el usuario por DNI
    const [usuarios] = await connection.execute(
      'SELECT id FROM usuario_cita WHERE dni = ?',
      [dni]
    );

    if (usuarios.length === 0) {
      return res.status(404).json({ mensaje: 'Usuario no encontrado' });
    }

    const usuarioId = usuarios[0].id;

    // Obtener las reservas del usuario
    const [turnos] = await connection.execute(
      `SELECT t.id, t.servicio, t.fecha, t.hora, t.comentarios,
              m.nombre AS nombre_mascota, m.tipo AS tipo_mascota
         FROM turnos t
         JOIN mascotas m ON t.mascotaId = m.id
        WHERE t.usuarioId = ?
        ORDER BY t.fecha DESC, t.hora DESC`,
      [usuarioId]
    );

    res.json({ reservas: turnos });
  } catch (err) {
    handleError(res, err, 'Error al obtener reservas');
  } finally {
    if (connection) {
      connection.release(); // Liberar la conexión
    }
  }
});

// Configuración del puerto del servidor
const PORT = process.env.PORT || 9000;
app.listen(PORT, () => {
  console.log(`Servidor backend escuchando en puerto ${PORT}`);
});

