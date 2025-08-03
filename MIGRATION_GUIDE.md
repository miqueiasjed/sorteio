# Guia de Migração: Flask para Laravel

Este documento explica a migração do backend Flask para Laravel, mantendo o frontend React original.

## Estrutura Original (Flask)

```
sorteio/
├── app.py                    # Servidor Flask
├── backend/
│   └── client_secret.json   # Credenciais Google Sheets
└── src/                     # Frontend React
    ├── App.jsx
    ├── services/sheetsService.js
    └── ...
```

## Estrutura Nova (Laravel)

```
sorteio/
├── laravel-backend/         # Backend Laravel
│   ├── app/Http/Controllers/SheetsController.php
│   ├── app/Http/Middleware/ServeFrontend.php
│   ├── routes/api.php
│   ├── public/              # Frontend React copiado
│   │   ├── index.html
│   │   └── src/
│   └── storage/app/client_secret.json
└── src/                     # Frontend original (mantido)
```

## Principais Mudanças

### 1. APIs Convertidas

**Flask (app.py):**
```python
@app.route('/api/sheets/data', methods=['GET'])
def get_sheet_data():
    # Lógica para buscar dados

@app.route('/api/sheets/reserve', methods=['POST'])
def reserve_numbers():
    # Lógica para reservar números

@app.route('/api/sheets/update-status', methods=['POST'])
def update_status():
    # Lógica para atualizar status
```

**Laravel (SheetsController.php):**
```php
public function getData(): JsonResponse
{
    // Mesma lógica, mas com validação Laravel
}

public function reserve(Request $request): JsonResponse
{
    // Validação automática dos dados
    $data = $request->validate([
        'numbers' => 'required|array',
        'name' => 'required|string',
        'phone' => 'required|string',
    ]);
}

public function updateStatus(Request $request): JsonResponse
{
    // Validação e tratamento de erros melhorados
}
```

### 2. Rotas

**Flask:**
```python
@app.route('/api/sheets/data', methods=['GET'])
@app.route('/api/sheets/reserve', methods=['POST'])
@app.route('/api/sheets/update-status', methods=['POST'])
```

**Laravel (routes/api.php):**
```php
Route::prefix('sheets')->group(function () {
    Route::get('/data', [SheetsController::class, 'getData']);
    Route::post('/reserve', [SheetsController::class, 'reserve']);
    Route::post('/update-status', [SheetsController::class, 'updateStatus']);
});
```

### 3. Servir Frontend

**Flask:**
```python
@app.route('/')
def serve_frontend():
    return send_from_directory(app.static_folder, 'index.html')
```

**Laravel:**
- Middleware `ServeFrontend` que serve o `public/index.html`
- Frontend React copiado para `public/`

### 4. CORS

**Flask:**
```python
from flask_cors import CORS
CORS(app)
```

**Laravel:**
- Configurado em `config/cors.php`
- Mais seguro e configurável

## Vantagens da Migração

1. **Validação Robusta**: Laravel oferece validação automática de dados
2. **Estrutura Organizada**: Controllers, Middleware, Routes separados
3. **Logs Melhorados**: Sistema de logs integrado
4. **Segurança**: CORS configurado adequadamente
5. **Manutenibilidade**: Código mais limpo e organizado
6. **Escalabilidade**: Laravel oferece mais recursos para crescimento

## Como Executar

### Backend Laravel
```bash
cd laravel-backend
./start.sh
```

### Frontend Original (se necessário)
```bash
npm run dev
```

## APIs Disponíveis

Todas as APIs mantêm a mesma interface:

- `GET /api/sheets/data` - Buscar dados da planilha
- `POST /api/sheets/reserve` - Reservar números
- `POST /api/sheets/update-status` - Atualizar status

## Configuração Necessária

1. **Credenciais Google Sheets**: Copiar `client_secret.json` para `laravel-backend/storage/app/`
2. **Arquivo .env**: Configurar variáveis de ambiente
3. **Dependências**: `composer install` no Laravel

## Compatibilidade

- ✅ Frontend React mantido 100% compatível
- ✅ APIs mantêm a mesma interface
- ✅ Integração Google Sheets preservada
- ✅ Funcionalidades idênticas

## Próximos Passos

1. Testar todas as funcionalidades
2. Configurar ambiente de produção
3. Implementar cache se necessário
4. Adicionar autenticação se necessário
5. Configurar logs de produção 