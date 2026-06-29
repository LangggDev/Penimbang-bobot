{{--
    Komponen logo ikon aplikasi.
    File logo ada di: public/images/logo.png
    Untuk mengganti logo, cukup ganti file tersebut.
--}}
<img
    src="{{ asset('images/logo.png') }}"
    alt="Logo Gusti Putra"
    {{ $attributes->merge(['class' => '']) }}
/>
