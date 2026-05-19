<x-layouts::app :title="'Dashboard Kasir'">
    <div class="p-6">
        <h1 class="text-2xl font-bold">Dashboard Kasir</h1>
        <p>Selamat datang, {{ auth()->user()->name }}</p>
    </div>
</x-layouts::app>