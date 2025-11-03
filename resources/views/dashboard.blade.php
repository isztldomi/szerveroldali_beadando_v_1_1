<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Irányítópult') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="flex flex-wrap -mx-3 mb-6">
                <div class="w-full sm:w-1/3 px-3 mb-6 sm:mb-0 space-y-6">
                    <a href="{{ route('events.create') }}">
                        <div class="bg-white shadow rounded-lg p-6">
                            <span class="inline-block bg-blue-600 hover:bg-blue-700 text-black font-semibold py-2 px-4 rounded">
                                Új esemény létrehozása
                            </span>
                        </div>
                    </a>
                </div>

                <div class="w-full sm:w-1/3 px-3 mb-6 sm:mb-0 space-y-6">
                    <a href="">
                        <div class="bg-white shadow rounded-lg p-6">
                            <span class="inline-block bg-blue-600 hover:bg-blue-700 text-black font-semibold py-2 px-4 rounded">
                                Ülőhelyek
                            </span>
                        </div>
                    </a>
                </div>

                <div class="w-full sm:w-1/3 px-3 mb-6 sm:mb-0 space-y-6">
                    <a href="">
                        <div class="bg-white shadow rounded-lg p-6">
                            <span class="inline-block bg-blue-600 hover:bg-blue-700 text-black font-semibold py-2 px-4 rounded">
                                Jegykezelés
                            </span>
                        </div>
                    </a>
                </div>
            </div>

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
                        <h3 class="text-lg font-medium text-gray-500 mb-4">Események</h3>

                        <ul class="divide-y divide-gray-200">
                            @foreach($events as $event)
                                <li>
                                    <div class="flex flex-wrap -mx-3 mb-6">

                                        <div class="w-full sm:w-1/2 px-3 mb-6 sm:mb-0">
                                            <p class="font-semibold text-gray-900">{{ $event->title }}</p>
                                            <p class="text-gray-500 text-sm">
                                                Szabad jegyek: {{ $event->available_tickets }} / {{ $totalSeats }} |
                                                Bevétel: {{ number_format($event->revenue, 0, ',', ' ') }} Ft
                                            </p>
                                        </div>

                                        <div class="w-full sm:w-1/2 px-3 mb-6 sm:mb-0">
                                            <a href="{{ route('events.edit', $event->id) }}"
                                            class="inline-flex items-center px-3 py-1.5 bg-yellow-400 hover:bg-yellow-500 text-black text-sm font-medium rounded-lg shadow">
                                                Módosítás
                                            </a>

                                            <form action="" method="POST" {{-- {{ route('events.destroy', $event->id) }} --}}
                                                onsubmit="return confirm('Biztosan törölni szeretnéd az eseményt?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="inline-flex items-center px-3 py-1.5 bg-red-500 hover:bg-red-600 text-black text-sm font-medium rounded-lg shadow">
                                                    Törlés
                                                </button>
                                            </form>
                                        </div>

                                    </div>
                                </li>
                            @endforeach
                        </ul>

                        <!-- Lapozó linkek -->
                        <div class="mt-4">
                            {{ $events->links() }}
                        </div>
                    </div>
                </div>

            </div>



        </div>
    </div>
</x-app-layout>
