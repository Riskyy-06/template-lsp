<?php

namespace App\Http\Controllers;

use App\Models\ItemKeranjang;
use App\Models\ItemTransaksi;
use App\Models\Produk;
use App\Models\Transaksi;
use Illuminate\Http\Request;

class KeranjangController extends Controller
{
    public function index()
    {
        $items = ItemKeranjang::with('produk')->get()->map(function ($item) {
            $item->total_harga = $item->produk->harga * $item->jumlah;
            return $item;
        });

        $total_belanja = $items->sum('total_harga');

        return view('keranjang.index', [
            'items' => $items,
            'total_belanja' => $total_belanja,
        ]);
    }

    public function checkout()
    {
        $items = ItemKeranjang::with('produk')->get()->map(function ($item) {
            $item->total_harga = $item->produk->harga * $item->jumlah;
            return $item;
        });

        $transaksi = Transaksi::create([
            'total_harga' => $items->sum('total_harga'),
        ]);

        foreach ($items as $item) {
            ItemTransaksi::create([
                'transaksi_id' => $transaksi->id,
                'produk_id' => $item->produk_id,
                'jumlah' => $item->jumlah,
                'total_harga' => $item->total_harga,
            ]);

            $item->produk()->update([
                'kuantitas' => $item->produk->kuantitas - $item->jumlah,
            ]);
        }

        ItemKeranjang::truncate();

        return redirect()->route('transaksi.show', $transaksi)->with('success', 'Transaksi berhasil dilakukan.');
    }

    public function create()
    {
        $produks = Produk::all(['id', 'nama_produk', 'kuantitas'])->map(function ($produk) {
            $totalInKeranjang = ItemKeranjang::where('produk_id', $produk->id)->sum('jumlah');

            $produk->tersedia = $produk->kuantitas - $totalInKeranjang;

            return $produk;
        });

        return view('keranjang.create', compact('produks'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'produk_id' => ['required', 'exists:produks,id'],
            'jumlah' => ['required', 'integer', 'min:1', function ($attribute, $value, $fail) use ($request) {
                $produk = Produk::find($request->produk_id);
                $itemKeranjang = ItemKeranjang::where('produk_id', $request->produk_id)->first();
                $currentJumlah = $itemKeranjang ? $itemKeranjang->jumlah : 0;

                if ($value + $currentJumlah > $produk->kuantitas) {
                    return $fail('Jumlah produk melebihi stok yang tersedia.');
                }
            }],
        ]);

        $item = ItemKeranjang::where('produk_id', $validated['produk_id'])->first();
        if ($item) {
            $item->jumlah += $validated['jumlah'];
            $item->save();
            return redirect()->route('keranjang.index')->with('success', 'Produk berhasil ditambahkan.');
        }

        ItemKeranjang::create($validated);
        return redirect()->route('keranjang.index')->with('success', 'Produk berhasil dimasukkan.');
    }

    public function destroy(ItemKeranjang $item)
    {
        $item->delete();
        return redirect()->route('keranjang.index')->with('success', 'Item keranjang berhasil dihapus.');
    }
}
