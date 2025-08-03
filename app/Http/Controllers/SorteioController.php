<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Number;

class SorteioController extends Controller
{
    public function index()
    {
        $numbers = Number::orderBy('number')->get();
        $reservedNumbers = $numbers->where('status', '!=', 'disponivel')->keyBy('number');

        return view('sorteio.index', compact('numbers', 'reservedNumbers'));
    }

    public function reserve(Request $request)
    {
        $request->validate([
            'numbers' => 'required|array',
            'numbers.*' => 'integer|min:1|max:200',
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
        ]);

        $selectedNumbers = array_map('intval', $request->numbers);
        $name = $request->name;
        $phone = $request->phone;

        // Verificar se os números estão disponíveis
        $unavailableNumbers = Number::whereIn('number', $selectedNumbers)
            ->where('status', '!=', 'disponivel')
            ->pluck('number')
            ->toArray();

        if (!empty($unavailableNumbers)) {
            return back()->withErrors([
                'numbers' => 'Os números ' . implode(', ', $unavailableNumbers) . ' já estão reservados.'
            ])->withInput();
        }

        // Reservar os números
        Number::whereIn('number', $selectedNumbers)->update([
            'name' => $name,
            'phone' => $phone,
            'status' => 'reservado'
        ]);

        return back()->with('success', 'Números reservados com sucesso!');
    }

    public function updateStatus(Request $request)
    {
        $request->validate([
            'number' => 'required|integer|min:1|max:200',
            'status' => 'required|in:disponivel,reservado,pago'
        ]);

        $number = Number::where('number', $request->number)->first();

        if (!$number) {
            return back()->withErrors(['number' => 'Número não encontrado.']);
        }

        $number->update(['status' => $request->status]);

        return back()->with('success', 'Status atualizado com sucesso!');
    }

    public function getNumbers()
    {
        $reservedNumbers = Number::where('status', '!=', 'disponivel')
            ->get()
            ->keyBy('number')
            ->map(function ($number) {
                return [
                    'name' => $number->name,
                    'phone' => $number->phone,
                    'status' => $number->status
                ];
            });

        return response()->json(['reservedNumbers' => $reservedNumbers]);
    }

    // Nova função para área administrativa
    public function admin(Request $request)
    {
        // Verificar se já está autenticado
        if ($request->session()->has('admin_authenticated')) {
            $reservedNumbers = Number::where('status', '!=', 'disponivel')
                ->orderBy('number')
                ->get();

            $stats = [
                'total' => Number::count(),
                'disponivel' => Number::where('status', 'disponivel')->count(),
                'reservado' => Number::where('status', 'reservado')->count(),
                'pago' => Number::where('status', 'pago')->count(),
            ];

            return view('sorteio.admin', compact('reservedNumbers', 'stats'));
        }

        // Se não está autenticado, mostrar página de login
        return view('sorteio.admin-login');
    }

    // Função para autenticar admin
    public function adminLogin(Request $request)
    {
        $request->validate([
            'password' => 'required|string'
        ]);

        if ($request->password === 'fic201301') {
            $request->session()->put('admin_authenticated', true);
            return redirect()->route('sorteio.admin');
        }

        return back()->withErrors(['password' => 'Senha incorreta.']);
    }

    // Função para logout
    public function adminLogout(Request $request)
    {
        $request->session()->forget('admin_authenticated');
        return redirect()->route('sorteio.index');
    }

    // Função para atualizar status via admin
    public function adminUpdateStatus(Request $request)
    {
        // Verificar autenticação
        if (!$request->session()->has('admin_authenticated')) {
            return redirect()->route('sorteio.admin');
        }

        $request->validate([
            'number' => 'required|integer|min:1|max:200',
            'status' => 'required|in:disponivel,reservado,pago'
        ]);

        $number = Number::where('number', $request->number)->first();

        if (!$number) {
            return back()->withErrors(['number' => 'Número não encontrado.']);
        }

        $oldStatus = $number->status;
        $number->update(['status' => $request->status]);

        return back()->with('success', "Status do número {$number->number} alterado de '{$oldStatus}' para '{$request->status}'");
    }

    // Função para limpar reserva (devolver para disponível)
    public function clearReservation(Request $request)
    {
        // Verificar autenticação
        if (!$request->session()->has('admin_authenticated')) {
            return redirect()->route('sorteio.admin');
        }

        $request->validate([
            'number' => 'required|integer|min:1|max:200'
        ]);

        $number = Number::where('number', $request->number)->first();

        if (!$number) {
            return back()->withErrors(['number' => 'Número não encontrado.']);
        }

        $number->update([
            'name' => null,
            'phone' => null,
            'status' => 'disponivel'
        ]);

        return back()->with('success', "Reserva do número {$number->number} foi cancelada");
    }
}
