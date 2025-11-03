<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Esemény') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto py-10 px-4 sm:px-6 lg:px-8">

            <div class="bg-white shadow-lg rounded-2xl p-6 text-center">

                @if ($event->cover_image)
                    <img src="{{ asset('storage/' . $event->cover_image) }}"
                        alt="{{ $event->title }}"
                        class="w-[300px] h-[200px] object-cover rounded-2xl mb-6 mx-auto">
                @else
                    <div class="w-[300px] h-[200px] bg-gray-200 flex items-center justify-center rounded-2xl text-gray-500 mb-6 mx-auto">
                        Nincs kép
                    </div>
                @endif

                <h1 class="text-3xl font-bold mb-2">{{ $event->title }}</h1>
                <p class="text-gray-600 mb-6">
                    Időpont: {{ $event->event_date_at->format('Y. m. d. H:i') }}
                </p>

                <p class="text-gray-800 mb-6 leading-relaxed">
                    {{ $event->description }}
                </p>

                <div class="mb-6">
                    <p class="text-gray-600 mb-4">
                        Szabad jegyek: <strong>{{ $remaining }} / {{ $total }}</strong>
                    </p>

                    @if (now() < $event->sale_start_at)
                        <span class="inline-block text-gray-500">
                            A jegyértékesítés kezdete: {{ $event->sale_start_at->format('Y. m. d. H:i') }}
                        </span>
                    @elseif (now() > $event->sale_end_at)
                        <span class="inline-block text-gray-500">
                            A jegyértékesítés vége: {{ $event->sale_end_at->format('Y. m. d. H:i') }}
                        </span>
                    @elseif ($remaining > 0)
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

                <a href="{{ route('events.index') }}"
                class="inline-block text-blue-600 hover:text-blue-800 underline">
                    ← Vissza a főoldalra
                </a>
            </div>

        </div>
    </div>
</x-app-layout>
