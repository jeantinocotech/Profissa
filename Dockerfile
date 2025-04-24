# Etapa 1: construir frontend com Vite
FROM node:18-alpine as frontend

WORKDIR /app
COPY package*.json ./
RUN npm install
COPY . .
RUN npm run build

# Etapa 2: preparar PHP + Apache com Laravel
FROM webdevops/php-apache:8.2-alpine

WORKDIR /app

# Copia os arquivos da build do frontend e Laravel
COPY --from=frontend /app /app

# Instala dependências PHP
RUN composer install --no-dev --optimize-autoloader \
    && chown -R application:application /app \
    && chmod -R 755 /app/storage /app/bootstrap/cache

# Porta padrão
EXPOSE 80

