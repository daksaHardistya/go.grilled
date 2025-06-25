<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Http;
use App\Models\data_pelanggan;
use App\Models\menu_paket;
use App\Models\produk_satuan;
use App\Models\tabel_order;
use App\Models\tabel_orderPaket;
use App\Models\tabel_orderProduk;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;

class AdminController extends Controller
{
    public function orderShow(Request $request)
    {
        $query = tabel_order::with(['data_pelanggan', 'order_produk.produk', 'order_paket.paket']);

        // Filter berdasarkan bulan jika tersedia
        if ($request->filled('bulan')) {
            try {
                [$tahun, $bulan] = explode('-', $request->bulan);
                $query->whereYear('created_at', $tahun)->whereMonth('created_at', $bulan);
            } catch (\Exception $e) {
                // Jika format tidak valid, bisa diabaikan atau tambahkan log
            }
        }

        // Ambil semua data yang difilter (atau tidak difilter)
        $orders = $query->orderBy('created_at', 'desc')->get();

        // Kelompokkan berdasarkan nomor telepon (orang yang sama)
        $groupedOrders = $orders->groupBy(function ($order) {
            return optional($order->data_pelanggan)->nomor_tlp ?? 'Tanpa Nomor';
        });

        return view('admin.order', compact('orders', 'groupedOrders'));
    }
    public function dashboard()
    {
        $orders = tabel_order::with('data_pelanggan')->orderBy('created_at', 'desc')->get();

        $totalOrders = $orders->count();
        $stokPaket = menu_paket::sum('stock_paket');
        $stokProduk = produk_satuan::sum('stock_produk');

        // Hitung total pendapatan hari ini
        $today = Carbon::now()->format('Y-m-d');
        $totalPendapatan = tabel_order::whereDate('created_at', $today)->where('status_order', 'digunakan')->sum('total_belanjaan');

        // Hitung total pendapatan bulan ini
        $currentMonth = Carbon::now()->format('m');
        $totalPendapatanBulanIni = tabel_order::whereMonth('created_at', $currentMonth)->where('status_order', 'digunakan')->sum('total_belanjaan');
        $statusCounts = [
            'pending' => $orders->where('status_order', 'pending')->count(),
            'proses' => $orders->where('status_order', 'proses')->count(),
            'dikirim' => $orders->where('status_order', 'dikirim')->count(),
            'booked' => $orders->where('status_order', 'booked')->count(),
            'expired' => $orders->where('status_order', 'expired')->count(),
            'selesai' => $orders->where('status_order', 'selesai')->count(),
            'batal' => $orders->where('status_order', 'batal')->count(),
        ];
        return view('admin.dashboard', compact('orders', 'totalOrders', 'stokPaket', 'stokProduk', 'totalPendapatanBulanIni', 'statusCounts'));

        // return view('admin.dashboard', compact('orders', 'totalOrders', 'stokPaket', 'stokProduk', 'totalPendapatan', 'totalPendapatanBulanIni'));
    }

