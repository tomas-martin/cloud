# Usa una imagen base de Node.js
FROM node:18

# Establece el directorio de trabajo en /app dentro del contenedor
WORKDIR /app

# Copia el package.json y package-lock.json para instalar dependencias
COPY package*.json ./
RUN npm install

# Copia todo el contenido de la carpeta 'cloud/backend' al contenedor en /app
COPY . .

# Exponer el puerto que tu aplicación usará (en este caso, 9000)
EXPOSE 9000

# Ejecuta el archivo 'index.js' ubicado en 'src/'
CMD ["node", "src/index.js"]
