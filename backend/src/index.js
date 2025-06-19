require('dotenv').config(); // Carga las variables de entorno desde .env

const express = require('express');
const pool = require('./db'); // 👈 ahora lo importamos de db.js
const cors = require('cors');
const Joi = require('joi');

const app = express();

// Configurar CORS para permitir conexión desde Vercel
app.use(cors({
  origin: [
    'https://cloud-9787046lw-tomasmartins-projects.vercel.app',
    'https://cloud-sooty.vercel.app',
    'http://localhost:3000',
    'http://localhost:5000',
    'http://127.0.0.1:5500'
  ],
  credentials: true,
  methods: ['GET', 'POST', 'PUT', 'DELETE', 'OPTIONS'],
  allowedHeaders: ['Content-Type', 'Authorization']
}));

app.use(express.json());

// Configuración de PostgreSQL usando variables de entorno
const pool = new Pool({
  connectionString: process.env.DATABASE_URL,
  ssl: process.env.NODE_ENV === 'production' ? { rejectUnauthorized: false } : false
});

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
  comentarios: Joi.string().optional().allow(''),
});

// Función para manejar errores de manera centralizada
const handleError = (res, error, message = 'Error inesperado') => {
  console.error('Error:', error);
  res.status(500).json({ error: message });
};

// Ruta de prueba para verificar conexión
app.get('/api/health', async (req, res) => {
  try {
    const client = await pool.connect();
    await client.query('SELECT 1');
    client.release();
    res.json({ 
      status: 'OK', 
      message: 'Backend funcionando correctamente',
      timestamp: new Date().toISOString()
    });
  } catch (error) {
    console.error('Health check error:', error);
    res.status(500).json({ 
      status: 'ERROR', 
      message: 'Error de conexión a la base de datos',
      error: error.message 
    });
  }
});

// POST /api/reservas → Crear nueva reserva
app.post('/api/reservas', async (req, res) => {
  console.log('Datos recibidos:', req.body);
  
  // Validar los datos del formulario
  const { error } = reservaSchema.validate(req.body);
  if (error) {
    console.error('Error de validación:', error.details[0].message);
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

  const client = await pool.connect();

  try {
    await client.query('BEGIN');

    // 1. Buscar o crear usuario
    const usuarioQuery = 'SELECT id FROM usuario_cita WHERE dni = $1';
    const usuarioResult = await client.query(usuarioQuery, [dni]);

    let usuarioId;
    if (usuarioResult.rows.length > 0) {
      usuarioId = usuarioResult.rows[0].id;
      console.log('Usuario existente encontrado:', usuarioId);
    } else {
      const insertUsuarioQuery = `
        INSERT INTO usuario_cita (nombre, apellido, telefono, dni, correo) 
        VALUES ($1, $2, $3, $4, $5) 
        RETURNING id
      `;
      const insertUsuarioResult = await client.query(insertUsuarioQuery, 
        [nombre, apellido, telefono, dni, correo]);
      usuarioId = insertUsuarioResult.rows[0].id;
      console.log('Nuevo usuario creado:', usuarioId);
    }

    // 2. Crear mascota
    const insertMascotaQuery = `
      INSERT INTO mascotas (nombre, tipo, usuarioId) 
      VALUES ($1, $2, $3) 
      RETURNING id
    `;
    const insertMascotaResult = await client.query(insertMascotaQuery, 
      [nombre_mascota, tipo_mascota, usuarioId]);
    const mascotaId = insertMascotaResult.rows[0].id;
    console.log('Mascota creada:', mascotaId);

    // 3. Crear turno
    const insertTurnoQuery = `
      INSERT INTO turnos (servicio, fecha, hora, comentarios, mascotaId, usuarioId) 
      VALUES ($1, $2, $3, $4, $5, $6)
    `;
    await client.query(insertTurnoQuery, 
      [servicio, fecha, hora, comentarios || '', mascotaId, usuarioId]);

    await client.query('COMMIT');
    console.log('Reserva creada exitosamente');
    
    res.status(201).json({ 
      mensaje: 'Reserva creada con éxito',
      data: {
        usuarioId,
        mascotaId,
        fecha,
        hora,
        servicio
      }
    });
  } catch (err) {
    await client.query('ROLLBACK');
    console.error('Error en la transacción:', err);
    handleError(res, err, 'Error al crear la reserva: ' + err.message);
  } finally {
    client.release();
  }
});

// GET /api/reservas/:dni → Obtener reservas de un usuario
app.get('/api/reservas/:dni', async (req, res) => {
  const { dni } = req.params;
  console.log('Buscando reservas para DNI:', dni);

  try {
    // Buscar el usuario por DNI
    const usuarioQuery = 'SELECT id FROM usuario_cita WHERE dni = $1';
    const usuarioResult = await pool.query(usuarioQuery, [dni]);

    if (usuarioResult.rows.length === 0) {
      return res.status(404).json({ mensaje: 'Usuario no encontrado' });
    }

    const usuarioId = usuarioResult.rows[0].id;

    // Obtener las reservas del usuario
    const turnosQuery = `
      SELECT t.id, t.servicio, t.fecha, t.hora, t.comentarios,
             m.nombre AS nombre_mascota, m.tipo AS tipo_mascota
      FROM turnos t
      JOIN mascotas m ON t.mascotaId = m.id
      WHERE t.usuarioId = $1
      ORDER BY t.fecha DESC, t.hora DESC
    `;
    
    const turnosResult = await pool.query(turnosQuery, [usuarioId]);

    res.json({ 
      reservas: turnosResult.rows,
      total: turnosResult.rows.length
    });
  } catch (err) {
    console.error('Error al obtener reservas:', err);
    handleError(res, err, 'Error al obtener reservas: ' + err.message);
  }
});

// Manejo de rutas no encontradas
app.use('*', (req, res) => {
  res.status(404).json({ 
    error: 'Ruta no encontrada',
    path: req.originalUrl,
    method: req.method
  });
});

// Configuración del puerto del servidor
const PORT = process.env.PORT || 9000;
app.listen(PORT, '0.0.0.0', () => {
  console.log(`🚀 Servidor backend escuchando en puerto ${PORT}`);
  console.log(`📊 Environment: ${process.env.NODE_ENV || 'development'}`);
  console.log(`🗄️  Database: ${process.env.DATABASE_URL ? 'PostgreSQL conectado' : 'Sin configuración de DB'}`);
});
