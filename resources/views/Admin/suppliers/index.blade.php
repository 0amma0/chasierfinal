@extends('layouts.admin')

@section('header_title', 'Master Data Supplier')

@section('content')
<div class="space-y-6">

  <!-- TAILWIND UI PAGE HEADER -->
  <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 lg:flex lg:items-center lg:justify-between">
    <div class="min-w-0 flex-1">
      <h2 class="text-2xl/7 font-bold text-gray-900 sm:truncate sm:text-3xl sm:tracking-tight">
        Gudang produk
      </h2>
      
      <!-- Metadata / Quick Stats -->
      <div class="mt-1 flex flex-col sm:mt-0 sm:flex-row sm:flex-wrap sm:space-x-6">
        <div class="mt-2 flex items-center text-sm text-gray-500">
          <svg viewBox="0 0 20 20" fill="currentColor" class="mr-1.5 h-5 w-5 shrink-0 text-gray-400">
            <path fill-rule="evenodd" d="M6 3.75A2.75 2.75 0 0 1 8.75 1h2.5A2.75 2.75 0 0 1 14 3.75v.443c.572.055 1.14.122 1.706.2C17.053 4.582 18 5.75 18 7.07v3.469c0 1.126-.694 2.191-1.83 2.54-1.952.599-4.024.921-6.17.921s-4.219-.322-6.17-.921C2.694 12.73 2 11.665 2 10.539V7.07c0-1.321.947-2.489 2.294-2.676A41.047 41.047 0 0 1 6 4.193V3.75Zm6.5 0v.325a41.622 41.622 0 0 0-5 0V3.75c0-.69.56-1.25 1.25-1.25h2.5c.69 0 1.25.56 1.25 1.25ZM10 10a1 1 0 0 0-1 1v.01a1 1 0 0 0 1 1h.01a1 1 0 0 0 1-1V11a1 1 0 0 0-1-1H10Z" clip-rule="evenodd" />
          </svg>
          Total Gudang: {{ $suppliers->total() ?? count($suppliers) }} Partner
        </div>
        </div>
      </div>
    </div>

    <!-- Tombol Tambah Supplier -->
    <div class="mt-5 flex lg:mt-0 lg:ml-4">
      <span>
        <a href="{{ route('admin.suppliers.create') }}" 
           class="inline-flex items-center rounded-xl bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 transition focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
          <svg viewBox="0 0 20 20" fill="currentColor" class="mr-1.5 -ml-0.5 h-5 w-5">
            <path d="M10.75 4.75a.75.75 0 0 0-1.5 0v4.5h-4.5a.75.75 0 0 0 0 1.5h4.5v4.5a.75.75 0 0 0 1.5 0v-4.5h4.5a.75.75 0 0 0 0-1.5h-4.5v-4.5Z" />
          </svg>
          Tambah Gudang
        </a>
      </span>
    </div>
  </div>

  <!-- FLASH SUCCESS NOTIFICATION -->
  @if(session('success'))
    <div class="rounded-2xl bg-emerald-50 p-4 border border-emerald-200 text-emerald-800 text-sm font-medium flex items-center gap-2">
      <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
      </svg>
      {{ session('success') }}
    </div>
  @endif

  <!--PENCARIAN SUPPLIER -->
  <div class="bg-white p-4 rounded-2xl shadow-sm border border-gray-100">
    <form method="GET" action="{{ route('admin.suppliers.index') }}" class="flex flex-col sm:flex-row gap-3">
      <div class="relative flex-1">
        <input type="text" 
               name="search" 
               placeholder="Cari nama supplier / distributor..." 
               value="{{ request('search') }}"
               class="w-full rounded-xl border border-gray-200 bg-gray-50/50 py-2.5 px-4 text-sm text-gray-800 focus:bg-white focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 transition">
      </div>

      <button type="submit" class="inline-flex justify-center items-center rounded-xl bg-gray-800 px-5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-gray-700 transition">
        Cari
      </button>

      @if(request('search'))
        <a href="{{ route('admin.suppliers.index') }}" class="inline-flex items-center text-xs text-gray-500 hover:text-gray-700 underline px-2">
          Reset
        </a>
      @endif
    </form>
  </div>

  <!-- 🟢 3. TABEL DATA SUPPLIER -->
  <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
      <table class="w-full text-left text-sm text-gray-600">
        <thead class="bg-gray-50/80 text-gray-700 uppercase text-xs tracking-wider border-b border-gray-100">
          <tr>
            <th class="px-6 py-4">Nama Supplier</th>
            <th class="px-6 py-4">Contact Person</th>
            <th class="px-6 py-4">No. Telepon</th>
            <th class="px-4 py-4">Status</th>
            <th class="px-6 py-4 text-center">Aksi</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
          @forelse($suppliers as $supplier)
          <tr class="hover:bg-gray-50/50 transition">
            <!-- Nama -->
            <td class="px-6 py-4 font-bold text-gray-900">
              {{ $supplier->name }}
            </td>

            <!-- Contact Person -->
            <td class="px-6 py-4 text-gray-600">
              {{ $supplier->contact_person ?? '-' }}
            </td>

            <!-- Phone -->
            <td class="px-6 py-4 font-mono text-xs text-gray-600">
              {{ $supplier->phone ?? '-' }}
            </td>

            <!-- Status -->
            <td class="px-4 py-4 whitespace-nowrap">
              @if($supplier->is_active)
                <span class="inline-flex items-center rounded-md bg-green-50 px-2.5 py-1 text-xs font-semibold text-green-700 ring-1 ring-inset ring-green-600/20">
                  Aktif
                </span>
              @else
                <span class="inline-flex items-center rounded-md bg-red-50 px-2.5 py-1 text-xs font-semibold text-red-700 ring-1 ring-inset ring-red-600/20">
                  Nonaktif
                </span>
              @endif
            </td>

            <!-- Aksi -->
            <td class="px-6 py-4 text-center whitespace-nowrap">
              <a href="{{ route('admin.suppliers.edit', $supplier) }}" 
                 class="px-2.5 py-1.5 rounded-lg text-xs font-semibold bg-gray-100 text-gray-700 hover:bg-gray-200 transition">
                Edit
              </a>

              <form action="{{ route('admin.suppliers.toggle-status', $supplier) }}" method="POST" class="inline">
                @csrf @method('PATCH')
                <button type="submit" 
                        class="px-2.5 py-1.5 rounded-lg text-xs font-semibold transition cursor-pointer
                               {{ $supplier->is_active 
                                   ? 'bg-red-50 text-red-600 hover:bg-red-100' 
                                   : 'bg-green-50 text-green-600 hover:bg-green-100' }}">
                    {{ $supplier->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                </button>
              </form>
            </td>
          </tr>
          @empty
          <tr>
            <td colspan="5" class="px-6 py-10 text-center text-gray-400">
              Belum ada data supplier yang terdaftar.
            </td>
          </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    <!-- Pagination -->
    @if($suppliers->hasPages())
      <div class="px-6 py-4 border-t border-gray-100">
        {{ $suppliers->links() }}
      </div>
    @endif
  </div>

</div>
@endsection