<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Megvásárolt jegyeim') }}
        </h2>
    </x-slot>

    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">


        @foreach ($events as $event)
            <div class="mb-8">
                <h3 class="text-2xl font-bold mb-2">{{ $event->title }}</h3>
                <p class="text-gray-600 mb-4">{{ $event->event_date_at?->format('Y. m. d. H:i') }}</p>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach ($event->userTickets as $ticket)

                        <div class="border rounded-xl p-4 shadow-sm bg-white">
                            <p class="text-sm text-gray-500 mb-1">Vonalkód:</p>
                            <div class="font-barcode text-3xl tracking-widest mb-2">|| ||| |||| ||| | ||</div>
                            <p class="text-gray-700"># <strong>{{ $ticket->barcode }}</strong></p>
                        </div>

                    @endforeach
                </div>
            </div>
        @endforeach





        {{-- Eseménycsoport
        <div class="mb-8">
            <h3 class="text-2xl font-bold mb-2">🎵 Coldplay koncert</h3>
            <p class="text-gray-600 mb-4">2025. június 14. 20:00</p>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="border rounded-xl p-4 shadow-sm bg-white">
                    <p class="text-sm text-gray-500 mb-1">Vonalkód:</p>
                    <div class="font-barcode text-3xl tracking-widest mb-2">|| ||| |||| ||| | ||</div>
                    <p class="text-gray-700"># <strong>123456789</strong></p>
                </div>

                <div class="border rounded-xl p-4 shadow-sm bg-white">
                    <p class="text-sm text-gray-500 mb-1">Vonalkód:</p>
                    <div class="font-barcode text-3xl tracking-widest mb-2">||| ||| | || ||| ||</div>
                    <p class="text-gray-700"># <strong>987654321</strong></p>
                </div>
            </div>
        </div>

        {{-- Következő esemény
        <div class="mb-8">
            <h3 class="text-2xl font-bold mb-2">🎭 Hamlet előadás</h3>
            <p class="text-gray-600 mb-4">2025. szeptember 5. 19:00</p>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="border rounded-xl p-4 shadow-sm bg-white">
                    <p class="text-sm text-gray-500 mb-1">Vonalkód:</p>
                    <div class="font-barcode text-3xl tracking-widest mb-2">| |||| ||| | || ||</div>
                    <p class="text-gray-700"># <strong>555333777</strong></p>
                </div>
            </div>
        </div>
        --}}
    </div>

    {{-- Ideiglenes barcode font hozzáadása --}}
    <style>
        @font-face {
            font-family: 'LibreBarcode';
            src: url('https://fonts.googleapis.com/css2?family=Libre+Barcode+39+Extended+Text&display=swap');
        }
        .font-barcode {
            font-family: 'Libre Barcode 39 Extended Text', cursive;
            letter-spacing: 0.1em;
        }
    </style>
</x-app-layout>
