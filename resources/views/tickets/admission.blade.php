<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Jegykezelés') }}
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

            <div class="bg-white shadow rounded-lg p-6">
                <form action="{{ route('tickets.check') }}" method="POST">
                    @csrf
                    <label for="barcode" class="block text-sm font-medium text-gray-700">Jegy vonalkód</label>
                    <input type="text" name="barcode" id="barcode"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"
                        placeholder="Írd be a jegy vonalkódját..."
                        value="{{ old('barcode') }}"
                        required>
                    <button type="submit"
                        class="mt-4 bg-blue-600 hover:bg-blue-700 text-black font-semibold py-2 px-4 rounded">
                        Beolvasás
                    </button>
                </form>
            </div>


        </div>
    </div>
</x-app-layout>
