<x-guest-layout>
    <div class="max-w-3xl mx-auto py-10 text-center">

        {{-- Borítókép --}}
        @if ($event->cover_image)
            <img src="{{ asset('storage/' . $event->cover_image) }}"
                 alt="{{ $event->title }}"
                 class="w-[300px] h-[200px] object-cover rounded-2xl mb-6 mx-auto">
        @else
            <div class="w-[300px] h-[200px] bg-gray-200 flex items-center justify-center rounded-2xl text-gray-500 mb-6 mx-auto">
                Nincs kép
            </div>
        @endif

        {{-- Esemény címe és dátuma --}}
        <h1 class="text-3xl font-bold mb-2">{{ $event->title }}</h1>
        <p class="text-gray-600 mb-4">
            Időpont: {{ $event->event_date_at?->format('Y. m. d. H:i') }}
        </p>

        {{-- Leírás
        <p class="text-gray-800 mb-6 leading-relaxed">
            {{ $event->description }}
        </p>
        --}}

        {{-- Jegyek információ és gomb --}}
        @php
            $total = \App\Models\Seat::count();
            $remaining = $event->remainingSeatsCount();
        @endphp

        <div class="mb-6">
            <p class="text-gray-600 mb-2">
                Szabad jegyek: <strong>{{ $remaining }} / {{ $total }}</strong>
            </p>

            @if($remaining > 0)
                <a href="{{ route('tickets.create', ['event' => $event->id]) }}"
                        class="inline-block text-blue-600 hover:text-blue-800 underline">
                    Jegyvásárlás
                </a>
            @else
                <button disabled
                        class="inline-block text-blue-600 hover:text-blue-800">
                    Elfogyott
                </button>
            @endif
        </div>

        {{-- Visszalépés --}}
        <a href="{{ route('events.index') }}"
           class="inline-block text-blue-600 hover:text-blue-800 underline">
            ← Vissza a főoldalra
        </a>
    </div>
</x-guest-layout>
