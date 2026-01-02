<x-mail::layout>
{{-- Header --}}
<x-slot:header>
<x-mail::header :url="config('app.url')">
{{ config('app.name') }}
</x-mail::header>
</x-slot:header>

{{-- Body --}}
{!! $slot !!}

{{-- Subcopy --}}
@isset($subcopy)
<x-slot:subcopy>
<x-mail::subcopy>
@php
    $translatedSubcopy = str_replace(
        "If you're having trouble clicking the",
        "Si tienes problemas para hacer clic en el botón",
        $subcopy
    );
    $translatedSubcopy = str_replace(
        "button, copy and paste the URL below into your web browser:",
        ", copia y pega la URL de abajo en tu navegador web:",
        $translatedSubcopy
    );
@endphp
{!! $translatedSubcopy !!}
</x-mail::subcopy>
</x-slot:subcopy>
@endisset

{{-- Footer --}}
<x-slot:footer>
<x-mail::footer>
© {{ date('Y') }} {{ config('app.name') }}. Todos los derechos reservados.
</x-mail::footer>
</x-slot:footer>
</x-mail::layout>
