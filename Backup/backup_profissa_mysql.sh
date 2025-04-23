#!/bin/bash

# CONFIGURAÇÕES DO BANCO
DB_NAME="my_herd_app"
DB_USER="root"
DB_PASS="" # ⚠️ Você pode remover essa linha e digitar a senha ao executar
BACKUP_DIR="$HOME/Herd/Profissa/Backup/"
DATE=$(date +"%Y-%m-%d_%H-%M")
FILE_NAME="${DB_NAME}_backup_${DATE}.sql"

# CAMINHO DO MYSQLDUMP (verifique o caminho correto do seu MySQL)
MYSQLDUMP="/opt/homebrew/opt/mysql@8.0/bin/mysqldump"

# CRIA O BACKUP
$MYSQLDUMP -u $DB_USER -p$DB_PASS $DB_NAME > "$BACKUP_DIR/$FILE_NAME"

# REMOVE BACKUPS COM MAIS DE 7 DIAS (opcional)
find "$BACKUP_DIR" -name "${DB_NAME}_backup_*.sql" -type f -mtime +7 -delete

