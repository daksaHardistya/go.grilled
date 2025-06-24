<x-layoute-admin>
    <x-navbar-admin></x-navbar-admin><br>
    <div class="max-w-xl mx-auto bg-white p-6 rounded shadow">
        <h2 class="text-2xl font-semibold mb-4 text-gray-800">Tambah Paket Baru</h2>
        <form action="{{ route('admin.paket.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="space-y-4">
                <div>
                    <label class="block text-gray-700">Kode Paket</label>
                    <input type="text" name="kode_paket" class="w-full border border-gray-300 p-2 rounded" required>
                </div>

                <div>
                    <label class="block text-gray-700">Nama Paket</label>
                    <input type="text" name="nama_paket" class="w-full border border-gray-300 p-2 rounded" required>
                </div>

                <div>
                    <label class="block text-gray-700">Detail Paket</label>
                    <textarea name="detail_paket" class="w-full border border-gray-300 p-2 rounded" rows="4" required></textarea>
                </div>

                <div>
                    <label class="block text-gray-700">Kategori</label>
                    <select name="kategori_paket" class="w-full border border-gray-300 p-2 rounded" required>
                        <option value="">-- Pilih Kategori --</option>
                        <option value="basic">Paket Basic</option>
                        <option value="special">Paket Special</option>
                        <option value="family">Paket Family</option>
                    </select>
                </div>


                <div>
                    <label class="block text-gray-700">Harga</label>
                    <input type="number" name="harga_paket" class="w-full border border-gray-300 p-2 rounded" required>
                </div>

                <div>
                    <label class="block text-gray-700">Stok</label>
                    <input type="number" name="stock_paket" class="w-full border border-gray-300 p-2 rounded" required>
                </div>

                <div>
                    <label class="block text-gray-700">Gambar</label>
                    <input type="file" name="image_paket" class="w-full border border-gray-300 p-2 rounded">
                </div>
            </div>
            <div class="mt-6 text-right">
                <button class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700">Simpan</button>
            </div>
        </form>
    </div>
</x-layoute-admin>
