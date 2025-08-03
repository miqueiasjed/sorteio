# 🎁 Sorteio Rede Artesanal

Aplicação web moderna para reserva de números de sorteio, desenvolvida com Laravel, Blade e Tailwind CSS.

## 🚀 Tecnologias

- **Backend**: Laravel 12
- **Frontend**: Blade + Tailwind CSS
- **Banco de Dados**: SQLite
- **Interface**: Responsiva e moderna

## 📁 Estrutura do Projeto

```
sorteio/
├── app/Models/Number.php
├── app/Http/Controllers/SorteioController.php
├── resources/views/sorteio/index.blade.php
├── routes/web.php
├── database/migrations/
├── database/seeders/
└── database/database.sqlite
```

## ⚡ Início Rápido

### 1. Executar o projeto
```bash
./start.sh
```

### 2. Acessar a aplicação
- **URL**: http://localhost:8000

## 🔧 Funcionalidades

| Funcionalidade | Descrição |
|----------------|-----------|
| Seleção de números | Interface visual para escolher números de 1 a 200 |
| Reserva | Sistema de reserva com nome e telefone |
| Status | Controle de status (disponível, reservado, pago) |
| Validação | Validação de dados no backend |
| Interface responsiva | Funciona em desktop e mobile |

## 📊 Banco de Dados

### Tabela `numbers`
| Campo | Tipo | Descrição |
|-------|------|-----------|
| `id` | integer | ID único |
| `number` | integer | Número do sorteio (1-200) |
| `name` | string | Nome do reservador |
| `phone` | string | Telefone do reservador |
| `status` | enum | Status (disponivel/reservado/pago) |
| `created_at` | timestamp | Data de criação |
| `updated_at` | timestamp | Data de atualização |

## 🎨 Interface

- **Design**: Moderno e limpo com Tailwind CSS
- **Cores**: 
  - Cinza: Disponível
  - Azul: Selecionado
  - Amarelo: Reservado
  - Verde: Pago
- **Responsivo**: Adapta-se a diferentes tamanhos de tela
- **Animações**: Efeitos suaves de hover e seleção

## 🛠️ Desenvolvimento

### Backend
```bash
composer install
php artisan serve
```

### Frontend (desenvolvimento)
```bash
npm run dev
```

### Banco de dados
```bash
php artisan migrate:fresh --seed
```

## 🚀 Deploy

### Produção
```bash
composer install --optimize-autoloader --no-dev
php artisan config:cache
php artisan route:cache
php artisan view:cache
npm run build
```

### Variáveis de Ambiente
```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://seudominio.com
DB_CONNECTION=sqlite
```

## 📝 Logs

Os logs estão disponíveis em:
- `storage/logs/laravel.log`

## 🔄 Migração

Este projeto foi migrado de uma arquitetura complexa (Flask + React + Google Sheets) para uma solução mais simples e eficiente (Laravel + Blade + SQLite). Veja o [MIGRATION_GUIDE.md](MIGRATION_GUIDE.md) para detalhes.

## ✨ Vantagens da Nova Arquitetura

1. **Simplicidade**: Sem dependências externas (Google Sheets)
2. **Performance**: Banco de dados local é mais rápido
3. **Manutenibilidade**: Código mais limpo e organizado
4. **Escalabilidade**: Fácil de expandir funcionalidades
5. **Segurança**: Dados controlados localmente
6. **Custo**: Sem custos de APIs externas
7. **Visual**: Interface moderna e responsiva

## 📞 Suporte

Para dúvidas ou problemas, verifique:
1. Logs do Laravel (`storage/logs/laravel.log`)
2. Configuração do banco de dados
3. Permissões de arquivos
4. Dependências instaladas

---

**Desenvolvido com ❤️ para Rede Artesanal**
