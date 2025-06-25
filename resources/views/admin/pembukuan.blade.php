<x-layoute-admin>
    <x-navbar-admin></x-navbar-admin>

    <div class="max-w-7xl mx-auto py-8 px-4">
        <h1 class="text-3xl font-bold mb-6 text-gray-800">Pembukuan Transaksi Masuk</h1>

        <!-- FILTER RENTANG -->
        <form method="GET" class="mb-6 grid grid-cols-1 md:grid-cols-5 gap-4 items-end">
            <div>
                <label class="text-sm text-gray-600 font-medium">Dari Tanggal</label>
                <input type="date" name="tanggal_awal" id="tanggal_awal" value="{{ request('tanggal_awal') }}"
                    class="border rounded-lg p-3 w-full shadow-sm">
            </div>
            <div>
                <label class="text-sm text-gray-600 font-medium">Sampai Tanggal</label>
                <input type="date" name="tanggal_akhir" id="tanggal_akhir" value="{{ request('tanggal_akhir') }}"
                    class="border rounded-lg p-3 w-full shadow-sm">
            </div>
            <div class="md:col-span-2">
                <label class="text-sm text-gray-600 font-medium block mb-1">Preset Rentang</label>
                <div class="flex gap-4 text-sm text-gray-700">
                    <label><input type="radio" name="preset" value="today" onclick="setTanggalPreset('today')"
                            {{ request('preset') == 'today' ? 'checked' : '' }}> Hari Ini</label>
                    <label><input type="radio" name="preset" value="week" onclick="setTanggalPreset('week')"
                            {{ request('preset') == 'week' ? 'checked' : '' }}> Minggu Ini</label>
                    <label><input type="radio" name="preset" value="month" onclick="setTanggalPreset('month')"
                            {{ request('preset') == 'month' ? 'checked' : '' }}> Bulan Ini</label>
                </div>
            </div>
            <div>
                <button type="submit"
                    class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition w-full">
                    Filter
                </button>
            </div>
        </form>

        <!-- JavaScript untuk preset tanggal -->
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

        <!-- TAMPILKAN RENTANG WAKTU -->
        @if (request('tanggal_awal') && request('tanggal_akhir'))
            <p class="text-sm text-gray-600 mb-4">
                Menampilkan transaksi dari
                <span class="font-semibold">{{ request('tanggal_awal') }}</span>
                sampai
                <span class="font-semibold">{{ request('tanggal_akhir') }}</span>
            </p>
        @endif

        <!-- TOTAL -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
            <div class="bg-blue-50 border-l-4 border-blue-600 p-4 rounded shadow">
                <h3 class="text-blue-700 font-semibold">Total Transfer</h3>
                <p class="text-blue-800 font-bold text-lg">Rp {{ number_format($totalTransfer, 0, ',', '.') }}</p>
            </div>
            <div class="bg-green-50 border-l-4 border-green-600 p-4 rounded shadow">
                <h3 class="text-green-700 font-semibold">Total Cash</h3>
                <p class="text-green-800 font-bold text-lg">Rp {{ number_format($totalCash, 0, ',', '.') }}</p>
            </div>
            <div class="bg-gray-100 border-l-4 border-gray-500 p-4 rounded shadow">
                <h3 class="text-gray-700 font-semibold">Total Keseluruhan</h3>
                <p class="text-black font-extrabold text-xl">Rp {{ number_format($totalSemua, 0, ',', '.') }}</p>
            </div>
        </div>

        <!-- TABEL TRANSFER -->
        @if (count($transferOrders))
            <div class="mb-10">
                <h2 class="text-lg font-semibold text-blue-700 mb-2">Pembayaran via Transfer</h2>
                <div class="overflow-auto rounded border">
                    <table class="min-w-full bg-white text-sm">
                        <thead class="bg-blue-100 text-blue-800 font-medium">
                            <tr>
                                <th class="py-2 px-4 border">Tanggal</th>
                                <th class="py-2 px-4 border">Nama</th>
                                <th class="py-2 px-4 border">Transaksi</th>
                                <th class="py-2 px-4 border">Total</th>
                                <th class="py-2 px-4 border">Bukti</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($transferOrders as $order)
                                <tr class="border-t hover:bg-gray-50">
                                    <td class="py-2 px-4">{{ $order->created_at->format('Y-m-d') }}</td>
                                    <td class="py-2 px-4">{{ $order->data_pelanggan->nama_pel }}</td>
                                    <td class="py-2 px-4">{{ $order->nomor_transaksi }}</td>
                                    <td class="py-2 px-4">Rp {{ number_format($order->total_belanjaan, 0, ',', '.') }}
                                    </td>
                                    <td class="py-2 px-4">
                                        <a href="{{ asset('storage/bukti_transfer/' . $order->bukti_pembayaran) }}"
                                            class="text-blue-600 hover:underline" target="_blank">Lihat</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif

        <!-- TABEL CASH -->
        @if (count($cashOrders))
            <div>
                <h2 class="text-lg font-semibold text-green-700 mb-2">Pembayaran via Cash</h2>
                <div class="overflow-auto rounded border">
                    <table class="min-w-full bg-white text-sm">
                        <thead class="bg-green-100 text-green-800 font-medium">
                            <tr>
                                <th class="py-2 px-4 border">Tanggal</th>
                                <th class="py-2 px-4 border">Nama</th>
                                <th class="py-2 px-4 border">Transaksi</th>
                                <th class="py-2 px-4 border">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($cashOrders as $order)
                                <tr class="border-t hover:bg-gray-50">
                                    <td class="py-2 px-4">{{ $order->created_at->format('Y-m-d') }}</td>
                                    <td class="py-2 px-4">{{ $order->data_pelanggan->nama_pel }}</td>
                                    <td class="py-2 px-4">{{ $order->nomor_transaksi }}</td>
                                    <td class="py-2 px-4">Rp {{ number_format($order->total_belanjaan, 0, ',', '.') }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif

        <!-- JIKA TIDAK ADA TRANSAKSI -->
        @if (count($transferOrders) == 0 && count($cashOrders) == 0)
            <div class="text-center mt-10 text-gray-500">
                <p>Tidak ada transaksi yang ditemukan untuk rentang tanggal ini.</p>
            </div>
        @endif
    </div>
</x-layoute-admin>
