<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Események') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">


            @if (session('success'))
                <div class="bg-white shadow rounded-lg p-6">
                    <span style="color: #15803d; font-weight: 600;">
                        <strong>{{ session('success') }}</strong>
                    </span>
                </div>
            @endif

            @if ($errors->any())
                <div class="bg-white shadow rounded-lg p-6">
                    <strong>Hiba történt:</strong>
                    <ul class="mt-2 list-disc list-inside text-sm">
                        @foreach ($errors->all() as $error)
                            <li>
                                <span style="color: #b91c1c; font-weight: 600;">{{ $error }}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @forelse ($events as $event)
                @php
                    // Számoljuk a szabad helyeket
                    $total = \App\Models\Seat::count();
                    $remaining = $event->remainingSeatsCount();
                    $percent = $total > 0 ? round(($remaining / $total) * 100) : 0;
                @endphp

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 flex flex-col md:flex-row gap-6">

                        {{-- Borítókép --}}
                        @if ($event->cover_image)
                            <img src="{{ asset('storage/' . $event->cover_image) }}"
                                alt="{{ $event->title }}"
                                class="w-[300px] h-[200px] object-cover rounded-lg mx-auto">
                        @else
                            <div class="w-[300px] h-[200px] bg-gray-200 flex items-center justify-center rounded-lg text-gray-500 mx-auto">
                                Nincs kép
                            </div>
                        @endif

                        {{-- Esemény adatai --}}
                        <div class="flex-1">
                            <h2 class="text-2xl font-semibold mb-2">{{ $event->title }}</h2>
                            <p class="text-gray-600 mb-2">
                                {{ $event->event_date_at?->format('Y. m. d. H:i') }}
                            </p>

                            {{--
                            <p class="text-gray-700 mb-4 line-clamp-3">
                                {{ Str::limit($event->description, 200) }}
                            </p>
                            --}}

                            {{-- Szabad helyek kijelzése --}}
                            <div class="bg-gray-200 rounded-full h-4 mb-2">
                                <div class="bg-green-500 h-4 rounded-full transition-all duration-300"
                                     style="width: {{ $percent }}%;"></div>
                            </div>
                            <p class="text-sm text-gray-500 mb-4">
                                Szabad helyek: {{ $remaining }} / {{ $total }} ({{ $percent }}%)
                            </p>

                            {{-- Részletek gomb --}}
                            <a href="{{ route('events.show', $event) }}"
                            class="inline-block bg-blue-200 text-black font-medium px-4 py-2 rounded-lg shadow hover:bg-blue-300 transition">
                                Részletek
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <p class="text-gray-500 text-center mt-8">Nincs elérhető jövőbeli esemény.</p>
            @endforelse

            {{-- Lapozás --}}
            <div class="mt-6">
                {{ $events->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
