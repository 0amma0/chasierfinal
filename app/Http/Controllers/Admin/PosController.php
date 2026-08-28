<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CashSession;
use App\Models\Member;
use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PosController extends Controller
{
    public function index(Request $request)
    {
        $sesiAktif = CashSession::where('user_id', Auth::id())
            ->where('status', 'open')
            ->first();

        if (! $sesiAktif) {
            return redirect()->route('admin.cash-sessions.open-form')
                ->with('error', 'Silakan buka sesi kasir terlebih dahulu sebelum melakukan transaksi!');
        }

        $pencarianProduk = Product::query();

        if ($request->filled('search')) {
            $kataKunci = $request->search;
            $pencarianProduk->where(function ($query) use ($kataKunci) {
                $query->where('name', 'like', "%{$kataKunci}%")
                    ->orWhere('barcode', $kataKunci);
            });
        }

        $produkTersedia = $pencarianProduk
            ->where('stock', '>', 0)
            ->where('is_active', true)
            ->get();

        return view('admin.pos.index', [
            'activeSession' => $sesiAktif,
            'products' => $produkTersedia,
        ]);
    }

    public function checkMember(Request $request)
    {
        $nomorHp = trim($request->query('phone'));
        $dataMember = Member::where('phone', $nomorHp)
            ->where('is_active', true)
            ->first();

        if ($dataMember) {
            return response()->json([
                'success' => true,
                'member' => $dataMember,
                'discount_percent' => $dataMember->discount_percent,
                'message' => "Member ditemukan: {$dataMember->name} (Diskon {$dataMember->discount_percent}%)",
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Nomor HP tidak terdaftar sebagai member.',
        ], 404);
    }

    public function store(Request $request)
    {
        $aturanValidasi = [
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.qty' => 'required|integer|min:1',
            'paid_amount' => 'required|numeric|min:0',
            'member_phone' => 'nullable|string',
        ];

        $request->validate($aturanValidasi);

        $sesiAktif = CashSession::where('user_id', Auth::id())
            ->where('status', 'open')
            ->first();

        if (! $sesiAktif) {
            return redirect()->route('admin.cash-sessions.open-form')
                ->with('error', 'Sesi kasir tidak aktif atau sudah ditutup.');
        }

        try {
            return DB::transaction(function () use ($request, $sesiAktif) {
                $totalBelanja = 0;
                $daftarBarang = [];

                foreach ($request->items as $barang) {
                    $dataProduk = Product::lockForUpdate()->findOrFail($barang['product_id']);

                    if ($dataProduk->stock < $barang['qty']) {
                        throw new \Exception("Stok produk '{$dataProduk->name}' tidak mencukupi!");
                    }

                    $subtotalBarang = $dataProduk->sell_price * $barang['qty'];
                    $totalBelanja += $subtotalBarang;

                    $daftarBarang[] = [
                        'product' => $dataProduk,
                        'qty' => $barang['qty'],
                        'price' => $dataProduk->sell_price,
                        'subtotal' => $subtotalBarang,
                    ];
                }

                $idMember = null;
                $potonganHarga = 0;
                $nomorHpMember = trim($request->input('member_phone', ''));

                if (! empty($nomorHpMember)) {
                    $dataMember = Member::where('phone', $nomorHpMember)
                        ->where('is_active', true)
                        ->first();

                    if ($dataMember) {
                        $idMember = $dataMember->id;
                        $potonganHarga = ($totalBelanja * $dataMember->discount_percent) / 100;
                    }
                }

                $totalAkhir = $totalBelanja - $potonganHarga;
                $uangDibayar = (float) $request->paid_amount;

                if ($uangDibayar < $totalAkhir) {
                    $formatRupiah = number_format($totalAkhir, 0, ',', '.');
                    throw new \Exception("Uang pembayaran kurang dari total belanja (Rp {$formatRupiah})");
                }

                $uangKembalian = $uangDibayar - $totalAkhir;
                $nomorNota = 'INV-'.date('Ymd').'-'.strtoupper(substr(uniqid(), -5));

                $transaksiBaru = Sale::create([
                    'invoice_number' => $nomorNota,
                    'user_id' => Auth::id(),
                    'cashier_session_id' => $sesiAktif->id,
                    'member_id' => $idMember,
                    'discount_amount' => $potonganHarga,
                    'total_amount' => $totalAkhir,
                    'cash_received' => $uangDibayar,
                    'cash_change' => $uangKembalian,
                ]);

                foreach ($daftarBarang as $item) {
                    SaleItem::create([
                        'sale_id' => $transaksiBaru->id,
                        'product_id' => $item['product']->id,
                        'qty' => $item['qty'],
                        'price' => $item['price'],
                        'subtotal' => $item['subtotal'],
                    ]);

                    $item['product']->decrement('stock', $item['qty']);
                }

                return redirect()->route('admin.pos.receipt', $transaksiBaru->id)
                    ->with('success', 'Transaksi berhasil!');
            });
        } catch (\Exception $error) {
            return redirect()->back()->with('error', $error->getMessage());
        }
    }

    public function receipt(Sale $sale)
    {
        if (Auth::user()->role === 'kasir' && $sale->user_id !== Auth::id()) {
            abort(403, 'Anda hanya dapat melihat struk transaksi Anda sendiri.');
        }

        $sale->load(['items.product', 'user', 'member']);

        return view('admin.pos.receipt', compact('sale'));
    }
}
