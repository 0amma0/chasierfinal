@extends('layouts.admin')

@section('header_title', 'Buka Kasir')

@section('content')
<div class="relative isolate bg-white px-6 py-12 sm:py-16 lg:px-8 rounded-3xl border border-gray-100 shadow-sm overflow-hidden">
  
  <!-- Hiasan Background Blur Gradient -->
  <div aria-hidden="true" class="absolute inset-x-0 -top-3 -z-10 transform-gpu overflow-hidden px-36 blur-3xl">
    <div style="clip-path: polygon(74.1% 44.1%, 100% 61.6%, 97.5% 26.9%, 85.5% 0.1%, 80.7% 2%, 72.5% 32.5%, 60.2% 62.4%, 52.4% 68.1%, 47.5% 58.3%, 45.2% 34.5%, 27.5% 76.7%, 0.1% 64.9%, 17.9% 100%, 27.6% 76.8%, 76.1% 97.7%, 74.1% 44.1%)" 
         class="mx-auto aspect-1155/678 w-288.75 bg-linear-to-tr from-[#ff80b5] to-[#9089fc] opacity-30"></div>
  </div>

  <!-- JUDUL UTAMA -->
  <div class="mx-auto max-w-4xl text-center">
    <h2 class="text-base/7 font-semibold text-indigo-600">Sesi Kasir Baru</h2>
    <p class="mt-2 text-4xl font-semibold tracking-tight text-balance text-gray-900 sm:text-5xl">
      Buka Shift Kasir
    </p>
  </div>
  <p class="mx-auto mt-4 max-w-2xl text-center text-base font-medium text-pretty text-gray-600 sm:text-lg">
    Masukkan jumlah modal awal yang ada di dalam laci sebelum memulai transaksi penjualan di petshop.
  </p>

  <!-- ALERT ERROR SESSION / VALIDATION -->
  @if(session('error') || !empty($error))
    <div class="mx-auto mt-8 max-w-2xl rounded-2xl bg-red-50 p-4 border border-red-200">
      <div class="flex">
        <div class="shrink-0">
          <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.28 7.22a.75.75 0 00-1.06 1.06L8.94 10l-1.72 1.72a.75.75 0 101.06 1.06L10 11.06l1.72 1.72a.75.75 0 101.06-1.06L11.06 10l1.72-1.72a.75.75 0 00-1.06-1.06L10 8.94 8.28 7.22z" clip-rule="evenodd" />
          </svg>
        </div>
        <div class="ml-3">
          <p class="text-sm font-medium text-red-800">{{ session('error') ?? $error }}</p>
        </div>
      </div>
    </div>
  @endif

  @if($errors->any())
    <div class="mx-auto mt-8 max-w-2xl rounded-2xl bg-red-50 p-4 border border-red-200">
      <div class="flex">
        <div class="shrink-0">
          <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.28 7.22a.75.75 0 00-1.06 1.06L8.94 10l-1.72 1.72a.75.75 0 101.06 1.06L10 11.06l1.72 1.72a.75.75 0 101.06-1.06L11.06 10l1.72-1.72a.75.75 0 00-1.06-1.06L10 8.94 8.28 7.22z" clip-rule="evenodd" />
          </svg>
        </div>
        <div class="ml-3">
          <h3 class="text-sm font-medium text-red-800">Terdapat kesalahan pada input:</h3>
          <ul class="mt-2 list-disc list-inside text-xs text-red-700 space-y-1">
            @foreach($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
          </ul>
        </div>
      </div>
    </div>
  @endif

  <!-- GRID CARD UTAMA -->
  <div class="mx-auto mt-12 grid max-w-lg grid-cols-1 items-stretch gap-y-6 sm:mt-16 sm:gap-y-0 lg:max-w-4xl lg:grid-cols-2">

    <!-- CARD KIRI: INFORMASI & PANDUAN (WHITE CARD) -->
    <div class="rounded-3xl bg-white/80 p-8 ring-1 ring-gray-900/10 sm:mx-8 sm:p-10 lg:mx-0 lg:rounded-r-none lg:rounded-l-3xl flex flex-col justify-between backdrop-blur-md">
      <div>
        <h3 class="text-base/7 font-semibold text-indigo-600">Panduan Sesi Kasir</h3>
        <p class="mt-4 flex items-baseline gap-x-2">
          <span class="text-3xl font-bold tracking-tight text-gray-900">Petshop POS</span>
        </p>
        <p class="mt-6 text-sm text-gray-600">
          Membuka sesi kasir sangat penting untuk pencatatan riwayat transaksi dan hitungan saldo akhir yang akurat.
        </p>

        <!-- LIST PANDUAN -->
        <ul role="list" class="mt-8 space-y-3 text-sm/6 text-gray-600 border-t border-gray-100 pt-6">
          <li class="flex gap-x-3">
            <svg viewBox="0 0 20 20" fill="currentColor" class="h-6 w-5 flex-none text-indigo-600">
              <path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 0 1 .143 1.052l-8 10.5a.75.75 0 0 1-1.127.075l-4.5-4.5a.75.75 0 0 1 1.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 0 1 1.05-.143Z" clip-rule="evenodd" />
            </svg>
            Hitung jumlah fisik modal awal (uang kembalian) dengan teliti.
          </li>
          <li class="flex gap-x-3">
            <svg viewBox="0 0 20 20" fill="currentColor" class="h-6 w-5 flex-none text-indigo-600">
              <path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 0 1 .143 1.052l-8 10.5a.75.75 0 0 1-1.127.075l-4.5-4.5a.75.75 0 0 1 1.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 0 1 1.05-.143Z" clip-rule="evenodd" />
            </svg>
            Semua transaksi penjualan akan terikat pada sesi yang aktif.
          </li>
          <li class="flex gap-x-3">
            <svg viewBox="0 0 20 20" fill="currentColor" class="h-6 w-5 flex-none text-indigo-600">
              <path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 0 1 .143 1.052l-8 10.5a.75.75 0 0 1-1.127.075l-4.5-4.5a.75.75 0 0 1 1.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 0 1 1.05-.143Z" clip-rule="evenodd" />
            </svg>
            Sesi dapat ditutup kapan saja saat pergantian shift / toko tutup.
          </li>
        </ul>
      </div>

      <div class="mt-8 text-xs text-gray-400">
        *Kasir Aktif: <span class="font-bold text-gray-700">{{ auth()->user()->name ?? 'Kasir' }}</span>
      </div>
    </div>

    <!-- CARD KANAN: FORM INPUT BUKA KASIR (DARK CARD) -->
    <div class="relative rounded-3xl bg-gray-900 p-8 shadow-2xl ring-1 ring-gray-900/10 sm:p-10 flex flex-col justify-between">
      <div>
        <h3 class="text-base/7 font-semibold text-indigo-400">Form Pembukaan</h3>
        <p class="mt-2 text-sm text-gray-300">
          Isi modal fisik awal yang ada di dalam laci kasir saat ini.
        </p>

        @if(($canOpen ?? true) === false)
          <p class="mt-6 text-sm text-amber-300">Form ditutup sampai sesi kasir lain diselesaikan.</p>
        @else
        <form id="openSessionForm" method="POST" action="{{ route('admin.cash-sessions.open') }}" class="mt-6 space-y-5">
          @csrf

          <!-- INPUT MODAL AWAL -->
          <div>
            <label class="block text-xs font-semibold text-gray-300 uppercase tracking-wider mb-2">
              Modal Awal (Rp) <span class="text-red-400">*</span>
            </label>
            <div class="relative rounded-xl shadow-sm">
              <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5">
                <span class="text-gray-400 text-sm font-semibold">Rp</span>
              </div>
              <input type="number" 
                     name="opening_balance" 
                     step="0.01" 
                     min="0" 
                     required 
                     autofocus
                     placeholder="0"
                     class="block w-full rounded-xl border-0 bg-gray-800/80 py-3 pl-10 pr-4 text-white font-bold text-lg ring-1 ring-inset ring-gray-700 placeholder:text-gray-500 focus:ring-2 focus:ring-inset focus:ring-indigo-500 sm:text-xl">
            </div>
          </div>

          <!-- TEXTAREA CATATAN -->
          <div>
            <label class="block text-xs font-semibold text-gray-300 uppercase tracking-wider mb-2">
              Catatan (Opsional)
            </label>
            <textarea name="note" 
                      rows="3" 
                      placeholder="Tuliskan catatan tambahan jika ada..."
                      class="block w-full rounded-xl border-0 bg-gray-800/80 p-3 text-sm text-white ring-1 ring-inset ring-gray-700 placeholder:text-gray-500 focus:ring-2 focus:ring-inset focus:ring-indigo-500"></textarea>
          </div>
        </form>
        @endif
      </div>

      @if(($canOpen ?? true) !== false)
      <div class="mt-8">
        <button type="submit" 
                form="openSessionForm"
                class="block w-full rounded-xl bg-indigo-500 px-4 py-3 text-center text-sm font-bold text-white shadow-lg hover:bg-indigo-400 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-500 transition cursor-pointer">
          🔓 Buka Kasir Sekarang
        </button>
      </div>
      @endif
    </div>

  </div>
</div>
@endsection