<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Sorteio Rede Artesanal</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .admin-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            padding: 24px;
            margin-bottom: 24px;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 16px;
            margin-bottom: 24px;
        }

        .stat-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px;
            border-radius: 12px;
            text-align: center;
        }

        .stat-number {
            font-size: 2rem;
            font-weight: bold;
            margin-bottom: 8px;
        }

        .reservation-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 16px;
        }

        .reservation-table th,
        .reservation-table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #e5e7eb;
        }

        .reservation-table th {
            background-color: #f9fafb;
            font-weight: bold;
            color: #374151;
        }

        .status-badge {
            padding: 4px 8px;
            border-radius: 6px;
            font-size: 12px;
            font-weight: bold;
            text-transform: uppercase;
        }

        .status-reservado {
            background-color: #fbbf24;
            color: #1f2937;
        }

        .status-pago {
            background-color: #10b981;
            color: white;
        }

        .btn-small {
            padding: 6px 12px;
            border-radius: 6px;
            font-size: 12px;
            font-weight: 500;
            border: none;
            cursor: pointer;
            transition: background-color 0.2s ease;
            margin: 2px;
        }

        .btn-success {
            background-color: #10b981;
            color: white;
        }

        .btn-success:hover {
            background-color: #059669;
        }

        .btn-warning {
            background-color: #f59e0b;
            color: white;
        }

        .btn-warning:hover {
            background-color: #d97706;
        }

        .btn-danger {
            background-color: #ef4444;
            color: white;
        }

        .btn-danger:hover {
            background-color: #dc2626;
        }

        .btn-secondary {
            background-color: #6b7280;
            color: white;
        }

        .btn-secondary:hover {
            background-color: #4b5563;
        }

        .search-box {
            width: 100%;
            padding: 12px 16px;
            border: 2px solid #d1d5db;
            border-radius: 8px;
            font-size: 16px;
            margin-bottom: 16px;
        }

        .search-box:focus {
            outline: none;
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }
    </style>
</head>
<body class="bg-gray-50 min-h-screen">
    <!-- Header -->
    <div class="bg-white shadow-sm border-b">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-6">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">🔧 Admin - Sorteio Rede Artesanal</h1>
                    <p class="text-gray-600 mt-1">Gerenciar reservas e status dos números</p>
                </div>
                <div class="flex gap-4">
                    <a href="{{ route('sorteio.index') }}" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg transition-colors">
                        📱 Ver Site
                    </a>
                    <a href="{{ route('sorteio.admin-logout') }}" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg transition-colors">
                        🚪 Sair
                    </a>
                    <button onclick="location.reload()" class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg transition-colors">
                        🔄 Atualizar
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Estatísticas -->
        <div class="admin-card">
            <h2 class="text-2xl font-bold text-gray-900 mb-4">📊 Estatísticas</h2>
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-number">{{ $stats['total'] }}</div>
                    <div>Total de Números</div>
                </div>
                <div class="stat-card" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%);">
                    <div class="stat-number">{{ $stats['disponivel'] }}</div>
                    <div>Disponíveis</div>
                </div>
                <div class="stat-card" style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);">
                    <div class="stat-number">{{ $stats['reservado'] }}</div>
                    <div>Reservados</div>
                </div>
                <div class="stat-card" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%);">
                    <div class="stat-number">{{ $stats['pago'] }}</div>
                    <div>Pagos</div>
                </div>
            </div>
        </div>

        <!-- Lista de Reservas -->
        <div class="admin-card">
            <h2 class="text-2xl font-bold text-gray-900 mb-4">📋 Reservas</h2>

            @if($reservedNumbers->count() > 0)
                <input type="text" id="searchInput" class="search-box" placeholder="🔍 Buscar por nome, telefone ou número...">

                <div class="overflow-x-auto">
                    <table class="reservation-table">
                        <thead>
                            <tr>
                                <th>Número</th>
                                <th>Nome</th>
                                <th>Telefone</th>
                                <th>Status</th>
                                <th>Data</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($reservedNumbers as $number)
                                <tr class="reservation-row" data-search="{{ strtolower($number->name . ' ' . $number->phone . ' ' . $number->number) }}">
                                    <td class="font-bold">{{ $number->number }}</td>
                                    <td>{{ $number->name }}</td>
                                    <td>{{ $number->phone }}</td>
                                    <td>
                                        <span class="status-badge status-{{ $number->status }}">
                                            {{ ucfirst($number->status) }}
                                        </span>
                                    </td>
                                    <td>{{ $number->updated_at->format('d/m/Y H:i') }}</td>
                                    <td>
                                        <form action="{{ route('sorteio.admin-update-status') }}" method="POST" style="display: inline;">
                                            @csrf
                                            <input type="hidden" name="number" value="{{ $number->number }}">
                                            <input type="hidden" name="status" value="pago">
                                            <button type="submit" class="btn-small btn-success" title="Marcar como Pago">
                                                ✅ Pago
                                            </button>
                                        </form>

                                        <form action="{{ route('sorteio.admin-update-status') }}" method="POST" style="display: inline;">
                                            @csrf
                                            <input type="hidden" name="number" value="{{ $number->number }}">
                                            <input type="hidden" name="status" value="reservado">
                                            <button type="submit" class="btn-small btn-warning" title="Marcar como Reservado">
                                                ⏳ Reservado
                                            </button>
                                        </form>

                                        <form action="{{ route('sorteio.clear-reservation') }}" method="POST" style="display: inline;">
                                            @csrf
                                            <input type="hidden" name="number" value="{{ $number->number }}">
                                            <button type="submit" class="btn-small btn-danger" title="Cancelar Reserva" onclick="return confirm('Tem certeza que deseja cancelar esta reserva?')">
                                                ❌ Cancelar
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-8">
                    <p class="text-gray-500 text-lg">Nenhuma reserva encontrada.</p>
                </div>
            @endif
        </div>
    </div>

    <script>
        // Busca em tempo real
        document.getElementById('searchInput').addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            const rows = document.querySelectorAll('.reservation-row');

            rows.forEach(row => {
                const searchText = row.getAttribute('data-search');
                if (searchText.includes(searchTerm)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });

        // Mostrar mensagens de sucesso/erro
        @if(session('success'))
            alert('{{ session('success') }}');
        @endif

        @if($errors->any())
            alert('{{ $errors->first() }}');
        @endif
    </script>
</body>
</html>
