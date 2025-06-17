<x-layoute-admin>
    <x-navbar-admin></x-navbar-admin>

    <div class="p-6">
        <h1 class="text-3xl font-extrabold text-gray-800 mb-8">Admin Go.Grilled Singaraja</h1>

        <!-- Statistik -->
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6 mb-12">
            <!-- Order Masuk -->
            <div class="bg-white rounded-2xl shadow-lg p-6 border border-blue-100">
                <h2 class="text-md font-semibold text-gray-600 mb-1">Order Masuk</h2>
                <p class="text-4xl font-bold text-blue-600">{{ $totalOrders }}</p>
                <p class="text-sm text-gray-400 mt-1">Order Keseluruhan</p>
            </div>

            <!-- Stok Paket -->
            <div class="bg-white rounded-2xl shadow-lg p-6 border border-green-100">
                <h2 class="text-md font-semibold text-gray-600 mb-1">Stok Paket</h2>
                <p class="text-4xl font-bold text-green-600">{{ $stokPaket }}</p>
                <p class="text-sm text-gray-400 mt-1">Paket tersedia</p>
            </div>

            <!-- Stok Produk -->
            <div class="bg-white rounded-2xl shadow-lg p-6 border border-yellow-100">
                <h2 class="text-md font-semibold text-gray-600 mb-1">Stok Produk</h2>
                <p class="text-4xl font-bold text-yellow-500">{{ $stokProduk }}</p>
                <p class="text-sm text-gray-400 mt-1">Produk satuan tersedia</p>
            </div>

            <!-- Pembukuan Hari Ini -->
            <div class="bg-white rounded-2xl shadow-lg p-6 border border-purple-100">
                <h2 class="text-md font-semibold text-gray-600 mb-1">Pembukuan</h2>
                <p class="text-3xl font-bold text-purple-600">
                    Rp {{ number_format($totalPendapatanBulanIni, 0, ',', '.') }}
                </p>
                <p class="text-sm text-gray-400 mt-1">Penjualan bulan ini</p>
            </div>
        </div>
        <!-- Cuplikan Order Terbaru -->
        <div class="bg-white rounded-2xl shadow-lg p-6">
            <h2 class="text-xl font-bold text-gray-700 mb-4">🕒 Order Terbaru</h2>
            <div class="overflow-auto">
                <table class="w-full text-sm text-gray-700 border border-gray-200 rounded-md overflow-hidden">
                    <thead class="bg-gray-100 uppercase text-xs font-semibold text-gray-600 text-center">
                        <tr>
                            <th class="py-3 px-4">No Transaksi</th>
                            <th class="py-3 px-4">Nama Pelanggan</th>
                            <th class="py-3 px-4">Total</th>
                            <th class="py-3 px-4">Tanggal</th>
                            <th class="py-3 px-4">Status</th>
                        </tr>
                    </thead>
                    <tbody class="text-center">
                        @forelse ($orders as $order)
                            <tr class="border-t hover:bg-gray-50">
                                <td class="py-3 px-4">{{ $order->nomor_transaksi }}</td>
                                <td class="py-2 px-4">
                                    @if($order->data_pelanggan)
                                        <span class="text-gray-700">{{ $order->data_pelanggan->nama_pel }}</span>
                                    @else
                                        <span class="text-gray-400">-</span>
                                    @endif
                                </td>
                                <td class="py-3 px-4 font-semibold text-green-600">
                                    Rp{{ number_format($order->total_belanjaan, 0, ',', '.') }}
                                </td>
                                <td class="py-3 px-4">
                                    {{ \Carbon\Carbon::parse($order->created_at)->format('d M Y, H:i') }}
                                </td>
                                <td class="py-3 px-4">
                                    @php $status = $order->status_order; @endphp
                                    @if ($status === 'pending')
                                        <span class="px-3 py-1 rounded-full text-xs bg-yellow-100 text-yellow-700">Menunggu Pembayaran</span>
                                    @elseif ($status === 'proses')
                                        <span class="px-3 py-1 rounded-full text-xs bg-orange-100 text-orange-700">Diproses</span>
                                    @elseif ($status === 'dikirim')
                                        <span class="px-3 py-1 rounded-full text-xs bg-purple-100 text-purple-700">Dikirim</span>
                                    @elseif ($status === 'booked')
                                        <span class="px-3 py-1 rounded-full text-xs bg-teal-100 text-teal-700">Sedang Digunakan</span>
                                    @elseif ($status === 'expired')
                                        <span class="px-3 py-1 rounded-full text-xs bg-blue-100 text-blue-700">Belum Dikembalikan</span>
                                    @elseif ($status === 'selesai')
                                        <span class="px-3 py-1 rounded-full text-xs bg-green-100 text-green-700">Selesai</span>
                                    @elseif ($status === 'batal')
                                        <span class="px-3 py-1 rounded-full text-xs bg-red-100 text-red-700">Dibatalkan</span>
                                    @else
                                        <span class="px-3 py-1 rounded-full text-xs bg-gray-100 text-gray-700">{{ ucfirst($status) }}</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-6 text-gray-400">Belum ada order terbaru.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-layoute-admin>
