<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CashSession;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CashSessionController extends Controller
{
    public function index()
    {
        $sessions = CashSession::with('user')
            ->latest('opened_at')
            ->paginate(15);

        return view('admin.cash-sessions.index', compact('sessions'));
    }

    public function openForm()
    {
        return view('admin.cash-sessions.open');
    }

    public function open(Request $request)
    {
        $validated = $request->validate([
            'opening_balance' => 'required|numeric|min:0',
            'cashier_name' => 'nullable|string|max:255',
        ]);

        $existingSession = CashSession::where('user_id', Auth::id())
            ->where('status', 'open')
            ->first();

        if ($existingSession) {
            return redirect()->route('admin.pos.index')
                ->with('error', 'Anda sudah memiliki sesi kasir yang terbuka!');
        }

        $cashierName = $validated['cashier_name'] ?? session('nama_karyawan') ?? Auth::user()->name;

        CashSession::create([
            'user_id' => Auth::id(),
            'cashier_name' => $cashierName,
            'opening_balance' => $validated['opening_balance'],
            'opened_at' => now(),
            'status' => 'open',
        ]);

        return redirect()->route('admin.pos.index')
            ->with('success', 'Sesi kasir berhasil dibuka!');
    }

    public function closeForm(CashSession $cashSession)
    {
        $this->authorizeCashSession($cashSession);

        return view('admin.cash-sessions.close', compact('cashSession'));
    }

    public function close(Request $request, CashSession $cashSession)
    {
        $this->authorizeCashSession($cashSession);

        $validated = $request->validate([
            'closing_balance' => 'required|numeric|min:0',
            'note' => 'nullable|string',
        ]);

        $cashSession->update([
            'closing_balance' => $validated['closing_balance'],
            'closed_at' => now(),
            'status' => 'closed',
            'note' => $validated['note'],
        ]);

        return redirect()->route('admin.cash-sessions.index')
            ->with('success', 'Sesi kasir berhasil ditutup!');
    }

    public function show(CashSession $cashSession)
    {
        $this->authorizeCashSession($cashSession);

        $cashSession->load(['user', 'sales.items.product']);

        return view('admin.cash-sessions.show', compact('cashSession'));
    }

    public function destroy(CashSession $cashSession)
    {
        $cashSession->delete();

        return redirect()->route('admin.cash-sessions.index')
            ->with('success', 'Sesi kasir berhasil dihapus!');
    }

    private function authorizeCashSession(CashSession $cashSession)
    {
        $user = Auth::user();

        if ($user->role === 'kasir' && $cashSession->user_id !== $user->id) {
            abort(403, 'Anda tidak memiliki akses ke sesi kasir ini.');
        }
    }
}
