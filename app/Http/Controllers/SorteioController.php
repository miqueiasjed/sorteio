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

        $selectedNumbers = $request->numbers;
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
}
