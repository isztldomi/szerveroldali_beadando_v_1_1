<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Ülőhelyek') }}
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

            <div class="flex flex-wrap -mx-3">
                <div class="w-full sm:w-1/2 px-3 mb-6 sm:mb-0 space-y-2">
                    <h3 class="text-lg font-medium text-gray-700 mb-2">Székek</h3>
                    <ul class="bg-white shadow rounded-lg divide-y divide-gray-200">
                        @foreach ($seats as $seat)
                            <li class="px-4 py-2 hover:bg-gray-100 cursor-pointer flex justify-between"
                                onclick="selectSeat({{ $seat->id }}, '{{ $seat->seat_number }}', {{ $seat->base_price }})">
                                <span>{{ $seat->seat_number }}</span>


                                <span>
                                    @if ($seat->deletable)
                                        <form action="{{ route('seats.destroy', $seat->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="bg-red-500 hover:bg-red-600 text-black px-3 rounded">
                                                Törlés
                                            </button>
                                        </form>
                                    @endif
                                </span>

                                <span>{{ $seat->base_price }} Ft</span>
                            </li>
                        @endforeach
                    </ul>

                    <div class="mt-4">
                        {{ $seats->links() }}
                    </div>
                </div>

                <div class="w-full sm:w-1/2 px-3 mb-6 sm:mb-0 space-y-2">
                    <h3 class="text-lg font-medium text-gray-700 mb-2">Kiválasztott szék</h3>
                    <div id="selected-seat" class="bg-white shadow rounded-lg p-6">
                        <p class="text-gray-500" id="no-seat-selected">Nincs szék kiválasztva</p>

                        <form id="seat-update-form" action="" method="POST" class="hidden space-y-4">
                            @csrf
                            @method('PUT')

                            <input type="hidden" name="seat_id" id="seat_id" value="{{ old('seat_id') }}">

                            <div>
                                <label for="seat_number" class="block text-sm font-medium text-gray-700">Szék szám</label>
                                <input type="text" name="seat_number" id="seat_number"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"
                                    value="{{ old('seat_number') }}"
                                    required>
                            </div>

                            <div>
                                <label for="base_price" class="block text-sm font-medium text-gray-700">Alap ár</label>
                                <input type="number" name="base_price" id="base_price"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"
                                    min="0"
                                    value="{{ old('base_price') }}"
                                    required>
                            </div>

                            <button type="submit"
                                class="bg-blue-600 hover:bg-blue-700 text-black font-semibold py-2 px-4 rounded">
                                <div class="bg-white shadow rounded-lg p-6">
                                    Szék frissítése
                                </div>
                            </button>
                        </form>
                    </div>
                </div>
            </div>



        </div>
    </div>

<script>
    function selectSeat(id, seatNumber, basePrice) {
        document.getElementById('seat-update-form').classList.remove('hidden');
        document.getElementById('no-seat-selected').style.display = 'none';

        document.getElementById('seat_id').value = id;
        document.getElementById('seat_number').value = seatNumber;
        document.getElementById('base_price').value = basePrice;

        document.getElementById('seat-update-form').action = '/dashboard/seats/update/' + id;
    }
</script>

</x-app-layout>
