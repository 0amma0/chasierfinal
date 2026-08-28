@extends('layouts.admin')

@section('content')
<script src="https://cdn.jsdelivr.net/npm/@tailwindplus/elements@1" type="module"></script>

<div class="max-w-4xl mx-auto p-6 bg-white rounded-lg shadow-sm">
    <div class="flex items-center justify-between border-b pb-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Struk Transaksi</h1>
            <p class="text-sm text-gray-500">Invoice: <span class="font-semibold text-gray-800">{{ $sale->invoice_number }}</span></p>
        </div>
        <button command="show-modal" commandfor="drawer" class="rounded-md bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-xs hover:bg-indigo-500 transition cursor-pointer">
            Lihat Struk Detail
        </button>
    </div>

    @if(session('success'))
        <div class="mb-6 rounded-md bg-green-50 p-4 border border-green-200">
            <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
        </div>
    @endif

    <!-- Ringkasan Singkat -->
    <div class="bg-gray-50 p-4 rounded-lg mb-6 flex justify-between items-center">
        <div>
            <p class="text-xs text-gray-500 uppercase tracking-wider">Tanggal Transaksi</p>
            <p class="text-sm font-medium text-gray-900">{{ $sale->created_at->format('d/m/Y H:i') }}</p>
            @if($sale->member)
                <p class="text-xs text-indigo-600 font-semibold mt-1">Member: {{ $sale->member->name }} ({{ $sale->member->phone }})</p>
            @endif
        </div>
        <div class="text-right">
            <p class="text-xs text-gray-500 uppercase tracking-wider">Total Pembayaran</p>
            <p class="text-lg font-bold text-indigo-600">Rp {{ number_format($sale->total_amount, 0, ',', '.') }}</p>
        </div>
    </div>

    <div class="flex justify-start">
        <a href="{{ route('admin.pos.index') }}" class="inline-flex items-center text-sm font-medium text-gray-600 hover:text-indigo-600 transition">
            &larr; Transaksi Baru
        </a>
    </div>
</div>

<!-- Drawer Struk Detail -->
<el-dialog>
  <dialog id="drawer" aria-labelledby="drawer-title" class="fixed inset-0 size-auto max-h-none max-w-none overflow-hidden bg-transparent not-open:hidden backdrop:bg-transparent z-50">
    <el-dialog-backdrop class="absolute inset-0 bg-gray-500/75 transition-opacity duration-500 ease-in-out data-closed:opacity-0"></el-dialog-backdrop>

    <div tabindex="0" class="absolute inset-0 pl-10 focus:outline-none sm:pl-16">
      <el-dialog-panel class="group/dialog-panel relative ml-auto block size-full max-w-md transform transition duration-500 ease-in-out data-closed:translate-x-full sm:duration-700">
        
        <div class="absolute top-0 left-0 -ml-8 flex pt-4 pr-2 duration-500 ease-in-out group-data-closed/dialog-panel:opacity-0 sm:-ml-10 sm:pr-4">
          <button type="button" command="close" commandfor="drawer" class="relative rounded-md text-gray-300 hover:text-white focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
            <span class="sr-only">Close panel</span>
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" class="size-6">
              <path d="M6 18 18 6M6 6l12 12" stroke-linecap="round" stroke-linejoin="round" />
            </svg>
          </button>
        </div>

        <div class="relative flex h-full flex-col overflow-y-auto bg-white py-6 shadow-xl">
          <div class="px-4 sm:px-6 border-b pb-4">
            <h2 id="drawer-title" class="text-base font-semibold text-gray-900">Rincian Struk</h2>
            <p class="text-xs text-gray-500 mt-1">No. {{ $sale->invoice_number }} • {{ $sale->created_at->format('d/m/Y H:i') }}</p>

            @if($sale->member)
              <div class="mt-2 text-xs text-indigo-700 font-medium bg-indigo-50 p-2.5 rounded-lg border border-indigo-100 flex items-center justify-between">
                <div>
                  <span class="font-bold text-indigo-900">{{ $sale->member->name }}</span> ({{ $sale->member->phone }})
                </div>
                <span class="bg-indigo-600 text-white font-bold px-2 py-0.5 rounded text-[10px]">Diskon {{ $sale->member->discount_percent }}%</span>
              </div>
            @endif
          </div>
          
          <div class="relative mt-4 flex-1 px-4 sm:px-6">
            <div class="flow-root">
              <ul role="list" class="-my-6 divide-y divide-gray-200">
                @foreach($sale->items as $item)
                  <li class="flex py-4">
                    <div class="flex-1 flex flex-col">
                      <div class="flex justify-between text-base font-medium text-gray-900">
                        <h3>{{ $item->product->name ?? 'Produk dihapus' }}</h3>
                        <p class="ml-4 font-semibold">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</p>
                      </div>
                      <p class="mt-1 text-sm text-gray-500">{{ $item->qty }} x Rp {{ number_format($item->price, 0, ',', '.') }}</p>
                    </div>
                  </li>
                @endforeach
              </ul>
            </div>
          </div>

          <div class="border-t border-gray-200 px-4 py-4 sm:px-6 bg-gray-50 space-y-2">
            @php $subtotal = $sale->items->sum('subtotal'); @endphp

            <div class="flex justify-between text-sm text-gray-600">
              <p>Subtotal</p>
              <p class="font-medium text-gray-900">Rp {{ number_format($subtotal, 0, ',', '.') }}</p>
            </div>

            @if(($sale->discount_amount ?? 0) > 0)
              <div class="flex justify-between text-sm text-emerald-600 font-semibold">
                <p>Diskon Member</p>
                <p>- Rp {{ number_format($sale->discount_amount, 0, ',', '.') }}</p>
              </div>
            @endif

            <div class="border-t border-gray-200 pt-1 my-1"></div>

            <div class="flex justify-between text-base font-bold text-gray-900">
              <p>Total Bayar</p>
              <p class="text-indigo-600">Rp {{ number_format($sale->total_amount, 0, ',', '.') }}</p>
            </div>

            <div class="flex justify-between text-sm text-gray-600">
              <p>Uang Diterima</p>
              <p class="font-medium text-gray-900">Rp {{ number_format($sale->cash_received, 0, ',', '.') }}</p>
            </div>

            <div class="flex justify-between text-sm text-gray-600">
              <p>Kembalian</p>
              <p class="font-medium text-gray-900">Rp {{ number_format($sale->cash_change, 0, ',', '.') }}</p>
            </div>

            <div class="mt-6 flex justify-end">
              <button type="button" command="close" commandfor="drawer" class="w-full rounded-md bg-gray-100 px-3.5 py-2.5 text-center text-sm font-semibold text-gray-900 shadow-xs hover:bg-gray-200 cursor-pointer">
                Tutup
              </button>
            </div>
          </div>

        </div>
      </el-dialog-panel>
    </div>
  </dialog>
</el-dialog>
@endsection