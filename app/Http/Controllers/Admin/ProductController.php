<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\StockMovement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $products = Product::query()->with('category');

        if ($request->filled('search')) {
            $search = $request->search;
            $products->where(function ($q) use ($search) {
                $q->where('name', 'like', '%'.$search.'%')
                    ->orWhere('barcode', 'like', '%'.$search.'%');
            });
        }

        if ($request->filled('category_id')) {
            $products->where('category_id', $request->category_id);
        }

        $products = $products->orderBy('name')->paginate(15)->withQueryString();
        $categories = Category::orderBy('name')->get();

        return view('admin.products.index', compact('products', 'categories'));
    }

    public function create()
    {
        $categories = Category::all();

        return view('admin.products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'barcode' => 'nullable|string|unique:products,barcode',
            'category_id' => 'required|exists:categories,id',
            'buy_price' => 'required|numeric|min:0',
            'sell_price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
        ]);

        $product = Product::create($data);

        if ($data['stock'] > 0) {
            StockMovement::create([
                'product_id' => $product->id,
                'type' => 'in',
                'qty' => $data['stock'],
                'stock_before' => 0,
                'stock_after' => $data['stock'],
                'note' => 'Stok awal',
                'user_id' => Auth::id(),
            ]);
        }

        return redirect()->route('admin.products.index')->with('success', 'Produk berhasil ditambahkan.');
    }

    public function edit(Product $product)
    {
        $categories = Category::orderBy('name')->get();

        return view('admin.products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'barcode' => 'nullable|string|unique:products,barcode,'.$product->id,
            'category_id' => 'required|exists:categories,id',
            'buy_price' => 'required|numeric|min:0',
            'sell_price' => 'required|numeric|min:0',
        ]);

        $product->update($data);

        return redirect()->route('admin.products.index')->with('success', 'Produk berhasil diupdate.');
    }

    public function deactivate(Product $product)
    {
        $product->update(['is_active' => false]);

        return back()->with('success', 'Produk berhasil dinonaktifkan.');
    }

    public function activate(Product $product)
    {
        $product->update(['is_active' => true]);

        return back()->with('success', 'Produk diaktifkan kembali.');
    }

    public function destroy(Product $product)
    {
        if ($product->saleItems()->exists()) {
            $product->update(['is_active' => false]);

            return redirect()->route('admin.products.index')
                ->with('success', 'Produk memiliki riwayat transaksi, status diubah menjadi Nonaktif.');
        }

        DB::transaction(function () use ($product) {
            $product->stockMovements()->delete();
            $product->delete();
        });

        return redirect()->route('admin.products.index')->with('success', 'Produk berhasil dihapus permanen!');
    }

    public function restockForm(Product $product)
    {
        return view('admin.products.restock', compact('product'));
    }

    public function restockProcess(Request $request, Product $product)
    {
        $data = $request->validate([
            'qty' => 'required|integer|min:1',
            'note' => 'nullable|string|max:255',
        ]);

        $stockBefore = $product->stock;
        $stockAfter = $stockBefore + $data['qty'];

        $product->update(['stock' => $stockAfter]);

        StockMovement::create([
            'product_id' => $product->id,
            'type' => 'in',
            'qty' => $data['qty'],
            'stock_before' => $stockBefore,
            'stock_after' => $stockAfter,
            'note' => $data['note'] ?? 'Restok manual',
            'user_id' => Auth::id(),
        ]);

        return redirect()->route('admin.products.index')
            ->with('success', "Stok produk '{product->name}' berhasil ditambahkan {data['qty']} unit.");
    }
}
