@extends('layouts.admin')

@section('header_title', 'Master Data Produk')

@section('content')
<div class="space-y-6">

  <!-- TAILWIND UI PAGE HEADER -->
  <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 lg:flex lg:items-center lg:justify-between">
    <div class="min-w-0 flex-1">
      <h2 class="text-2xl/7 font-bold text-gray-900 sm:truncate sm:text-3xl sm:tracking-tight">
        Data Produk
      </h2>
      <div class="mt-1 flex flex-col sm:mt-0 sm:flex-row sm:flex-wrap sm:space-x-6">
        <div class="mt-2 flex items-center text-sm text-gray-500">
          Total Produk: {{ $products->total() ?? count($products) }} Items
        </div>
        <div class="mt-2 flex items-center text-sm text-gray-500">
          {{ $categories->count() }} Kategori Terdaftar
        </div>
      </div>
    </div>

    <div class="mt-5 flex lg:mt-0 lg:ml-4">
      <a href="{{ route('admin.products.create') }}" 
         class="inline-flex items-center rounded-xl bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 transition">
        + Tambah Produk
      </a>  
    </div>
  </div>

  <!-- FLASH NOTIFICATION -->
  @if(session('success'))
    <div class="rounded-2xl bg-emerald-50 p-4 border border-emerald-200 text-emerald-800 text-sm font-medium">
      {{ session('success') }}
    </div>
  @endif

  @if(session('error'))
    <div class="rounded-2xl bg-red-50 p-4 border border-red-200 text-red-800 text-sm font-medium">
      {{ session('error') }}
    </div>
  @endif

  <!-- FILTER & PENCARIAN -->
  <div class="bg-white p-4 rounded-2xl shadow-sm border border-gray-100">
    <form method="GET" action="{{ route('admin.products.index') }}" class="flex flex-col sm:flex-row gap-3">
      <div class="relative flex-1">
        <input type="text" 
               name="search" 
               placeholder="Cari nama / SKU produk..." 
               value="{{ request('search') }}"
               class="w-full rounded-xl border border-gray-200 bg-gray-50/50 py-2.5 px-4 text-sm text-gray-800 focus:bg-white focus:border-indigo-500 focus:outline-none">
      </div>

      <div class="w-full sm:w-56">
        <select name="category_id" class="w-full rounded-xl border border-gray-200 bg-gray-50/50 py-2.5 px-3 text-sm text-gray-800">
          <option value="">Semua Kategori</option>
          @foreach($categories as $cat)
            <option value="{{ $cat->id }}" @selected(request('category_id') == $cat->id)>{{ $cat->name }}</option>
          @endforeach
        </select>
      </div>

      <button type="submit" class="inline-flex justify-center items-center rounded-xl bg-gray-800 px-5 py-2.5 text-sm font-semibold text-white hover:bg-gray-700 transition">
        Filter
      </button>
    </form>
  </div>

  <!-- TABEL DATA PRODUK -->
  <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
      <table class="w-full text-left text-sm text-gray-600">
        <thead class="bg-gray-50/80 text-gray-700 uppercase text-xs tracking-wider border-b border-gray-100">
          <tr>
            <th class="px-6 py-4">Nama Produk</th>
            <th class="px-4 py-4">SKU</th>
            <th class="px-4 py-4">Kategori</th>
            <th class="px-4 py-4">Harga Pokok</th>
            <th class="px-4 py-4">Harga Jual</th>
            <th class="px-4 py-4">Stok</th>
            <th class="px-4 py-4">Status</th>
            <th class="px-6 py-4 text-center">Aksi</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
          @forelse($products as $product)
          <tr class="hover:bg-gray-50/50 transition">
            <td class="px-6 py-4 font-bold text-gray-900">{{ $product->name }}</td>
            <td class="px-4 py-4 font-mono text-xs text-gray-500">{{ $product->barcode ?? '-' }}</td>
            <td class="px-4 py-4">
              <span class="inline-flex items-center rounded-md bg-gray-100 px-2 py-1 text-xs font-medium text-gray-600">
                {{ $product->category?->name ?? 'Tanpa Kategori' }}
              </span>
            </td>
            <td class="px-4 py-4 font-medium text-gray-500">Rp {{ number_format($product->buy_price, 0, ',', '.') }}</td>
            <td class="px-4 py-4 font-bold text-indigo-600">Rp {{ number_format($product->sell_price, 0, ',', '.') }}</td>
            <td class="px-4 py-4 font-bold {{ $product->stock <= 5 ? 'text-red-500' : 'text-gray-800' }}">{{ $product->stock }}</td>
            <td class="px-4 py-4">
              @if($product->is_active)
                <span class="inline-flex items-center rounded-md bg-green-50 px-2 py-1 text-xs font-semibold text-green-700 ring-1 ring-inset ring-green-600/20">Aktif</span>
              @else
                <span class="inline-flex items-center rounded-md bg-red-50 px-2 py-1 text-xs font-semibold text-red-700 ring-1 ring-inset ring-red-600/20">Nonaktif</span>
              @endif
            </td>

            <!-- AKSI -->
            <td class="px-6 py-4 text-center whitespace-nowrap">
              <div class="inline-flex items-center gap-2">
                <a href="{{ route('admin.products.edit', $product) }}" class="px-2.5 py-1.5 rounded-lg text-xs font-semibold bg-gray-100 text-gray-700 hover:bg-gray-200 transition">
                  Edit
                </a>

                @if($product->is_active)
                  <a href="{{ route('admin.products.restock.form', $product) }}" class="px-2.5 py-1.5 rounded-lg text-xs font-semibold bg-blue-50 text-blue-600 hover:bg-blue-100 transition">
                    Restock
                  </a>
                  <form action="{{ route('admin.products.deactivate', $product) }}" method="POST" class="inline">
                    @csrf @method('PATCH')
                    <button type="submit" class="px-2.5 py-1.5 rounded-lg text-xs font-semibold bg-red-50 text-red-600 hover:bg-red-100 transition cursor-pointer">
                      Nonaktifkan
                    </button>
                  </form>
                @else
                  <form action="{{ route('admin.products.activate', $product) }}" method="POST" class="inline">
                    @csrf @method('PATCH')
                    <button type="submit" class="px-2.5 py-1.5 rounded-lg text-xs font-semibold bg-green-50 text-green-600 hover:bg-green-100 transition cursor-pointer">
                      Aktifkan
                    </button>
                  </form>
                @endif

                <form action="{{ route('admin.products.destroy', $product) }}" method="POST" class="inline">
                  @csrf @method('DELETE')
                  <button type="submit" onclick="return confirm('Apakah Anda yakin ingin menghapus produk ini secara permanen?')" class="px-2.5 py-1.5 rounded-lg text-xs font-semibold bg-red-600 text-white hover:bg-red-700 transition cursor-pointer">
                    Delete
                  </button>
                </form>

              </div>
            </td>
          </tr>
          @empty
          <tr>
            <td colspan="8" class="px-6 py-10 text-center text-gray-400">
              Belum ada data produk yang ditemukan.
            </td>
          </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    @if($products->hasPages())
      <div class="px-6 py-4 border-t border-gray-100">
        {{ $products->links() }}
      </div>
    @endif
  </div>

</div>
@endsection