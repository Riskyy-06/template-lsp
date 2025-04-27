<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Keranjang Belanja') }}
        </h2>
    </x-slot>

    <div class="py-12 max-w-4xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Daftar Produk di Keranjang</h3>
                <a href="{{ route('keranjang.create') }}" 
                   class="px-4 py-2 bg-blue-500 hover:bg-blue-600 text-white font-semibold rounded">
                    + Tambah Produk
                </a>
            </div>

            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead>
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Nama Produk</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Harga Satuan</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Jumlah</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Total Harga</th>
                        <th class="px-6 py-3"></th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse ($items as $item)
                        <tr>
                            <td class="px-6 py-4 text-sm text-gray-700 dark:text-gray-300">{{ $item->produk->nama_produk }}</td>
                            <td class="px-6 py-4 text-sm text-gray-700 dark:text-gray-300">Rp {{ number_format($item->produk->harga, 0, ',', '.') }}</td>
                            <td class="px-6 py-4 text-sm text-gray-700 dark:text-gray-300">{{ $item->jumlah }}</td>
                            <td class="px-6 py-4 text-sm text-gray-700 dark:text-gray-300">Rp {{ number_format($item->total_harga, 0, ',', '.') }}</td>
                            <td class="px-6 py-4 text-right">
                                <form action="{{ route('keranjang.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus produk ini dari keranjang?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="px-3 py-1 bg-red-500 hover:bg-red-600 text-white text-sm rounded">
                                        Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500 dark:text-gray-300">Keranjang kosong.</td>
                        </tr>
                    @endforelse
                    <tr>
                        <td colspan="3" class="px-6 py-4 font-bold text-gray-700 dark:text-gray-300">Total Belanja</td>
                        <td class="px-6 py-4 font-bold text-gray-700 dark:text-gray-300">Rp {{ number_format($total_belanja, 0, ',', '.') }}</td>
                        <td></td>
                    </tr>
                </tbody>
            </table>

            @if ($items->count() > 0)
            <form action="{{ route('keranjang.checkout') }}" method="POST" class="mt-6">
                @csrf
                <input type="hidden" name="total_belanja" value="{{ $total_belanja }}">

                <button type="submit"
                    class="w-full px-4 py-2 bg-green-500 hover:bg-green-600 text-white font-semibold rounded">
                    Checkout Sekarang
                </button>
            </form>
            @endif

        </div>
    </div>

    {{-- Script JavaScript --}}
    <script>
        function openDeleteModal(itemId) {
            const modal = document.getElementById('deleteModal');
            const form = document.getElementById('deleteForm');
            form.action = `/keranjang/${itemId}`;
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function closeDeleteModal() {
            const modal = document.getElementById('deleteModal');
            modal.classList.remove('flex');
            modal.classList.add('hidden');
        }
    </script>
</x-app-layout>

{{-- Script JavaScript --}}
    <script>
        function openDeleteModal(itemId) {
            const modal = document.getElementById('deleteModal');
            const form = document.getElementById('deleteForm');
            form.action = `/keranjang/${itemId}`;
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function closeDeleteModal() {
            const modal = document.getElementById('deleteModal');
            modal.classList.remove('flex');
            modal.classList.add('hidden');
        }
    </script>