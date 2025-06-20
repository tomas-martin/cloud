FROM node:18

WORKDIR /app

# Copiás los archivos package.json y package-lock.json que están en la carpeta backend/
COPY package*.json ./

RUN npm install

# Copiás todo el contenido de la carpeta backend/ (que es el contexto actual)
COPY . .

EXPOSE 9000

CMD ["node", "src/index.js"]

