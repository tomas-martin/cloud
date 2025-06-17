const express = require('express');
const mysql = require('mysql2/promise');
const cors = require('cors');

const app = express();
app.use(cors());
app.use(express.json());

const dbConfig = {
  host: process.env.DB_HOST || 'localhost',
  user: process.env.DB_USER || 'root',
  password: process.env.DB_PASSWORD || 'root',
  database: process.env.DB_NAME || 'veterinaria',
};

// POST /api/reservas → Crear nueva reserva
app.post('/api/reservas', async (req, res) => {
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

  try {
    const connection = await mysql.createConnection(dbConfig);

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

    await connection.end();
    res.status(201).json({ mensaje: 'Reserva creada con éxito' });
  } catch (err) {
    console.error(err);
    res.status(500).json({ error: 'Error al crear la reserva' });
  }
});

// GET /api/reservas/:dni → Obtener reservas de un usuario
app.get('/api/reservas/:dni', async (req, res) => {
  const { dni } = req.params;

  try {
    const connection = await mysql.createConnection(dbConfig);

    const [usuarios] = await connection.execute(
      'SELECT id FROM usuario_cita WHERE dni = ?',
      [dni]
    );
    if (usuarios.length === 0) {
      await connection.end();
      return res.status(404).json({ mensaje: 'Usuario no encontrado' });
    }

    const usuarioId = usuarios[0].id;

    const [turnos] = await connection.execute(
      `SELECT t.id, t.servicio, t.fecha, t.hora, t.comentarios,
              m.nombre AS nombre_mascota, m.tipo AS tipo_mascota
         FROM turnos t
         JOIN mascotas m ON t.mascotaId = m.id
        WHERE t.usuarioId = ?
        ORDER BY t.fecha DESC, t.hora DESC`,
      [usuarioId]
    );

    await connection.end();
    res.json({ reservas: turnos });
  } catch (err) {
    console.error(err);
    res.status(500).json({ error: 'Error al obtener reservas' });
  }
});

const PORT = process.env.PORT || 9000;
app.listen(PORT, () => {
  console.log(`Servidor backend escuchando en puerto ${PORT}`);
});
