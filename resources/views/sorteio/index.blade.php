<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sorteio Rede Artesanal - Reserve seu número</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .number-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(60px, 1fr));
            gap: 8px;
            max-width: 1200px;
            margin: 0 auto;
        }

        .number-button {
            width: 60px;
            height: 60px;
            border: 2px solid #d1d5db;
            border-radius: 8px;
            font-weight: bold;
            font-size: 14px;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .number-available {
            background-color: #f9fafb;
            color: #374151;
        }

        .number-available:hover {
            background-color: #e5e7eb;
            transform: scale(1.05);
        }

        .number-selected {
            background-color: #3b82f6;
            color: white;
            border-color: #3b82f6;
            transform: scale(1.05);
        }

        .number-reserved {
            background-color: #fbbf24;
            color: #1f2937;
            border-color: #fbbf24;
            cursor: not-allowed;
        }

        .number-paid {
            background-color: #10b981;
            color: white;
            border-color: #10b981;
            cursor: not-allowed;
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

        .btn-primary:hover:not(:disabled) {
            background-color: #2563eb;
        }

        .btn-primary:disabled {
            background-color: #9ca3af;
            cursor: not-allowed;
        }

        .btn-secondary {
            background-color: #10b981;
            color: white;
            padding: 8px 16px;
            border-radius: 6px;
            font-weight: 500;
            border: none;
            cursor: pointer;
            transition: background-color 0.2s ease;
        }

        .btn-secondary:hover {
            background-color: #059669;
        }

        .card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            padding: 24px;
            margin-bottom: 24px;
        }

        .legend-item {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .legend-color {
            width: 24px;
            height: 24px;
            border-radius: 4px;
            border: 2px solid #d1d5db;
        }

        @media (max-width: 768px) {
            .number-grid {
                grid-template-columns: repeat(auto-fill, minmax(50px, 1fr));
                gap: 6px;
            }

            .number-button {
                width: 50px;
                height: 50px;
                font-size: 12px;
            }
        }
    </style>
</head>
<body class="bg-gray-50 min-h-screen">
    <!-- Header -->
    <div class="bg-white shadow-sm border-b">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center py-6 gap-4">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">🎁 Sorteio Rede Artesanal</h1>
                    <p class="text-gray-600 mt-2">Reserve seus números da sorte! Números de 1 a 200 disponíveis.</p>
                </div>
                <div class="flex gap-2">
                    <a href="{{ route('sorteio.admin') }}" class="btn-secondary" style="background-color: #6b7280;">
                        🔧 Admin
                    </a>
                    <button onclick="location.reload()" class="btn-secondary">
                        🔄 Atualizar
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Números -->
        <div class="card">
            <h2 class="text-2xl font-bold text-gray-900 mb-4">Escolha seus números</h2>
            <p class="text-gray-600 mb-6">Clique nos números para selecioná-los. Números em verde já foram pagos, em amarelo estão reservados.</p>

            <div class="number-grid">
                @foreach($numbers as $number)
                    @php
                        $status = $number->status;
                        $isReserved = $status !== 'disponivel';
                        $buttonClass = 'number-button ';

                        if ($status === 'pago') {
                            $buttonClass .= 'number-paid';
                        } elseif ($status === 'reservado') {
                            $buttonClass .= 'number-reserved';
                        } else {
                            $buttonClass .= 'number-available';
                        }
                    @endphp

                    <button
                        class="{{ $buttonClass }}"
                        @if(!$isReserved)
                            onclick="toggleNumber({{ $number->number }})"
                        @endif
                        data-number="{{ $number->number }}"
                        data-status="{{ $status }}"
                    >
                        {{ $number->number }}
                    </button>
                @endforeach
            </div>
        </div>

        <!-- Formulário de Reserva -->
        <div class="card">
            <h2 class="text-2xl font-bold text-gray-900 mb-4">Seus dados</h2>
            <p class="text-gray-600 mb-6">Preencha seus dados para reservar os números selecionados</p>

            <form action="{{ route('sorteio.reserve') }}" method="POST" id="reserveForm">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Nome completo</label>
                        <input
                            type="text"
                            id="name"
                            name="name"
                            class="form-input"
                            placeholder="Digite seu nome completo"
                            required
                        >
                    </div>

                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">Telefone/WhatsApp</label>
                        <input
                            type="tel"
                            id="phone"
                            name="phone"
                            class="form-input"
                            placeholder="(11) 99999-9999"
                            required
                        >
                    </div>
                </div>

                <div class="bg-gray-50 rounded-lg p-4 mb-6">
                    <strong class="text-gray-900">Números selecionados:</strong>
                    <span id="selectedNumbers" class="text-gray-600">Nenhum</span>
                </div>

                <input type="hidden" name="numbers[]" id="numbersInput">

                <button
                    type="submit"
                    id="reserveButton"
                    class="btn-primary"
                    disabled
                >
                    Reservar números
                </button>
            </form>
        </div>

        <!-- Legenda -->
        <div class="card">
            <h3 class="text-xl font-bold text-gray-900 mb-4">Legenda</h3>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div class="legend-item">
                    <div class="legend-color bg-gray-100"></div>
                    <span class="text-gray-700">Disponível</span>
                </div>
                <div class="legend-item">
                    <div class="legend-color bg-blue-500"></div>
                    <span class="text-gray-700">Selecionado</span>
                </div>
                <div class="legend-item">
                    <div class="legend-color bg-yellow-400"></div>
                    <span class="text-gray-700">Reservado</span>
                </div>
                <div class="legend-item">
                    <div class="legend-color bg-green-500"></div>
                    <span class="text-gray-700">Pago</span>
                </div>
            </div>
        </div>
    </div>

    <script>
        let selectedNumbers = [];

        function toggleNumber(number) {
            const button = document.querySelector(`[data-number="${number}"]`);
            const status = button.getAttribute('data-status');

            if (status !== 'disponivel') return;

            if (selectedNumbers.includes(number)) {
                selectedNumbers = selectedNumbers.filter(n => n !== number);
                button.classList.remove('number-selected');
                button.classList.add('number-available');
            } else {
                selectedNumbers.push(number);
                button.classList.remove('number-available');
                button.classList.add('number-selected');
            }

            updateSelectedNumbers();
            updateReserveButton();
        }

        function updateSelectedNumbers() {
            const span = document.getElementById('selectedNumbers');
            const input = document.getElementById('numbersInput');

            if (selectedNumbers.length === 0) {
                span.textContent = 'Nenhum';
                input.value = '';
            } else {
                span.textContent = selectedNumbers.sort((a, b) => a - b).join(', ');
                // Criar múltiplos inputs para cada número
                input.innerHTML = '';
                selectedNumbers.forEach(number => {
                    const hiddenInput = document.createElement('input');
                    hiddenInput.type = 'hidden';
                    hiddenInput.name = 'numbers[]';
                    hiddenInput.value = number;
                    input.appendChild(hiddenInput);
                });
            }
        }

        function updateReserveButton() {
            const button = document.getElementById('reserveButton');
            const name = document.getElementById('name').value;
            const phone = document.getElementById('phone').value;

            const isValid = selectedNumbers.length > 0 && name.trim() && phone.trim();

            button.disabled = !isValid;
            button.textContent = isValid ? `Reservar ${selectedNumbers.length} número(s)` : 'Reservar números';
        }

        // Atualizar botão quando os campos mudarem
        document.getElementById('name').addEventListener('input', updateReserveButton);
        document.getElementById('phone').addEventListener('input', updateReserveButton);

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
