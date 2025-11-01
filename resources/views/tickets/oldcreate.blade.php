<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Jegyvásárlás') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">

                {{-- Esemény info --}}
                <h3 class="text-2xl font-bold mb-2">{{ $event->title }}</h3>
                <p class="text-gray-600 mb-2">{{ $event->event_date_at?->format('Y. m. d. H:i') }}</p>
                <p class="mb-4">Még megvásárolható jegyek száma: {{ $event->max_number_allowed - $userTicketsCount }}</p>

                @if ($event->cover_image)
                    <img src="{{ asset('storage/' . $event->cover_image) }}"
                         alt="{{ $event->title }}"
                         class="w-full max-w-md rounded-lg mb-6 mx-auto">
                @endif

                {{-- Jegyvásárló űrlap --}}
                <form action="{{ route('tickets.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="event_id" value="{{ $event->id }}">

                    <div id="seats-container">
                        @foreach ($seats as $seat)
                            <div class="seat-box border rounded-lg text-center p-2 cursor-pointer bg-white text-black"
                                data-seat-id="{{ $seat->id }}">
                                <span>{{ $seat->seat_number }}</span><br>
                                <span>{{ $seat->base_price }} Ft</span>
                                <input type="checkbox" name="seat_ids[]" value="{{ $seat->id }}" class="hidden">
                            </div>
                        @endforeach
                    </div>

                    <button type="submit"
                            class="bg-black text-white px-4 py-2 rounded-lg hover:bg-gray-800 transition">
                        Jegyvásárlás
                    </button>
                </form>

            </div>
        </div>
    </div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const maxTickets = {{ $event->max_number_allowed - $userTicketsCount }};
        const seats = document.querySelectorAll('.seat-box');

        seats.forEach(box => {
            box.addEventListener('click', () => {
                const checkbox = box.querySelector('input[type="checkbox"]');
                const selectedCount = document.querySelectorAll('.seat-box input[type="checkbox"]:checked').length;

                if (!checkbox.checked && selectedCount >= maxTickets) {
                    alert(`Csak ${maxTickets} jegyet választhatsz.`);
                    return;
                }

                // Toggle a vizuális állapotot
                box.classList.toggle('bg-black');
                box.classList.toggle('text-white');
                box.classList.toggle('bg-white');
                box.classList.toggle('text-black');

                // Toggle a checkbox állapotát
                checkbox.checked = !checkbox.checked;
            });
        });
    });
</script>

</x-app-layout>
