{{--
    Layout AUTH utama — digunakan oleh halaman login, register, dll.
    Mengarah ke card.blade.php untuk tampilan yang lebih premium.
--}}
<x-layouts::auth.card :title="$title ?? null">
    {{ $slot }}
</x-layouts::auth.card>
