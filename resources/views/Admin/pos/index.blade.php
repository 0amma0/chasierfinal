@extends('layouts.admin')

@section('header_title', 'Sistem Kasir (POS)')

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

    <!-- Katalog Produk -->
    <div class="lg:col-span-7 space-y-4">
        <div class="bg-white p-4 rounded-2xl shadow-sm border border-gray-100">
            <form method="GET" action="{{ route('admin.pos.index') }}" class="flex gap-2">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Scan barcode / cari nama produk..." class="w-full rounded-xl border border-gray-200 bg-gray-50/50 py-2.5 px-4 text-sm focus:bg-white focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 transition">
                <button type="submit" class="px-4 py-2.5 bg-gray-900 text-white text-xs font-bold rounded-xl hover:bg-gray-800 transition">Cari</button>
                @if(request()->filled('search'))
                    <a href="{{ route('admin.pos.index') }}" class="px-3 py-2.5 bg-gray-100 text-gray-600 text-xs font-bold rounded-xl hover:bg-gray-200 transition">Reset</a>
                @endif
            </form>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
            @forelse($products as $product)
                <div onclick="addToCart({{ $product->id }}, '{{ addslashes($product->name) }}', {{ $product->sell_price }}, {{ $product->stock }})" class="bg-white p-3.5 rounded-2xl border border-gray-100 shadow-sm hover:border-indigo-400 hover:shadow-md transition cursor-pointer flex flex-col justify-between">
                    <div>
                        <span class="text-[10px] font-bold text-indigo-600 uppercase bg-indigo-50 px-2 py-0.5 rounded-md">Stok: {{ $product->stock }}</span>
                        <h4 class="font-bold text-gray-900 text-sm mt-2 line-clamp-2">{{ $product->name }}</h4>
                    </div>
                    <div class="mt-3 pt-2 border-t border-gray-50 flex items-center justify-between">
                        <span class="font-bold text-sm text-gray-900">Rp {{ number_format($product->sell_price, 0, ',', '.') }}</span>
                        <span class="w-7 h-7 bg-indigo-600 text-white rounded-lg flex items-center justify-center font-bold text-xs hover:bg-indigo-700">+</span>
                    </div>
                </div>
            @empty
                <div class="col-span-full bg-white p-8 rounded-2xl text-center text-gray-400 border border-gray-100">
                    Produk tidak ditemukan atau stok habis.
                </div>
            @endforelse
        </div>
    </div>

    <!-- Keranjang & Form Pembayaran -->
    <div class="lg:col-span-5">
        <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100 space-y-4 sticky top-6">
            <div class="flex items-center justify-between border-b border-gray-100 pb-3">
                <h3 class="font-bold text-gray-900 text-base">Item Penjualan</h3>
                <span class="text-xs text-gray-500">Sesi ID: #{{ $activeSession->id }}</span>
            </div>

            <div id="cart-items-container" class="space-y-2 max-h-60 overflow-y-auto pr-1 text-sm divide-y divide-gray-50">
                <p class="text-center text-gray-400 py-6 text-xs">Keranjang masih kosong.</p>
            </div>

            <hr class="border-gray-100">

            <!-- Member Check -->
            <div>
                <label for="member_phone_input" class="block text-xs font-semibold text-gray-700">Nomor HP Member (Opsional):</label>
                <div class="flex gap-2 mt-1">
                    <input type="text" id="member_phone_input" oninput="document.getElementById('hidden_member_phone').value = this.value.trim()" placeholder="Contoh: 081234567890" class="flex-1 p-2 border border-gray-200 rounded-xl text-xs focus:ring-1 focus:ring-indigo-500 focus:outline-none">
                    <button type="button" onclick="checkMember()" class="px-3 py-2 bg-gray-800 text-white rounded-xl text-xs font-semibold hover:bg-gray-700 transition">Cek Member</button>
                </div>
                <div id="member-status-box" class="hidden mt-2 p-2 rounded-xl text-xs font-semibold"></div>
            </div>

            <!-- Form Submit POS -->
            <form action="{{ route('admin.pos.store') }}" method="POST" id="pos-form" class="space-y-3 pt-2">
                @csrf
                <div id="hidden-cart-inputs"></div>
                <input type="hidden" name="member_phone" id="hidden_member_phone" value="">

                <div class="bg-gray-50 p-3.5 rounded-xl space-y-1.5 text-xs border border-gray-100">
                    <div class="flex justify-between text-gray-600">
                        <span>Subtotal:</span>
                        <span id="label-subtotal" class="font-semibold">Rp 0</span>
                    </div>
                    <div class="flex justify-between text-emerald-600">
                        <span>Diskon Member:</span>
                        <span id="label-discount" class="font-semibold">- Rp 0</span>
                    </div>
                    <hr class="border-gray-200 my-1">
                    <div class="flex justify-between text-sm font-bold text-gray-900">
                        <span>Total Bayar:</span>
                        <span id="label-grand-total" class="text-indigo-600">Rp 0</span>
                    </div>
                </div>

                <div>
                    <label for="paid_amount" class="block text-xs font-semibold text-gray-700">Uang Diterima (Rp):</label>
                    <input type="number" name="paid_amount" id="paid_amount" required min="0" placeholder="0" oninput="calculateChange()" class="w-full p-2.5 border border-gray-300 rounded-xl text-sm mt-1 font-bold text-gray-900 focus:ring-1 focus:ring-indigo-500 focus:outline-none">
                </div>

                <div class="flex justify-between items-center px-1 text-xs">
                    <span class="text-gray-500">Kembalian:</span>
                    <span id="label-change" class="font-bold text-gray-900 text-sm">Rp 0</span>
                </div>

                <button type="submit" id="btn-submit-pos" disabled class="w-full bg-indigo-600 text-white py-3 rounded-xl text-xs font-bold hover:bg-indigo-700 transition disabled:opacity-50 disabled:cursor-not-allowed shadow-md hover:shadow-indigo-500/20">
                    Bayar & Cetak Struk
                </button>
            </form>
        </div>
    </div>
