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
                                <span>{{ $seat->base_price }} Ft</span>
                            </li>
                        @endforeach
                    </ul>

                    <!-- Lapozó linkek -->
                    <div class="mt-4">
                        {{ $seats->links() }}
                    </div>
                </div>

                {{-- Jobb oldal --}}
                <div class="w-full sm:w-1/2 px-3 mb-6 sm:mb-0 space-y-2">
                    <h3 class="text-lg font-medium text-gray-700 mb-2">Kiválasztott szék</h3>
                    <div id="selected-seat" class="bg-white shadow rounded-lg p-6">
                        <p class="text-gray-500" id="no-seat-selected">Nincs szék kiválasztva</p>

                        {{-- Form a szék módosításához --}}
                        <form id="seat-update-form" action="" method="POST" class="hidden space-y-4">
                            @csrf
                            @method('PUT')

                            <input type="hidden" name="seat_id" id="seat_id">

                            <div>
                                <label for="seat_number" class="block text-sm font-medium text-gray-700">Szék szám</label>
                                <input type="text" name="seat_number" id="seat_number"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"
                                    required>
                            </div>

                            <div>
                                <label for="base_price" class="block text-sm font-medium text-gray-700">Alap ár</label>
                                <input type="number" name="base_price" id="base_price"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"
                                    min="0" required>
                            </div>

                            <button type="submit"
                                    class="bg-blue-600 hover:bg-blue-700 text-black font-semibold py-2 px-4 rounded">
                                Szék frissítése
                            </button>
                        </form>
                    </div>
                </div>
            </div>



        </div>
    </div>

<script>
    function selectSeat(id, seatNumber, basePrice) {
        // form megjelenítése
        document.getElementById('seat-update-form').classList.remove('hidden');
        document.getElementById('no-seat-selected').style.display = 'none';

        // adatok beállítása a formban
        document.getElementById('seat_id').value = id;
        document.getElementById('seat_number').value = seatNumber;
        document.getElementById('base_price').value = basePrice;

        // action beállítása a formhoz (példa: /seats/{id})
        document.getElementById('seat-update-form').action = '/dashboard/seats/' + id;
    }
</script>

</x-app-layout>