    public function orderUpdate(Request $request, $id)
    {
        $order = tabel_order::findOrFail($id);
        $order->status_order = $request->status_order;
        $order->save();

        $orderPaket = tabel_orderPaket::where('id_order', $id)->get();
        $orderProduk = tabel_orderProduk::where('id_order', $id)->get();

        $pelanggan = $order->data_pelanggan;
        $nomor = preg_replace('/[^0-9]/', '', $pelanggan->nomor_tlp);
        if (str_starts_with($nomor, '0')) {
            $nomor = '62' . substr($nomor, 1);
        }

        // Format isi pesan berdasarkan status
        $status = strtolower($request->status_order);
        $statusText = match ($status) {
            'dikirim' => 'telah *dikirim* dan sedang dalam perjalanan',
            'digunakan' => 'telah *diterima* dan sedang digunakan',
            'proses' => 'sedang *diproses*. Mohon ditunggu sebentar lagi.',
            'selesai' => 'telah *selesai*',
            'batal' => ' *dibatalkan*.',
            'expired' => 'telah memasuki waktu *pengembalian* alat. Silakan hubungi admin untuk melakukan pengembalian.',
            default => 'telah diperbarui',
        };
        //tidak mengirim pesan jika status pending
        if ($status === 'pending') {
            return redirect()->back()->with('success', 'Status order berhasil diperbarui.');
        }
        // Format pesan WhatsApp
        $pesan = "Halo *{$pelanggan->nama_pel}*,\n\n" . "Status pesanan Anda telah diperbarui:\n\n" . '- Paket: *' . ($orderPaket->pluck('paket.nama_paket')->implode(', ') ?: '-') . "*\n" . '- Jumlah Paket: *' . ($orderPaket->pluck('jumlah_orderPaket')->implode(', ') ?: '-') . "*\n" . '- Produk: *' . ($orderProduk->pluck('produk.nama_produk')->implode(', ') ?: '-') . "*\n" . '- Jumlah Produk: *' . ($orderProduk->pluck('jumlah_orderProduk')->implode(', ') ?: '-') . "*\n" . '- Total Belanjaan: *Rp ' . number_format($order->total_belanjaan, 0, ',', '.') . "*\n\n" . "Pesanan Anda saat ini $statusText\n\n" . 'Terima kasih telah mempercayakan harimu bersama *Go.Grilled*!';

        // Kirim pesan via WhatsApp
        kirimPesanWA($nomor, $pesan);

        return redirect()->back()->with('success', 'Status order berhasil diperbarui.');
    }

    public function pelangganDetail($id)
    {
        $pelanggan = data_pelanggan::findOrFail($id);
        return view('admin.pelanggan', compact('pelanggan'));
    }

    // CONTROL ADMIN PRODUK
    public function produkShow()
    {
        $produkList = produk_satuan::all();
        return view('admin.produk', compact('produkList'));
    }
    public function produkCreate()
    {
        return view('admin.produk.create');
    }
    // Simpan Produk
    public function produkStore(Request $request)
    {
        $data = $request->validate([
            'kode_produk' => 'required|unique:tabel_produk,kode_produk',
            'nama_produk' => 'required',
            'harga_produk' => 'required|numeric',
            'stock_produk' => 'required|integer',
            'image_produk' => 'nullable|image',
        ]);

        if ($request->hasFile('image_produk')) {
            $data['image_produk'] = $request->file('image_produk')->store('produk_satuan', 'public');
        }

        $data['created_at'] = Carbon::now();
        $data['updated_at'] = Carbon::now();

        produk_satuan::create($data);

        return redirect()->route('admin.produk.show')->with('success', 'Produk berhasil ditambahkan');
    }
    // Form Edit
    public function produkEdit($id)
    {
        $produk = produk_satuan::findOrFail($id);
        return view('admin.produk.edit', compact('produk'));
    }
    // Update Produk
    public function produkUpdate(Request $request, $id)
    {
        $produk = produk_satuan::findOrFail($id);
        $data = $request->validate([
            'nama_produk' => 'required',
            'harga_produk' => 'required|numeric',
            'stock_produk' => 'required|integer',
            'image_produk' => 'nullable|image',
        ]);

        if ($request->hasFile('image_produk')) {
            $data['image_produk'] = $request->file('image_produk')->store('produk_satuan', 'public');
        }

        $produk->update($data);
        return redirect()->route('admin.produk.show')->with('success', 'Produk berhasil diperbarui');
    }
    // Hapus Produk
    public function produkDelete($id)
    {
        $produk = produk_satuan::findOrFail($id);
        $produk->delete();
        return redirect()->route('admin.produk.show')->with('success', 'Produk berhasil dihapus');
    }
    public function updateStockProduk(Request $request, $id)
    {
        $produk = produk_satuan::findOrFail($id);
        $produk->stock_produk = $request->stock_produk;
        $produk->save();

        return redirect()->back()->with('success', 'Stok produk berhasil diperbarui.');
    }

    // CONTROL UNTUK ADMIN PAKET
    public function paketShow()
    {
        $paketList = menu_paket::all();
        return view('admin.paket', compact('paketList'));
    }

