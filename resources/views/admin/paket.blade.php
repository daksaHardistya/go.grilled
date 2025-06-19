<x-layoute-admin>
    <x-navbar-admin></x-navbar-admin><br>

    <div class="max-w-6xl mx-auto">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold text-gray-800">Daftar Paket</h1>
            <a href="{{ route('admin.paket.create') }}" class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700">+ Tambah Paket</a>
        </div>

        <div class="bg-white shadow-lg rounded-lg overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-red-600 text-white">
                    <tr>
                        <th class="px-6 py-3 text-left text-sm font-semibold">Kode</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold">Nama</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold">Kategori</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold">Harga</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold">Stok</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold">Gambar</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 bg-white text-sm">
                    @forelse ($paketList as $paket)
                        <tr>
                            <td class="px-6 py-4">{{ $paket->kode_paket }}</td>
                            <td class="px-6 py-4">{{ $paket->nama_paket }}</td>
                            <td class="px-6 py-4">{{ $paket->kategori_paket }}</td>
                            <td class="px-6 py-4">Rp{{ number_format($paket->harga_paket, 0, ',', '.') }}</td>
                            <td class="px-6 py-4">
                                <form action="{{ route('admin.paket.stock.update', $paket->id_paket) }}" method="POST" class="flex items-center space-x-2">
                                    @csrf
                                    <input type="number" name="stock_paket" value="{{ $paket->stock_paket }}" class="w-20 border border-gray-300 p-1 rounded text-center">
                                    <button type="submit" class="text-sm text-white bg-green-600 px-2 py-1 rounded hover:bg-green-700">Update</button>
                                </form>
                            </td>
                            <td class="px-6 py-4">
                                @if ($paket->image_paket)
                                    <img src="{{ asset('storage/' . $paket->image_paket) }}" class="w-16 h-16 object-cover rounded cursor-pointer">
                                @else
                                    <span class="text-gray-400 italic">Tidak ada gambar</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 space-y-2">
                                <a href="{{ route('admin.paket.edit', $paket->id_paket) }}" class="block text-blue-600 hover:underline">Edit</a>
                                <a href="{{ route('admin.paket.delete', $paket->id_paket) }}" onclick="return confirm('Yakin hapus paket?')" class="block text-red-600 hover:underline">Hapus</a>
                                <button onclick="openDetail('{{ $paket->nama_paket }}', '{{ $paket->detail_paket }}', '{{ asset('storage/' . $paket->image_paket) }}')" class="block text-green-600 hover:underline">Lihat Detail</button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-6 py-4 text-center text-gray-500">Tidak ada paket tersedia.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal Detail Paket -->
    <div id="modalDetail" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
        <div class="bg-white rounded-lg shadow-lg max-w-md w-full p-6 relative">
            <button onclick="closeDetail()" class="absolute top-2 right-2 text-gray-500 hover:text-red-600">&times;</button>
            <h2 id="detailNama" class="text-xl font-bold text-gray-800 mb-2"></h2>
            <img id="detailImage" src="" class="w-full h-48 object-cover rounded mb-4">
            <p id="detailDeskripsi" class="text-gray-600"></p>
        </div>
    </div>

    <script>
        function openDetail(nama, detail, image) {
            document.getElementById('detailNama').textContent = nama;
            document.getElementById('detailDeskripsi').textContent = detail;
            document.getElementById('detailImage').src = image;
            document.getElementById('modalDetail').classList.remove('hidden');
            document.getElementById('modalDetail').classList.add('flex');
        }

        function closeDetail() {
            document.getElementById('modalDetail').classList.add('hidden');
            document.getElementById('modalDetail').classList.remove('flex');
        }
    </script>
</x-layoute-admin>