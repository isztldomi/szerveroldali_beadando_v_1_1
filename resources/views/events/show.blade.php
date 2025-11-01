<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Esemény') }}
        </h2>
    </x-slot>

    {{-- Legkülső div, szekció szerűen --}}
    <div class="max-w-7xl mx-auto py-10 px-4 sm:px-6 lg:px-8">

        {{-- Konténer a fehér háttérrel, lekerekített sarkokkal és árnyékkal --}}
        <div class="bg-white shadow-lg rounded-2xl p-6 text-center">

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
            <p class="text-gray-600 mb-6">
                Időpont: {{ $event->event_date_at?->format('Y. m. d. H:i') }}
            </p>

            {{-- Leírás --}}
            <p class="text-gray-800 mb-6 leading-relaxed">
                {{ $event->description }}
            </p>

            {{-- Jegyek információ és gomb --}}
            @php
                $total = \App\Models\Seat::count();
                $remaining = $event->remainingSeatsCount();
            @endphp

            <div class="mb-6">
                <p class="text-gray-600 mb-4">
                    Szabad jegyek: <strong>{{ $remaining }} / {{ $total }}</strong>
                </p>

                @if($remaining > 0)
                    @auth
                        <a href="{{ route('tickets.create', ['event' => $event->id]) }}"
                           class="inline-block bg-blue-600 text-black px-6 py-2 rounded-lg shadow hover:bg-blue-700 transition">
                            Jegyvásárlás
                        </a>
                    @else
                        <span class="inline-block text-gray-500">
                            Bejelentkezés szükséges a vásárláshoz
                        </span>
                    @endauth
                @else
                    <button disabled
                            class="inline-block bg-gray-300 text-gray-600 px-6 py-2 rounded-lg cursor-not-allowed">
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

    </div>
</x-app-layout>
