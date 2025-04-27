<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Produk;
use App\Models\Transaksi;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        
        $jumlahTransaksi = Transaksi::count();
        $totalPendapatan = Transaksi::sum('total_harga');
        $totalStok = Produk::sum('kuantitas');

        // Tambahan untuk grafik area chart
        $salesData = Transaksi::selectRaw('DATE(created_at) as date, SUM(total_harga) as total')
                    ->whereBetween('created_at', [now()->subDays(6), now()])
                    ->groupBy('date')
                    ->orderBy('date')
                    ->get();

        $dates = $salesData->pluck('date')->map(function($date) {
            return \Carbon\Carbon::parse($date)->format('d M');
        });
        $totals = $salesData->pluck('total');

        return view('dashboard', compact('jumlahTransaksi', 'totalPendapatan', 'totalStok', 'dates', 'totals'));
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
