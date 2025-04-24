# Etapa 1: build do frontend com Vite
FROM node:18-alpine as frontend

WORKDIR /app
COPY package*.json ./
RUN npm install
COPY . .
RUN npm run build

# Etapa 2: build da aplicação Laravel com PHP
FROM webdevops/php-apache:8.2-alpine

WORKDIR /app

# Instalar dependências do Laravel
COPY --from=frontend /app /app
RUN composer install --no-dev --optimize-autoloader

# Expor a porta correta
EXPOSE 80
