<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CashSession;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class EmployeeController extends Controller
{
    public function index()
    {
        $employees = User::select('users.*')->addSelect([
            'cash_sessions_count' => CashSession::selectRaw('count(*)')
                ->whereColumn('user_id', 'users.id'),
            'total_omset' => CashSession::selectRaw('coalesce(sum(total_sales), 0)')
                ->whereColumn('user_id', 'users.id'),
        ])->latest()->paginate(10);

        return view('admin.employees.index', compact('employees'));
    }

    public function create()
    {
        return view('admin.employees.create');
    }

    public function store(Request $request)
    {
        $dataValid = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'nullable|string|max:20',
            'role' => ['required', Rule::in(['admin', 'kasir'])],
            'password' => 'required|string|min:6',
        ]);

        $dataValid['is_active'] = true;
        $dataValid['password'] = Hash::make($request->password);

        User::create($dataValid);

        return redirect()->route('admin.employees.index')
            ->with('success', 'Karyawan baru berhasil ditambahkan!');
    }

    public function toggleStatus(User $employee)
    {
        if ($employee->id === Auth::id()) {
            return back()->with('error', 'Anda tidak dapat me-nonaktifkan akun sendiri!');
        }

        $employee->update(['is_active' => ! $employee->is_active]);
        $teks = $employee->is_active ? 'diaktifkan' : 'dinonaktifkan';

        return back()->with('success', "Akun karyawan {$employee->name} berhasil {$teks}.");
    }

    public function show(User $employee)
    {
        $sessions = CashSession::where('user_id', $employee->id)
            ->latest('opened_at')
            ->paginate(10);

        $totalSales = CashSession::where('user_id', $employee->id)
            ->sum('total_sales');

        $totalSessions = CashSession::where('user_id', $employee->id)
            ->count();

        return view('admin.employees.show', compact(
            'employee',
            'sessions',
            'totalSales',
            'totalSessions'
        ));
    }

    public function edit(User $employee)
    {
        return view('admin.employees.edit', compact('employee'));
    }

    public function update(Request $request, User $employee)
    {
        $dataValid = $request->validate([
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users')->ignore($employee->id),
            ],
            'phone' => 'nullable|string|max:20',
            'role' => ['required', Rule::in(['admin', 'kasir'])],
        ]);

        if ($request->filled('password')) {
            $request->validate(['password' => 'string|min:6']);
            $dataValid['password'] = Hash::make($request->password);
        }

        $employee->update($dataValid);

        return redirect()->route('admin.employees.index')
            ->with('success', 'Data karyawan berhasil diperbarui!');
    }

    public function destroy(User $employee)
    {
        if ($employee->id === Auth::id()) {
            return back()->with('error', 'Anda tidak bisa menghapus akun sendiri!');
        }

        if (CashSession::where('user_id', $employee->id)->exists()) {
            return back()->with(
                'error',
                "Karyawan {$employee->name} tidak dapat dihapus karena punya riwayat. Ubah ke Non-Aktif!"
            );
        }

        $employee->delete();

        return redirect()->route('admin.employees.index')
            ->with('success', 'Karyawan berhasil dihapus!');
    }
}
