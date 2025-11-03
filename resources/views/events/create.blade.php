<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Új esemény létrehozása') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

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

            <form action="{{ route('events.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf



                <div class="flex flex-wrap -mx-3">
                    <div class="w-full sm:w-1/2 px-3 mb-6 sm:mb-0 space-y-6">

                        <div class="bg-white shadow rounded-lg p-6">
                            <label for="title" class="block text-sm font-medium text-gray-700">Cím</label>
                            <input type="text" name="title" id="title"
                                value="{{ old('title') }}"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"
                                required>
                        </div>

                        <div class="bg-white shadow rounded-lg p-6">
                            <label for="description" class="block text-sm font-medium text-gray-700">Leírás</label>
                            <textarea name="description" id="description" rows="4"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"
                                required>{{ old('description') }}</textarea>
                        </div>

                        <div class="bg-white shadow rounded-lg p-6 flex items-center">
                            <input type="checkbox" name="is_dynamic_price" id="is_dynamic_price" value="1"
                                {{ old('is_dynamic_price') ? 'checked' : '' }}
                                class="h-4 w-4 text-blue-600 border-gray-300 rounded">
                            <label for="is_dynamic_price" class="ml-3 block text-sm text-gray-700">
                                Dinamikus árképzés engedélyezése
                            </label>
                        </div>

                        <div class="bg-white shadow rounded-lg p-6">
                            <label for="cover_image" class="block text-sm font-medium text-gray-700">Borítókép</label>
                            <input type="file" name="cover_image" id="cover_image"
                                class="mt-1 block w-full text-sm text-gray-700 border border-gray-300 rounded-md cursor-pointer bg-gray-50 focus:outline-none">
                        </div>
                    </div>

                    <div class="w-full sm:w-1/2 px-3 mb-6 sm:mb-0 space-y-6">

                        <div class="bg-white shadow rounded-lg p-6">
                            <label for="sale_start_at" class="block text-sm font-medium text-gray-700">Jegyeladás kezdete</label>
                            <input type="datetime-local" name="sale_start_at" id="sale_start_at"
                                value="{{ old('sale_start_at') }}"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"
                                required>
                        </div>

                        <div class="bg-white shadow rounded-lg p-6">
                            <label for="sale_end_at" class="block text-sm font-medium text-gray-700">Jegyeladás vége</label>
                            <input type="datetime-local" name="sale_end_at" id="sale_end_at"
                                value="{{ old('sale_end_at') }}"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"
                                required>
                        </div>

                        <div class="bg-white shadow rounded-lg p-6">
                            <label for="event_date_at" class="block text-sm font-medium text-gray-700">Esemény dátuma</label>
                            <input type="datetime-local" name="event_date_at" id="event_date_at"
                                value="{{ old('event_date_at') }}"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"
                                required>
                        </div>

                        <div class="bg-white shadow rounded-lg p-6">
                            <label for="max_number_allowed" class="block text-sm font-medium text-gray-700">Maximálisan vásárolható jegyek száma</label>
                            <input type="number" name="max_number_allowed" id="max_number_allowed"
                                value="{{ old('max_number_allowed') }}"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"
                                min="1" required>
                        </div>
                    </div>
                </div>

                <div class="flex flex-wrap -mx-3 mb-6">
                    <button type="submit"
                        class="bg-blue-600 hover:bg-blue-700 text-black font-semibold py-2 px-4 rounded">
                        <div class="bg-white shadow rounded-lg p-6">
                            Esemény létrehozása
                        </div>
                    </button>

                    <a href="{{ route('dashboard') }}">
                        <div class="bg-blue-600 hover:bg-blue-700 text-black font-semibold py-2 px-4 rounded">
                            <div class="bg-white shadow rounded-lg p-6">
                                Mégse
                            </div>
                        </div>
                    </a>
                </div>


            </form>
        </div>
    </div>
</x-app-layout>
