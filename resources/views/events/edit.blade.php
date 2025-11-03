<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Esemény módosítása') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
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
            <form action="{{ route('events.update', $event->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf
                @method('PUT')








                <div class="flex flex-wrap -mx-3">
                    <div class="w-full sm:w-1/2 px-3 mb-6 sm:mb-0 space-y-6">
                        <!-- Cím -->
                        <div class="bg-white shadow rounded-lg p-6">
                            <label for="title" class="block text-sm font-medium text-gray-700">Cím</label>
                            <input type="text" name="title" id="title"
                                value="{{ old('title', $event->title) }}"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"
                                required>
                        </div>

                        <!-- Leírás -->
                        <div class="bg-white shadow rounded-lg p-6">
                            <label for="description" class="block text-sm font-medium text-gray-700">Leírás</label>
                            <textarea name="description" id="description" rows="4"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"
                                required>{{ old('description', $event->description) }}</textarea>
                        </div>

                        <!-- Dinamikus árképzés -->
                        <div class="bg-white shadow rounded-lg p-6 flex items-center {{ $isSaleStarted ? 'opacity-70' : '' }}">
                            <input type="checkbox" name="is_dynamic_price" id="is_dynamic_price" value="1"
                                {{ old('is_dynamic_price', $event->is_dynamic_price) ? 'checked' : '' }}
                                class="h-4 w-4 text-blue-600 border-gray-300 rounded"
                                {{ $isSaleStarted ? 'disabled' : '' }}>
                            <label for="is_dynamic_price" class="ml-3 block text-sm text-gray-700">
                                Dinamikus árképzés engedélyezése
                            </label>
                        </div>

                        <!-- Borítókép -->
                        <div class="bg-white shadow rounded-lg p-6">
                            <label for="cover_image" class="block text-sm font-medium text-gray-700 mb-2">Borítókép</label>

                            {{-- Ha már van feltöltött kép --}}
                            @if($event->cover_image)
                                <div class="mb-4">
                                    <p class="text-gray-600 text-sm mb-2">Jelenlegi borítókép:</p>
                                    <img src="{{ asset('storage/' . $event->cover_image) }}" alt="Borítókép" class="rounded-lg shadow-md max-h-48">
                                </div>
                            @endif

                            <input type="file" name="cover_image" id="cover_image"
                                class="mt-1 block w-full text-sm text-gray-700 border border-gray-300 rounded-md cursor-pointer bg-gray-50 focus:outline-none">

                            <p class="text-xs text-gray-500 mt-2">Válassz új képet, ha módosítani szeretnéd a borítóképet (max. 2MB, JPG/PNG).</p>
                        </div>
                    </div>

                    <div class="w-full sm:w-1/2 px-3 mb-6 sm:mb-0 space-y-6">
                        <!-- Jegyeladás kezdete -->
                        <div class="bg-white shadow rounded-lg p-6">
                            <label for="sale_start_at" class="block text-sm font-medium text-gray-700">Jegyeladás kezdete</label>
                            <input type="datetime-local" name="sale_start_at" id="sale_start_at"
                                value="{{ old('sale_start_at', $event->sale_start_at) }}"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm {{ $isSaleStarted ? 'bg-gray-100 text-gray-500 cursor-not-allowed' : '' }}"
                                {{ $isSaleStarted ? 'disabled' : '' }} required>
                        </div>

                        <!-- Jegyeladás vége -->
                        <div class="bg-white shadow rounded-lg p-6">
                            <label for="sale_end_at" class="block text-sm font-medium text-gray-700">Jegyeladás vége</label>
                            <input type="datetime-local" name="sale_end_at" id="sale_end_at"
                                value="{{ old('sale_end_at', $event->sale_end_at) }}"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm {{ $isSaleStarted ? 'bg-gray-100 text-gray-500 cursor-not-allowed' : '' }}"
                                {{ $isSaleStarted ? 'disabled' : '' }} required>
                        </div>

                        <!-- Esemény dátuma -->
                        <div class="bg-white shadow rounded-lg p-6">
                            <label for="event_date_at" class="block text-sm font-medium text-gray-700">Esemény dátuma</label>
                            <input type="datetime-local" name="event_date_at" id="event_date_at"
                                value="{{ old('event_date_at', $event->event_date_at) }}"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm {{ $isSaleStarted ? 'bg-gray-100 text-gray-500 cursor-not-allowed' : '' }}"
                                {{ $isSaleStarted ? 'disabled' : '' }} required>
                        </div>

                        <!-- Maximálisan vásárolható jegyek száma -->
                        <div class="bg-white shadow rounded-lg p-6">
                            <label for="max_number_allowed" class="block text-sm font-medium text-gray-700">Maximálisan vásárolható jegyek száma</label>
                            <input type="number" name="max_number_allowed" id="max_number_allowed"
                                value="{{ old('max_number_allowed', $event->max_number_allowed) }}"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm {{ $isSaleStarted ? 'bg-gray-100 text-gray-500 cursor-not-allowed' : '' }}"
                                {{ $isSaleStarted ? 'disabled' : '' }} min="1" required>
                        </div>
                    </div>
                </div>

















































                    <!-- Gomb -->

                    <div class="flex flex-wrap -mx-3 mb-6">
                        <button type="submit"
                            class="bg-blue-600 hover:bg-blue-700 text-black font-semibold py-2 px-4 rounded">
                            <div class="bg-white shadow rounded-lg p-6">
                                Esemény módosítása
                            </div>
                        </button>
                    </div>
                </form>
        </div>
    </div>

</x-app-layout>
