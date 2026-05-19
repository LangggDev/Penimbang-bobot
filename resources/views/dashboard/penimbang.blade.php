<x-layouts::app :title="'Dashboard Penimbang'">
    <div class="p-6">
        <h1 class="text-2xl font-bold">Dashboard Penimbang</h1>
        <p>Selamat datang, {{ auth()->user()->name }}</p>
    </div>
</x-layouts::app>