#!/bin/bash

echo "🚀 Iniciando Sorteio Rede Artesanal..."

# Verificar se as dependências estão instaladas
if [ ! -d "vendor" ]; then
    echo "📦 Instalando dependências PHP..."
    composer install
fi

if [ ! -d "node_modules" ]; then
    echo "📦 Instalando dependências Node.js..."
    npm install
fi

# Verificar se o .env existe
if [ ! -f ".env" ]; then
    echo "📝 Copiando arquivo .env.example para .env..."
    cp .env.example .env
    php artisan key:generate
fi

# Verificar se o banco de dados existe
if [ ! -f "database/database.sqlite" ]; then
    echo "🗄️ Criando banco de dados..."
    touch database/database.sqlite
fi

# Executar migrações se necessário
echo "🔄 Verificando migrações..."
php artisan migrate --force

# Verificar se os números foram criados
if [ ! -f "database/seeded" ]; then
    echo "🌱 Populando banco de dados..."
    php artisan db:seed
    touch database/seeded
fi

# Compilar assets
echo "🎨 Compilando assets..."
npm run build

echo "✅ Configuração concluída!"
echo ""
echo "🌐 Servidor iniciando em http://localhost:8000"
echo "📱 Interface moderna com Tailwind CSS"
echo "🗄️ Banco de dados SQLite"
echo "✨ Funcionalidades:"
echo "   - Seleção de números 1-200"
echo "   - Reserva com nome e telefone"
echo "   - Status: disponível/reservado/pago"
echo "   - Interface responsiva"

# Iniciar servidor
php artisan serve --host=0.0.0.0 --port=8000
