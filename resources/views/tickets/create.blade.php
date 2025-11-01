<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Jegyvásárlás') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">

                    {{-- Esemény infó --}}
                    <h3 class="text-lg font-semibold mb-2">{{ $event->title }}</h3>
                    <p class="text-gray-600 mb-4">
                        {{ $event->event_date_at?->format('Y. m. d. H:i') }}
                    </p>


                    @if ($event->cover_image)
                        <img src="{{ asset('storage/' . $event->cover_image) }}"
                            alt="{{ $event->title }}"
                            class="w-[300px] h-[200px] object-cover rounded-2xl mb-6 mx-auto">
                    @else
                        <div class="w-[300px] h-[200px] bg-gray-200 flex items-center justify-center rounded-2xl text-gray-500 mb-6 mx-auto">
                            Nincs kép
                        </div>
                    @endif

                    {{-- Jegyvásárló űrlap --}}
                    <form action="{{ route('tickets.store') }}" method="POST" class="space-y-6">
                        @csrf

                        <input type="hidden" name="event_id" value="{{ $event->id }}">

                        <div>
                            <label for="seat_id" class="block text-sm font-medium text-gray-700">
                                Válassz ülőhelyet
                            </label>
                            <select id="seat_id" name="seat_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                @foreach ($availableSeats as $seat)
                                    <option value="{{ $seat->id }}">{{ $seat->seat_number }} - {{ $seat->base_price }} Ft</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="flex items-center gap-4">
                            <button type="submit"
                                    class="bg-blue-600 text-black px-4 py-2 rounded-lg hover:bg-blue-700 transition">
                                Jegyvásárlás
                            </button>

                            <a href="{{ route('events.show', ['event' => $event->id]) }}"
                                    class="bg-blue-600 text-black px-4 py-2 rounded-lg hover:bg-blue-700 transition">
                                Mégse
                            </a>

                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
