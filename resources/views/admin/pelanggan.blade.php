<x-layoute-admin>
    <br>
    <div class="max-w-4xl mx-auto bg-white shadow-lg rounded-lg p-6">
        <h1 class="text-2xl font-bold text-gray-800 mb-6">Detail Pelanggan</h1>

        <table class="min-w-full text-sm text-gray-700">
            <tbody>
                <tr class="border-b">
                    <td class="py-2 font-semibold w-1/3">Nama</td>
                    <td class="py-2">{{ $pelanggan->nama_pel }}</td>
                </tr>
                <tr class="border-b">
                    <td class="py-2 font-semibold">Email</td>
                    <td class="py-2">{{ $pelanggan->email_pel }}</td>
                </tr>
                <tr class="border-b">
                    <td class="py-2 font-semibold">Nomor Telepon</td>
                    <td class="py-2">{{ $pelanggan->nomor_tlp }}</td>
                </tr>
                <tr>
                    <td class="py-2 font-semibold">Alamat</td>
                    <td class="py-2">{{ $pelanggan->alamat_pel }}</td>
                </tr>
            </tbody>
        </table>

        <div class="mt-6">
            <a href="{{ route('admin.order.show') }}" class="text-red-600 hover:underline">&larr; Kembali ke Daftar Order</a>
        </div>
    </div>
</x-layoute-admin>