</div>

<script>
let cart = [];
let discountPercent = 0;

function addToCart(id, name, price, maxStock) {
    let item = cart.find(i => i.id === id);
    if (item) {
        if (item.qty < maxStock) item.qty++;
        else alert('Stok produk telah mencapai batas maksimal!');
    } else {
        cart.push({ id, name, price, qty: 1, maxStock });
    }
    renderCart();
}

function updateQty(id, change) {
    let item = cart.find(i => i.id === id);
    if (item) {
        item.qty += change;
        if (item.qty <= 0) cart = cart.filter(i => i.id !== id);
        else if (item.qty > item.maxStock) {
            item.qty = item.maxStock;
            alert('Stok produk tidak mencukupi!');
        }
    }
    renderCart();
}

function renderCart() {
    const container = document.getElementById('cart-items-container');
    const hiddenInputs = document.getElementById('hidden-cart-inputs');
    container.innerHTML = '';
    hiddenInputs.innerHTML = '';

    if (cart.length === 0) {
        container.innerHTML = '<p class="text-center text-gray-400 py-6 text-xs">Keranjang masih kosong.</p>';
        updateTotals(0);
        document.getElementById('btn-submit-pos').disabled = true;
        return;
    }

    let subtotal = 0;
    cart.forEach((item, index) => {
        subtotal += item.price * item.qty;
        container.innerHTML += `
            <div class="flex items-center justify-between py-2">
                <div class="flex-1 min-w-0 pr-2">
                    <p class="font-bold text-gray-800 text-xs truncate">$$\{item.name}</p>
                    <p class="text-[11px] text-gray-400">Rp $${item.price.toLocaleString('id-ID')} x $${item.qty}</p>
                </div>
                <div class="flex items-center gap-1.5">
                    <button type="button" onclick="updateQty($${item.id}, -1)" class="w-6 h-6 bg-gray-100 hover:bg-gray-200 text-gray-800 rounded font-bold text-xs">-</button>
                    <span class="font-bold text-xs w-4 text-center">$${item.qty}</span>
                    <button type="button" onclick="updateQty($${item.id}, 1)" class="w-6 h-6 bg-gray-100 hover:bg-gray-200 text-gray-800 rounded font-bold text-xs">+</button>
                </div>
            </div>`;

        hiddenInputs.innerHTML += `
            <input type="hidden" name="items[$index][product_id]" value="$${item.id}">
            <input type="hidden" name="items[$index][qty]" value="$${item.qty}">`;
    });

    updateTotals(subtotal);
    document.getElementById('btn-submit-pos').disabled = false;
}

async function checkMember() {
    const phone = document.getElementById('member_phone_input').value.trim();
    const statusBox = document.getElementById('member-status-box');
    const hiddenPhoneInput = document.getElementById('hidden_member_phone');

    if (!phone) {
        statusBox.className = 'mt-2 p-2 rounded-xl text-xs font-semibold bg-amber-50 text-amber-700 block';
        statusBox.innerText = 'Masukkan nomor HP member terlebih dahulu.';
        discountPercent = 0;
        hiddenPhoneInput.value = '';
        renderCart();
        return;
    }

    try {
        const res = await fetch(`{{ route('admin.pos.check-member') }}?phone=$${encodeURIComponent(phone)}`);
        const data = await res.json();
        
        statusBox.className = `mt-2 p-2 rounded-xl text-xs font-semibold block $${data.success ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-red-50 text-red-600 border border-red-200'}`;
        statusBox.innerText = data.message;
        discountPercent = data.success ? parseFloat(data.discount_percent) : 0;
        hiddenPhoneInput.value = phone;
    } catch (err) {
        statusBox.className = 'mt-2 p-2 rounded-xl text-xs font-semibold bg-red-50 text-red-600 border border-red-200 block';
        statusBox.innerText = 'Nomor HP tidak terdaftar sebagai member.';
        discountPercent = 0;
        hiddenPhoneInput.value = phone;
    }
    renderCart();
}

function updateTotals(subtotal) {
    const discountAmount = (subtotal * discountPercent) / 100;
    const grandTotal = subtotal - discountAmount;

    document.getElementById('label-subtotal').innerText = 'Rp ' + subtotal.toLocaleString('id-ID');
    document.getElementById('label-discount').innerText = '- Rp ' + discountAmount.toLocaleString('id-ID');
    document.getElementById('label-grand-total').innerText = 'Rp ' + grandTotal.toLocaleString('id-ID');

    calculateChange();
}

function calculateChange() {
    const paidInput = parseFloat(document.getElementById('paid_amount').value) || 0;
    const subtotal = cart.reduce((sum, item) => sum + (item.price * item.qty), 0);
    const discountAmount = (subtotal * discountPercent) / 100;
    const grandTotal = subtotal - discountAmount;
    const change = paidInput - grandTotal;

    const changeLabel = document.getElementById('label-change');
    if (change >= 0) {
        changeLabel.innerText = 'Rp ' + change.toLocaleString('id-ID');
        changeLabel.className = 'font-bold text-emerald-600 text-sm';
    } else {
        changeLabel.innerText = 'Rp 0 (Kurang Rp ' + Math.abs(change).toLocaleString('id-ID') + ')';
        changeLabel.className = 'font-bold text-red-500 text-xs';
    }
}
</script>
@endsection