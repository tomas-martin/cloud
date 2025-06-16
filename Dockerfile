# Imagen base de MySQL
FROM mysql:8

# Copia los archivos SQL al directorio especial de MySQL
COPY ./sql /docker-entrypoint-initdb.d

# Establece variables de entorno (pueden sobreescribirse al correr el contenedor)
ENV MYSQL_ROOT_PASSWORD=rootpass
ENV MYSQL_DATABASE=veterinaria
