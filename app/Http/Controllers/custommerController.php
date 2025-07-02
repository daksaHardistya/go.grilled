<?php

namespace App\Http\Controllers;

use App\Models\data_pelanggan;
use App\Models\menu_paket;
use App\Models\produk_satuan;
use App\Models\tabel_order;
use App\Models\tabel_orderPaket;
use App\Models\tabel_orderProduk;
use Illuminate\Support\Facades\Http;
// use Illuminate\Container\Attributes\Log;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class custommerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function showProduk()
    {
        $produkSatuan = produk_satuan::all();
        return view('produk', compact('produkSatuan'));
    }

    public function showForm()
    {
        return view('form');
    }

    public function showPakets()
    {
        $paketmenubasic = menu_paket::where('kategori_paket', 'basic')->where('stock_paket', '>', 0)->get();

        $paketmenuspecial = menu_paket::where('kategori_paket', 'special')->where('stock_paket', '>', 0)->get();

        $paketmenufamily = menu_paket::where('kategori_paket', 'family')->where('stock_paket', '>', 0)->get();

        return view('paket', compact('paketmenubasic', 'paketmenuspecial', 'paketmenufamily'));
    }

    public function showMetodePembayaran()
    {
        return view('metodePembayaran');
    }
    public function showPembayaranTransfer()
    {
        return view('pembayaranTransfer');
    }
    public function uploadBuktiTf(Request $request)
    {
        $request->validate([
            'bukti_pembayaran' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:5120', // Max 2MB (5120KB)
        ]);
        if ($request->hasFile('bukti_pembayaran')) {
            $file = $request->file('bukti_pembayaran');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('bukti_transfer', $fileName, 'public');

            return response()->json([
                'success' => true,
                'fileName' => $fileName,
            ]);
        }

        return response()->json(['success' => false], 400);
    }

    public function showInvoice()
    {
        return view('invoice');
    }
    public function simpanOrderan(Request $request)
    {
        try {
            Log::info('Data request simpanTransaksi:', $request->all());
            $data = $request->json()->all();
            DB::beginTransaction();

            // =======================
            // SIMPAN DATA PELANGGAN
            // =======================
            Log::info('Mulai menyimpan data pelanggan...');
            $pelanggan = data_pelanggan::create([
                'nomor_tlp' => $data['pelanggan']['nomor_tlp'] ?? null,
                'nama_pel' => $data['pelanggan']['nama_pel'] ?? null,
                'alamat_pel' => $data['pelanggan']['alamat_pel'] ?? null,
                'email_pel' => $data['pelanggan']['email_pel'] ?? null,
            ]);
            Log::info('Data pelanggan berhasil disimpan.', ['id_pel' => $pelanggan->id_pel]);

            // =======================
            // SIMPAN DATA ORDER
            // =======================
            Log::info('Mulai menyimpan data order...');
            $order = tabel_order::create([
                'id_pel' => $pelanggan->id_pel,
                'nomor_transaksi' => $data['order']['nomor_transaksi'],
                'tipe_pembayaran' => $data['order']['tipe_pembayaran'],
                'total_belanjaan' => $data['order']['total_belanjaan'],
                'status_order' => 'pending',
                'bukti_pembayaran' => $data['order']['bukti_pembayaran'] ?? 'Cash',
            ]);
            Log::info('Data order berhasil disimpan.', ['id_order' => $order->id_order]);

            // =======================
            // SIMPAN ORDER PAKET
            // =======================
            Log::info('Memproses orderPaket...');
            foreach ($data['orderPaket'] as $orderPaket) {
                $paket = menu_paket::find($orderPaket['id_paket']);
                if (!$paket) {
                    throw new \Exception("Paket dengan ID {$orderPaket['id_paket']} tidak ditemukan.");
                }

                if ($paket->stock_paket < $orderPaket['jumlah_orderPaket']) {
                    throw new \Exception("Stok paket '{$paket->nama_paket}' tidak mencukupi!");
                }

                tabel_orderPaket::create([
                    'id_order' => $order->id_order,
                    'id_paket' => $paket->id_paket,
                    'jumlah_orderPaket' => $orderPaket['jumlah_orderPaket'],
                ]);

                $paket->stock_paket -= $orderPaket['jumlah_orderPaket'];
                $paket->save();

                Log::info('Berhasil simpan orderPaket dan update stok', [
                    'id_paket' => $paket->id_paket,
                    'sisa_stok' => $paket->stock_paket,
                ]);
            }

            // =======================
            // SIMPAN ORDER PRODUK
            // =======================
            Log::info('Memproses orderProduk...');
            foreach ($data['orderProduk'] as $orderProduk) {
                $produk = produk_satuan::find($orderProduk['id_produk']);
                if (!$produk) {
                    throw new \Exception("Produk dengan ID {$orderProduk['id_produk']} tidak ditemukan.");
                }

                if ($produk->stock_produk < $orderProduk['jumlah_orderProduk']) {
                    throw new \Exception("Stok produk '{$produk->nama_produk}' tidak mencukupi!");
                }

                tabel_orderProduk::create([
                    'id_order' => $order->id_order,
                    'id_produk' => $produk->id_produk,
                    'jumlah_orderProduk' => $orderProduk['jumlah_orderProduk'],
                ]);

                $produk->stock_produk -= $orderProduk['jumlah_orderProduk'];
                $produk->save();

                Log::info('Berhasil simpan orderProduk dan update stok', [
                    'id_produk' => $produk->id_produk,
                    'sisa_stok' => $produk->stock_produk,
                ]);
            }

            DB::commit();

            Log::info('Transaksi berhasil disimpan.', [
                'id_order' => $order->id_order,
                'total_belanjaan' => $order->total_belanjaan,
            ]);

            return response()->json(['message' => 'Transaksi berhasil disimpan!'], 200);
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Terjadi kesalahan saat menyimpan transaksi.', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json(
                [
                    'error' => 'Gagal menyimpan transaksi.',
                    'pesan' => $e->getMessage(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                ],
                500,
            );
        }
    }

    public function showCart()
    {
        return view('cart');
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
