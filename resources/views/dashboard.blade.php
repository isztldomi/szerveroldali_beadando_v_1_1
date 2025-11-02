<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Irányítópult') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <!-- Stat kártyák flexben -->
            <div class="flex flex-wrap -mx-3 mb-6">
                <!-- Összes esemény -->
                <div class="w-full sm:w-1/3 px-3 mb-6 sm:mb-0">
                    <div class="bg-white shadow rounded-lg p-6 text-center">
                        <h3 class="text-lg font-medium text-gray-500">Összes esemény</h3>
                        <p class="mt-2 text-3xl font-bold text-gray-900">{{ $totalEvents }}</p>
                    </div>
                </div>

                <!-- Összes eladott jegy -->
                <div class="w-full sm:w-1/3 px-3 mb-6 sm:mb-0">
                    <div class="bg-white shadow rounded-lg p-6 text-center">
                        <h3 class="text-lg font-medium text-gray-500">Összes eladott jegy</h3>
                        <p class="mt-2 text-3xl font-bold text-gray-900">{{ $totalTickets }}</p>
                    </div>
                </div>

                <!-- Összes bevétel -->
                <div class="w-full sm:w-1/3 px-3 mb-6 sm:mb-0">
                    <div class="bg-white shadow rounded-lg p-6 text-center">
                        <h3 class="text-lg font-medium text-gray-500">Összes bevétel</h3>
                        <p class="mt-2 text-3xl font-bold text-gray-900">{{ number_format($totalIncome, 0, ',', ' ') }} Ft</p>
                    </div>
                </div>
            </div>



        </div>
    </div>
</x-app-layout>
