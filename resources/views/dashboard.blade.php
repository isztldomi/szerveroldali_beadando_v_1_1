<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Irányítópult') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="flex flex-wrap -mx-3 mb-6">

                <!-- Bal oszlop: stat kártyák -->
                <div class="w-full sm:w-1/2 px-3 mb-6 sm:mb-0 space-y-6">
                    <div class="bg-white shadow rounded-lg p-6 text-center">
                        <h3 class="text-lg font-medium text-gray-500">Összes esemény</h3>
                        <p class="mt-2 text-3xl font-bold text-gray-900">{{ $totalEvents }}</p>
                    </div>

                    <div class="bg-white shadow rounded-lg p-6 text-center">
                        <h3 class="text-lg font-medium text-gray-500">Összes eladott jegy</h3>
                        <p class="mt-2 text-3xl font-bold text-gray-900">{{ $totalTickets }}</p>
                    </div>

                    <div class="bg-white shadow rounded-lg p-6 text-center">
                        <h3 class="text-lg font-medium text-gray-500">Összes bevétel</h3>
                        <p class="mt-2 text-3xl font-bold text-gray-900">{{ number_format($totalIncome, 0, ',', ' ') }} Ft</p>
                    </div>

                    <div class="bg-white shadow rounded-lg p-6 text-center">
                        <h3 class="text-lg font-medium text-gray-500">Top 3 ülőhely</h3>
                        <ul class="mt-4 text-gray-700">
                            @foreach($topSeats as $seat)
                                <li class="mb-2">
                                    <span class="font-semibold">{{ $seat->seat->seat_number }}</span> -
                                    <span>Eladott jegyek: {{ $seat->sold_count }}</span>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>

                <!-- Jobb oszlop: hosszabb elem -->
                <div class="w-full sm:w-1/2 px-3 mb-6 sm:mb-0">
                    <div class="bg-white shadow rounded-lg p-6">
                        <h3 class="text-lg font-medium text-gray-500 mb-4">Legfrissebb események</h3>

                        <ul class="divide-y divide-gray-200">
                            @foreach($events as $event)
                                <li class="py-4 flex justify-between items-center">
                                    <div>
                                        <p class="font-semibold text-gray-900">{{ $event->name }}</p>
                                        <p class="text-gray-500 text-sm">
                                            Szabad jegyek: {{ $event->available_tickets }} |
                                            Bevétel: {{ number_format($event->revenue, 0, ',', ' ') }} Ft
                                        </p>
                                    </div>
                                    <div class="text-gray-400 text-sm">
                                        {{ $event->created_at->format('Y-m-d') }}
                                    </div>
                                </li>
                            @endforeach
                        </ul>

                        <div class="mt-4">
                            {{ $events->links() }}
                        </div>
                    </div>
                </div>

            </div>


        </div>
    </div>
</x-app-layout>
