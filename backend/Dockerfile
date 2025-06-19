# Usa una imagen base de Node.js
FROM node:18

# Establece el directorio de trabajo en /app dentro del contenedor
WORKDIR /app

# Copia el package.json y package-lock.json para instalar dependencias
COPY package*.json ./
RUN npm install

# Copia el resto del código fuente al contenedor
COPY . .

# Expone el puerto que usa tu backend (ajusta si no es 9000)
EXPOSE 9000

# Comando para arrancar la aplicación
CMD ["node", "src/index.js"]

