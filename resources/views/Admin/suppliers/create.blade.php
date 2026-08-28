@extends('layouts.admin')

@section('header_title', 'Tambah Supplier')

@section('content')
<div class="bg-white p-6 sm:p-8 rounded-2xl shadow-sm border border-gray-100 max-w-4xl mx-auto">

  <!-- 🟢 1. ERROR ALERT -->
  @if($errors->any())
    <div class="mb-8 rounded-2xl bg-red-50 p-4 border border-red-200">
      <div class="flex">
        <div class="shrink-0">
          <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.28 7.22a.75.75 0 00-1.06 1.06L8.94 10l-1.72 1.72a.75.75 0 101.06 1.06L10 11.06l1.72 1.72a.75.75 0 101.06-1.06L11.06 10l1.72-1.72a.75.75 0 00-1.06-1.06L10 8.94 8.28 7.22z" clip-rule="evenodd" />
          </svg>
        </div>
        <div class="ml-3">
          <h3 class="text-sm font-medium text-red-800">Terdapat kesalahan saat mengisi form:</h3>
          <ul class="mt-2 list-disc list-inside text-xs text-red-700 space-y-1">
            @foreach($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
          </ul>
        </div>
      </div>
    </div>
  @endif

  <!-- 🟢 2. FORM UTAMA -->
  <form method="POST" action="{{ route('admin.suppliers.store') }}">
    @csrf

    <div class="space-y-12">
      <div class="border-b border-gray-900/10 pb-10">
        <h2 class="text-lg font-semibold text-gray-900">Informasi Supplier Baru</h2>
        <p class="mt-1 text-sm text-gray-600">Lengkapi data distributor atau vendor pemasok barang untuk toko petshop kamu.</p>

        <div class="mt-8 grid grid-cols-1 gap-x-6 gap-y-6 sm:grid-cols-6">
          
          <!-- Nama Supplier -->
          <div class="sm:col-span-3">
            <label for="name" class="block text-sm/6 font-medium text-gray-900">
              Nama Supplier <span class="text-red-500">*</span>
            </label>
            <div class="mt-2">
              <input id="name" 
                     type="text" 
                     name="name" 
                     value="{{ old('name') }}" 
                     required
                     placeholder="Contoh: PT. Pet Food Indonesia"
                     class="block w-full rounded-md bg-white px-3 py-2 text-base text-gray-900 border border-gray-300 placeholder:text-gray-400 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6" />
            </div>
          </div>

          <!-- Nama Kontak (PIC) -->
          <div class="sm:col-span-3">
            <label for="contact_person" class="block text-sm/6 font-medium text-gray-900">
              Nama Kontak (PIC)
            </label>
            <div class="mt-2">
              <input id="contact_person" 
                     type="text" 
                     name="contact_person" 
                     value="{{ old('contact_person') }}" 
                     placeholder="Contoh: Budi Santoso"
                     class="block w-full rounded-md bg-white px-3 py-2 text-base text-gray-900 border border-gray-300 placeholder:text-gray-400 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6" />
            </div>
          </div>

          <!-- No. Telepon -->
          <div class="sm:col-span-3">
            <label for="phone" class="block text-sm/6 font-medium text-gray-900">
              No. Telepon / Whatsapp
            </label>
            <div class="mt-2">
              <input id="phone" 
                     type="text" 
                     name="phone" 
                     value="{{ old('phone') }}" 
                     placeholder="Contoh: 081234567890"
                     class="block w-full rounded-md bg-white px-3 py-2 text-base text-gray-900 border border-gray-300 placeholder:text-gray-400 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6" />
            </div>
          </div>

          <!-- Email -->
          <div class="sm:col-span-3">
            <label for="email" class="block text-sm/6 font-medium text-gray-900">
              Email Address
            </label>
            <div class="mt-2">
              <input id="email" 
                     type="email" 
                     name="email" 
                     value="{{ old('email') }}" 
                     placeholder="supplier@example.com"
                     class="block w-full rounded-md bg-white px-3 py-2 text-base text-gray-900 border border-gray-300 placeholder:text-gray-400 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6" />
            </div>
          </div>

          <!-- Alamat -->
          <div class="col-span-full">
            <label for="address" class="block text-sm/6 font-medium text-gray-900">
              Alamat Lengkap Gudang / Kantor
            </label>
            <div class="mt-2">
              <textarea id="address" 
                        name="address" 
                        rows="3" 
                        placeholder="Tuliskan alamat domisili atau tempat gudang supplier..."
                        class="block w-full rounded-md bg-white px-3 py-2 text-base text-gray-900 border border-gray-300 placeholder:text-gray-400 focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6">{{ old('address') }}</textarea>
            </div>
            <p class="mt-2 text-xs text-gray-500">Sertakan detail alamat untuk memudahkan pengiriman barang dan penagihan utang.</p>
          </div>

        </div>
      </div>
    </div>

    <!-- 🟢 3. TOMBOL AKSI (CANCEL & SAVE) -->
    <div class="mt-6 flex items-center justify-end gap-x-4">
      <a href="{{ route('admin.suppliers.index') }}" 
         class="rounded-lg bg-white px-4 py-2 text-sm font-semibold text-gray-900 border border-gray-300 hover:bg-gray-50 transition">
        Batal
      </a>
      <button type="submit" 
              class="rounded-lg bg-indigo-600 px-5 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 transition">
        Simpan Supplier
      </button>
    </div>
  </form>
</div>
@endsection