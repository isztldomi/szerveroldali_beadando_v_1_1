<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Megvásárolt jegyeim') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @foreach ($events as $event)

                    <h3 class="text-2xl font-bold mb-2">{{ $event->title }}</h3>
                    <p class="text-gray-600 mb-4">{{ $event->event_date_at?->format('Y. m. d. H:i') }}</p>

                    <div class="flex flex-wrap -mx-3 mb-6">
                        @foreach ($event->userTickets as $ticket)
                        <div class="w-full sm:w-1/5 px-3 mb-6 sm:mb-0 space-y-6">

                            <div class="bg-white shadow rounded-lg p-6 text-center">
                                <p class="text-sm text-gray-500 mb-1">Vonalkód:</p>
                                <div class="font-barcode text-3xl tracking-widest mb-2">
                                    <img src="data:image/png;base64,{{ $ticket->barcodeImage }}" alt="Barcode" class="mx-auto mb-2" />
                                </div>
                                <p class="text-gray-700"># <strong>{{ $ticket->barcode }}</strong></p>
                                <p class="text-sm text-gray-500 mb-1">Ülőhely:</p>
                                <p class="text-lg font-semibold mb-3">
                                    {{ $ticket->seat?->seat_number ?? 'N/A' }}
                                </p>
                            </div>
                        </div>
                        @endforeach
                    </div>
            @endforeach

        </div>
    </div>

</x-app-layout>
