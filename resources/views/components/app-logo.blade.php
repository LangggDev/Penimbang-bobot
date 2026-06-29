{{--
    Komponen logo aplikasi (sidebar & header).
    File logo ada di: public/images/logo.png
    Untuk mengganti logo, cukup ganti file tersebut.
--}}
@props([
    'sidebar' => false,
])

@if($sidebar)
    <flux:sidebar.brand name="Gusti Putra" {{ $attributes }}>
        <x-slot name="logo" class="flex aspect-square size-8 items-center justify-center">
            <img src="{{ asset('images/logo.png') }}" alt="Logo Gusti Putra" class="size-8 object-contain" />
        </x-slot>
    </flux:sidebar.brand>
@else
    <flux:brand name="Gusti Putra" {{ $attributes }}>
        <x-slot name="logo" class="flex aspect-square size-8 items-center justify-center">
            <img src="{{ asset('images/logo.png') }}" alt="Logo Gusti Putra" class="size-8 object-contain" />
        </x-slot>
    </flux:brand>
@endif
