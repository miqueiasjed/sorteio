<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - Sorteio Rede Artesanal</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .login-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            padding: 32px;
            max-width: 400px;
            width: 100%;
        }

        .form-input {
            width: 100%;
            padding: 12px 16px;
            border: 2px solid #d1d5db;
            border-radius: 8px;
            font-size: 16px;
            transition: border-color 0.2s ease;
        }

        .form-input:focus {
            outline: none;
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }

        .btn-primary {
            background-color: #3b82f6;
            color: white;
            padding: 12px 24px;
            border-radius: 8px;
            font-weight: bold;
            font-size: 16px;
            border: none;
            cursor: pointer;
            transition: background-color 0.2s ease;
            width: 100%;
        }

        .btn-primary:hover {
            background-color: #2563eb;
        }

        .btn-secondary {
            background-color: #6b7280;
            color: white;
            padding: 8px 16px;
            border-radius: 6px;
            font-weight: 500;
            border: none;
            cursor: pointer;
            transition: background-color 0.2s ease;
        }

        .btn-secondary:hover {
            background-color: #4b5563;
        }
    </style>
</head>
<body class="bg-gray-50 min-h-screen flex items-center justify-center">
    <div class="w-full max-w-md">
        <!-- Header -->
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-gray-900">🔧 Admin</h1>
            <p class="text-gray-600 mt-2">Sorteio Rede Artesanal</p>
        </div>

        <!-- Login Card -->
        <div class="login-card">
            <h2 class="text-2xl font-bold text-gray-900 mb-6 text-center">Acesso Administrativo</h2>

            <form action="{{ route('sorteio.admin-login') }}" method="POST">
                @csrf

                <div class="mb-6">
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Senha</label>
                    <input
                        type="password"
                        id="password"
                        name="password"
                        class="form-input"
                        placeholder="Digite a senha"
                        required
                        autofocus
                    >
                </div>

                @if($errors->any())
                    <div class="mb-4 p-3 bg-red-100 border border-red-400 text-red-700 rounded">
                        {{ $errors->first() }}
                    </div>
                @endif

                <button type="submit" class="btn-primary mb-4">
                    🔐 Entrar
                </button>
            </form>

            <div class="text-center">
                <a href="{{ route('sorteio.index') }}" class="btn-secondary">
                    ← Voltar ao Site
                </a>
            </div>
        </div>
    </div>
</body>
</html>
