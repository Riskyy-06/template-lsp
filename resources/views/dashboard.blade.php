<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Dashboard') }}
                </h2>
                <p class="text-gray-600 dark:text-gray-400">Hai, {{ Auth::user()->name }}!</p>
            </div>
            <div class="text-sm text-gray-600 dark:text-gray-400">
                {{ \Carbon\Carbon::now()->format('d M Y, H:i') }}
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow text-center">
                <div class="text-lg text-gray-600 dark:text-gray-300">Jumlah Transaksi</div>
                <div class="text-3xl font-bold text-blue-600 mt-2">{{ $jumlahTransaksi }}</div>
            </div>

            <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow text-center">
                <div class="text-lg text-gray-600 dark:text-gray-300">Total Pendapatan</div>
                <div class="text-3xl font-bold text-green-500 mt-2">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</div>
            </div>

            <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow text-center">
                <div class="text-lg text-gray-600 dark:text-gray-300">Total Stok Produk</div>
                <div class="text-3xl font-bold text-yellow-500 mt-2">{{ $totalStok }}</div>
            </div>
        </div>

        <!-- Area Chart Section -->
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 mt-10">
            <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow">
                <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-300 mb-4">Grafik Penjualan 7 Hari Terakhir</h3>
                <div id="salesChart" class="h-96"></div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
    var options = {
        chart: {
            type: 'area',
            height: 350
        },
        series: [{
            name: 'Penjualan',
            data: @json($totals->map(function($total) {
                return round($total / 5000) * 5000; // Bulatkan ke kelipatan 5000
            }))
        }],
        xaxis: {
            categories: @json($dates)
        },
        colors: ['#34D399'],
        fill: {
            type: 'gradient',
            gradient: {
                shadeIntensity: 1,
                opacityFrom: 0.7,
                opacityTo: 0.2,
                stops: [0, 90, 100]
            }
        },
        dataLabels: {
            enabled: false
        },
        stroke: {
            curve: 'smooth'
        },
        grid: {
            borderColor: '#e0e0e0',
            strokeDashArray: 5
        },
        tooltip: {
            x: {
                format: 'dd MMM'
            },
            y: {
                formatter: function (value) {
                    return 'Rp ' + value.toLocaleString('id-ID');
                }
            }
        },
        yaxis: {
            labels: {
                formatter: function (value) {
                    return 'Rp ' + value.toLocaleString('id-ID');
                }
            }
        }
    };

    var chart = new ApexCharts(document.querySelector("#salesChart"), options);
    chart.render();
</script>
</x-app-layout>