    public function paketCreate()
    {
        return view('admin.paket.create');
    }

    // Simpan Paket
    public function paketStore(Request $request)
    {
        $data = $request->validate([
            'kode_paket' => 'required|unique:tabel_paket,kode_paket',
            'nama_paket' => 'required',
            'detail_paket' => 'required',
            'kategori_paket' => 'required',
            'harga_paket' => 'required|numeric',
            'stock_paket' => 'required|integer',
            'image_paket' => 'nullable|image',
        ]);

        if ($request->hasFile('image_paket')) {
            $data['image_paket'] = $request->file('image_paket')->store('menu_paket', 'public');
        }

        $data['created_at'] = Carbon::now();
        $data['updated_at'] = Carbon::now();

        menu_paket::create($data);

        return redirect()->route('admin.paket.show')->with('success', 'Paket berhasil ditambahkan');
    }

    // Form Edit
    public function paketEdit($id)
    {
        $paket = menu_paket::findOrFail($id);
        return view('admin.paket.edit', compact('paket'));
    }

    // Update Paket
    public function paketUpdate(Request $request, $id)
    {
        $paket = menu_paket::findOrFail($id);
        $data = $request->validate([
            'nama_paket' => 'required',
            'detail_paket' => 'required',
            'kategori_paket' => 'required',
            'harga_paket' => 'required|numeric',
            'stock_paket' => 'required|integer',
            'image_paket' => 'nullable|image|',
        ]);

        if ($request->hasFile('image_paket')) {
            $data['image_paket'] = $request->file('image_paket')->store('menu_paket', 'public');
        }

        $paket->update($data);
        return redirect()->route('admin.paket.show')->with('success', 'Paket berhasil diperbarui');
    }

    // Hapus Paket
    public function paketDelete($id)
    {
        $paket = menu_paket::findOrFail($id);
        $paket->delete();
        return redirect()->route('admin.paket.show')->with('success', 'Paket berhasil dihapus');
    }

    public function updateStockPaket(Request $request, $id)
    {
        $paket = menu_paket::findOrFail($id);
        $paket->stock_paket = $request->stock_paket;
        $paket->save();

        return redirect()->back()->with('success', 'Stok paket berhasil diperbarui.');
    }

    // CONTROL UNTUK ADMIN PEMBUKUAN
    public function pembukuanShow(Request $request)
    {
        $query = tabel_order::with('data_pelanggan')->whereNotIn('status_order', ['batal', 'pending']);

        if ($request->filled('tanggal_awal') && $request->filled('tanggal_akhir')) {
            $tanggal_awal = $request->tanggal_awal;
            $tanggal_akhir = $request->tanggal_akhir;
            $query->whereBetween('created_at', [$tanggal_awal, $tanggal_akhir]);
        }

        // Urutkan berdasarkan tanggal terbaru
        $orders = $query->orderBy('created_at', 'desc')->get();

        $transferOrders = $orders->where('tipe_pembayaran', 'Transfer Bank');
        $cashOrders = $orders->where('tipe_pembayaran', 'Cash');

        $totalTransfer = $transferOrders->sum('total_belanjaan');
        $totalCash = $cashOrders->sum('total_belanjaan');
        $totalSemua = $totalTransfer + $totalCash;

        return view('admin.pembukuan', compact('transferOrders', 'cashOrders', 'totalTransfer', 'totalCash', 'totalSemua'));
    }
}
function kirimPesanWA($nomor, $pesan)
{
    $client = new \GuzzleHttp\Client();
    $response = $client->request('POST', 'https://api.fonnte.com/send', [
        'headers' => [
            'Authorization' => 'cFh96YKghJi8GQkN3LFN', // ganti dengan API key Fonnte kamu
        ],
        'form_params' => [
            'target' => $nomor,
            'message' => $pesan,
            'countryCode' => '62', // Opsional, default 62 untuk Indonesia
        ],
    ]);

    return json_decode($response->getBody(), true);
}
