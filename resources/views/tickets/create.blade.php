<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Jegyvásárlás') }}
        </h2>
    </x-slot>
    <style>
        .seat-box {
            background-color: white;
            color: black;
        }
        .seat-box.selected {
            background-color: black;
            color: white;
        }
    </style>
    <div class="py-8">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">

                    {{-- Esemény infó --}}
                    <h3 class="text-2xl font-bold mb-2">{{ $event->title }}</h3>
                    <p class="text-gray-600 mb-2">{{ $event->event_date_at?->format('Y. m. d. H:i') }}</p>
                    <p class="mb-4">Még megvásárolható jegyek száma: {{ $remainingTicketsCount }} / {{ $maxTicketsCount }}</p>


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

                    {{-- Jegyvásárló űrlap --}}
                    <form action="{{ route('tickets.store') }}" method="POST" class="space-y-6">
                        @csrf

                        <input type="hidden" name="event_id" value="{{ $event->id }}">

                        <div>
                            <label for="seat_id" class="block text-sm font-medium text-gray-700">
                                Válassz ülőhelyet
                            </label>
                            <div id="availableSeats-container">
                            @foreach ($availableSeats as $seat)
                                <div class="seat-box border rounded-lg text-center p-2 cursor-pointer bg-white text-black"
                                    data-seat-id="{{ $seat->id }}">
                                    <span>{{ $seat->seat_number }}</span><br>
                                    <span>{{ $seat->base_price }} Ft</span>
                                    <input type="checkbox" name="seat_ids[]" value="{{ $seat->id }}" class="hidden">
                                </div>
                            @endforeach
                            </div>
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

<script>
    document.addEventListener('DOMContentLoaded', () => {
    const maxTickets = {{ $remainingTicketsCount }}; // Laravel változó a szerveroldalról
    const seats = document.querySelectorAll('.seat-box');

    seats.forEach(box => {
        box.addEventListener('click', () => {
            const checkbox = box.querySelector('input[type="checkbox"]');
            const selectedCount = document.querySelectorAll('.seat-box input[type="checkbox"]:checked').length;

            // Ha már elérted a max jegyszámot, ne engedje újabb kijelölését
            if (!checkbox.checked && selectedCount >= maxTickets) {
                alert(`Csak ${maxTickets} jegyet választhatsz.`);
                return;
            }

            // Toggle a checkbox állapotát
            checkbox.checked = !checkbox.checked;

            // Toggle a vizuális állapotot a selected osztállyal
            box.classList.toggle('selected');
        });
    });
    });
</script>

</x-app-layout>
