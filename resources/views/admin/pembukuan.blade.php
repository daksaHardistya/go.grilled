<x-layoute-admin>
    <x-navbar-admin></x-navbar-admin>
    <div class="max-w-7xl mx-auto py-8 px-4">
        <h1 class="text-3xl font-extrabold mb-6 text-gray-800">Pembukuan Order (Status: Booked)</h1>

        <!-- Filter Rentang Tanggal -->
        <form method="GET" class="mb-6 grid grid-cols-1 md:grid-cols-5 gap-4 items-end">
            <div>
                <label class="text-sm text-gray-600 font-medium">Dari Tanggal</label>
                <input type="date" id="tanggal_awal" name="tanggal_awal" value="{{ request('tanggal_awal') }}" class="border rounded-lg p-3 w-full text-sm text-gray-700 shadow-md hover:shadow-lg transition duration-300">
            </div>
            <div>
                <label class="text-sm text-gray-600 font-medium">Sampai Tanggal</label>
                <input type="date" id="tanggal_akhir" name="tanggal_akhir" value="{{ request('tanggal_akhir') }}" class="border rounded-lg p-3 w-full text-sm text-gray-700 shadow-md hover:shadow-lg transition duration-300">
            </div>
            <div class="md:col-span-2">
                <label class="text-sm text-gray-600 font-medium block mb-1">Preset Rentang</label>
                <div class="flex flex-col md:flex-row gap-2 text-sm text-gray-700">
                    <label><input type="radio" name="preset" value="today" onclick="setTanggalPreset('today')" {{ request('preset') == 'today' ? 'checked' : '' }}> Hari Ini</label>
                    <label><input type="radio" name="preset" value="week" onclick="setTanggalPreset('week')" {{ request('preset') == 'week' ? 'checked' : '' }}> 1 Minggu</label>
                    <label><input type="radio" name="preset" value="month" onclick="setTanggalPreset('month')" {{ request('preset') == 'month' ? 'checked' : '' }}> 1 Bulan</label>
                </div>
            </div>
            <div>
                <button type="submit" class="bg-blue-600 text-white rounded-lg px-4 py-3 mt-1 hover:bg-blue-700 transition duration-300 w-full">Filter</button>
            </div>
        </form>

        <!-- JS untuk mengisi tanggal otomatis -->
        <script>
            function setTanggalPreset(preset) {
                const today = new Date();
                const end = today.toISOString().split('T')[0];
                let start;

                if (preset === 'today') {
                    start = end;
                } else if (preset === 'week') {
                    const lastWeek = new Date(today);
                    lastWeek.setDate(today.getDate() - 6);
                    start = lastWeek.toISOString().split('T')[0];
                } else if (preset === 'month') {
                    const lastMonth = new Date(today);
                    lastMonth.setMonth(today.getMonth() - 1);
                    start = lastMonth.toISOString().split('T')[0];
                }

                document.getElementById('tanggal_awal').value = start;
                document.getElementById('tanggal_akhir').value = end;
            }
        </script>

        <!-- Total Transfer -->
        <p class="text-sm text-gray-600 mb-2">
            <span class="font-semibold text-blue-700">Total Transfer:</span>
            <span class="font-semibold text-blue-600">Rp {{ number_format($totalTransfer, 0, ',', '.') }}</span>
        </p>

        <!-- TABEL TRANSFER -->
        <div class="mb-10">
            <h2 class="text-xl font-semibold text-blue-700 mb-4">Pembayaran Transfer</h2>
            <div class="overflow-auto rounded-lg shadow-lg border border-gray-200">
                <table class="min-w-full bg-white">
                    <thead class="bg-gray-100 text-left text-sm font-semibold text-gray-700">
                        <tr>
                            <th class="py-3 px-4 border">Tanggal</th>
                            <th class="py-3 px-4 border">Nama Pelanggan</th>
                            <th class="py-3 px-4 border">Nomor Transaksi</th>
                            <th class="py-3 px-4 border">Total</th>
                            <th class="py-3 px-4 border">Bukti</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($transferOrders as $order)
                            <tr class="border-t text-sm hover:bg-gray-50">
                                <td class="py-3 px-4">{{ $order->created_at->format('Y-m-d') }}</td>
                                <td class="py-3 px-4">{{ $order->data_pelanggan->nama_pel }}</td>
                                <td class="py-3 px-4">{{ $order->nomor_transaksi }}</td>
                                <td class="py-3 px-4">Rp {{ number_format($order->total_belanjaan, 0, ',', '.') }}</td>
                                <td class="py-3 px-4">
                                    <a href="{{ asset('storage/bukti_transfer/' . $order->bukti_pembayaran) }}" class="text-blue-600 underline hover:text-blue-800" target="_blank">Lihat Bukti</a>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="text-center py-4 text-gray-400">Tidak ada data.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Total Cash -->
        <p class="text-sm text-gray-600 mb-2">
            <span class="font-semibold text-green-700">Total Cash:</span>
            <span class="font-semibold text-green-600">Rp {{ number_format($totalCash, 0, ',', '.') }}</span>
        </p>

        <!-- TABEL CASH -->
        <div>
            <h2 class="text-xl font-semibold text-green-700 mb-4">Pembayaran Cash</h2>
            <div class="overflow-auto rounded-lg shadow-lg border border-gray-200">
                <table class="min-w-full bg-white">
                    <thead class="bg-gray-100 text-left text-sm font-semibold text-gray-700">
                        <tr>
                            <th class="py-3 px-4 border">Tanggal</th>
                            <th class="py-3 px-4 border">Nama Pelanggan</th>
                            <th class="py-3 px-4 border">Nomor Transaksi</th>
                            <th class="py-3 px-4 border">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($cashOrders as $order)
                            <tr class="border-t text-sm hover:bg-gray-50">
                                <td class="py-3 px-4">{{ $order->created_at->format('Y-m-d') }}</td>
                                <td class="py-3 px-4">{{ $order->data_pelanggan->nama_pel }}</td>
                                <td class="py-3 px-4">{{ $order->nomor_transaksi }}</td>
                                <td class="py-3 px-4">Rp {{ number_format($order->total_belanjaan, 0, ',', '.') }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="text-center py-4 text-gray-400">Tidak ada data.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- TOTAL KESELURUHAN -->
        <div class="bg-gray-100 p-6 mb-8 mt-8 rounded-lg shadow-lg">
            <h2 class="text-lg font-bold text-gray-700">Total Keseluruhan Pembukuan</h2>
            <p class="text-md mt-3 text-gray-800">
                <span class="font-semibold">Transfer + Cash:</span>
                <span class="text-black font-extrabold text-xl">
                    Rp {{ number_format($totalSemua, 0, ',', '.') }}
                </span>
            </p>
        </div>
    </div>
</x-layoute-admin>
