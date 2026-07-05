@extends('dashboard')

@section('content')
<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8 space-y-6">
    
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
        <h2 class="text-xl font-bold text-gray-800 mb-4">Input Transaksi Baru</h2>
        
        <form method="POST" action="{{ route('transaksi.insert') }}" class="space-y-4">
            @csrf
            
            <div>
                <label class="block text-sm font-medium text-gray-700">Pilih Pelanggan</label>
                <select name="customer_id" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                    <option value="">-- Pilih Data Pelanggan --</option>
                    @foreach($customers as $customer)
                        <option value="{{ $customer->id }}">{{ $customer->name }} ({{ $customer->email }})</option>
                    @endforeach
                </select>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Nama Produk</label>
                    <input type="text" name="product_name" required placeholder="Contoh: Sepatu" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Quantity (Jumlah)</label>
                    <input type="number" name="quantity" required placeholder="0" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Harga Satuan (Rp)</label>
                    <input type="number" name="price" required placeholder="0" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                </div>
            </div>

            <div class="pt-2">
                <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-md shadow-sm transition duration-150 ease-in-out">
                    Simpan Transaksi
                </button>
            </div>
        </form>
    </div>

    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
        <h2 class="text-xl font-bold text-gray-800 mb-4">Daftar Transaksi Masuk</h2>
        
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama Pelanggan</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Produk</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">QTY</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Harga Satuan</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total Harga</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($transactions as $transaksi)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $transaksi->customer->name ?? 'Pelanggan Terhapus' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $transaksi->product_name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $transaksi->quantity }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Rp {{ number_format($transaksi->price, 0, ',', '.') }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-blue-600">Rp {{ number_format($transaksi->price * $transaksi->quantity, 0, ',', '.') }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <form action="{{ route('transaksi.delete', $transaksi->id) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900 bg-red-100 px-3 py-1 rounded text-xs" onclick="return confirm('Hapus transaksi ini?')">Delete</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-4 text-center text-sm text-gray-500">Belum ada transaksi recorded.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection