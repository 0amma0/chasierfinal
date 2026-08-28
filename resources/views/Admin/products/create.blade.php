@extends('layouts.admin')

@section('content')
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-6">

        <!-- Flash Alert Error (Validasi) -->
        @if ($errors->any())
            <div class="mb-6 rounded-xl bg-red-50 p-4 border border-red-200">
                <div class="flex items-center gap-2 mb-2 text-sm font-semibold text-red-800">
                    <svg class="size-5 text-red-500 shrink-0" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd"
                            d="M10 18a8 8 0 1 0 0-16 8 8 0 0 0 0 16ZM8.28 7.22a.75.75 0 0 0-1.06 1.06L8.94 10l-1.72 1.72a.75.75 0 1 0 1.06 1.06L10 11.06l1.72 1.72a.75.75 0 1 0 1.06-1.06L11.06 10l1.72-1.72a.75.75 0 0 0-1.06-1.06L10 8.94 8.28 7.22Z"
                            clip-rule="evenodd" />
                    </svg>
                    <span>Terdapat beberapa kesalahan input:</span>
                </div>
                <ul class="list-disc list-inside text-sm text-red-700 space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Form Container -->
        <form method="POST" action="{{ route('admin.products.store') }}"
            class="bg-white p-6 sm:p-8 rounded-2xl shadow-xs border border-gray-200">
            @csrf

            <div class="space-y-12">
                <div class="border-b border-gray-900/10 pb-12">
                    <h2 class="text-base/7 font-semibold text-gray-900">Tambah Produk Baru</h2>
                    <p class="mt-1 text-sm/6 text-gray-600">Lengkapi data dan detail informasi produk yang ingin ditambahkan
                        ke sistem POS.</p>

                    <div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">

                        <!-- Nama Produk -->
                        <div class="sm:col-span-4">
                            <label for="name" class="block text-sm/6 font-medium text-gray-900">Nama Produk <span
                                    class="text-red-500">*</span></label>
                            <div class="mt-2">
                                <input id="name" type="text" name="name" value="{{ old('name') }}"
                                    placeholder="Contoh: Kopi Susu Aren"
                                    class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6 @error('name') ring-1 ring-red-500 @enderror" />
                            </div>
                            @error('name')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Barcode -->
                        <div class="sm:col-span-2">
                            <label for="barcode" class="block text-sm/6 font-medium text-gray-900">SKU</label>
                            <div class="mt-2">
                                <input id="barcode" type="text" name="barcode" value="{{ old('barcode') }}"
                                    placeholder="89912345678"
                                    class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline-1 -outline-offset-1 placeholder:text-gray-400 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6 @error('barcode') outline-red-500 @enderror" />
                            </div>
                            @error('barcode')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Kategori (Dropdown Select) -->
                        <div class="sm:col-span-3">
                            <label for="category_id" class="block text-sm/6 font-medium text-gray-900">
                                Kategori <span class="text-red-500">*</span>
                            </label>
                            <div class="mt-2 grid grid-cols-1">
                                <select id="category_id" name="category_id"
                                    class="col-start-1 row-start-1 w-full appearance-none rounded-md bg-white py-1.5 pr-8 pl-3 text-base text-gray-900 outline-1 -outline-offset-1 outline-gray-300 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6 @error('category_id') ring-1 ring-red-500 @enderror">
                                    <option value="">-- Pilih Kategori --</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}"
                                            {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <svg class="pointer-events-none col-start-1 row-start-1 mr-2 size-5 self-center justify-self-end text-gray-500 sm:size-4"
                                    viewBox="0 0 16 16" fill="currentColor">
                                    <path
                                        d="M4.22 6.22a.75.75 0 0 1 1.06 0L8 8.94l2.72-2.72a.75.75 0 1 1 1.06 1.06l-3.25 3.25a.75.75 0 0 1-1.06 0L4.22 7.28a.75.75 0 0 1 0-1.06Z"
                                        clip-rule="evenodd" fill-rule="evenodd" />
                                </svg>
                            </div>
                            @error('category_id')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <!-- Stok Awal -->
                        <div class="sm:col-span-3">
                            <label for="stock" class="block text-sm/6 font-medium text-gray-900">Stok Awal</label>
                            <div class="mt-2">
                                <input id="stock" type="number" name="stock" value="{{ old('stock', 0) }}"
                                    min="0"
                                    class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6 @error('stock') ring-1 ring-red-500 @enderror" />
                            </div>
                            @error('stock')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Harga Beli -->
                        <div class="sm:col-span-3">
                            <label for="buy_price" class="block text-sm/6 font-medium text-gray-900">Harga pokok
                                </label>
                            <div class="mt-2">
                                <div
                                    class="flex items-center rounded-md bg-white pl-3 outline-1 -outline-offset-1 outline-gray-300 focus-within:outline-2 focus-within:-outline-offset-2 focus-within:outline-indigo-600">
                                    <div class="shrink-0 text-base text-gray-500 select-none sm:text-sm/6">Rp</div>
                                    <input id="buy_price" type="number" step="0.01" name="buy_price"
                                        value="{{ old('buy_price') }}" placeholder="0"
                                        class="block min-w-0 grow bg-white py-1.5 pr-3 pl-2 text-base text-gray-900 placeholder:text-gray-400 focus:outline-none sm:text-sm/6" />
                                </div>
                            </div>
                            @error('buy_price')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Harga Jual -->
                        <div class="sm:col-span-3">
                            <label for="sell_price" class="block text-sm/6 font-medium text-gray-900">Harga Jual</label>
                            <div class="mt-2">
                                <div
                                    class="flex items-center rounded-md bg-white pl-3 outline-1 -outline-offset-1 outline-gray-300 focus-within:outline-2 focus-within:-outline-offset-2 focus-within:outline-indigo-600">
                                    <div class="shrink-0 text-base text-gray-500 select-none sm:text-sm/6">Rp</div>
                                    <input id="sell_price" type="number" step="0.01" name="sell_price"
                                        value="{{ old('sell_price') }}" placeholder="0"
                                        class="block min-w-0 grow bg-white py-1.5 pr-3 pl-2 text-base text-gray-900 placeholder:text-gray-400 focus:outline-none sm:text-sm/6" />
                                </div>
                            </div>
                            @error('sell_price')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="mt-8 pt-6 border-t border-gray-100 flex items-center justify-end gap-x-4">
                <a href="{{ route('admin.products.index') }}"
                    class="text-sm/6 font-semibold text-gray-900 hover:text-gray-700">
                    Batal
                </a>
                <button type="submit"
                    class="rounded-md bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-xs hover:bg-indigo-500 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 transition">
                    Simpan
                </button>
            </div>
        </form>
    </div>
@endsection