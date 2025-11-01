<x-guest-layout>
    <div class="max-w-4xl mx-auto py-10 text-center">
        @if ($event->cover_image)
            <img src="{{ asset('storage/' . $event->cover_image) }}"
                 alt="{{ $event->title }}"
                 class="w-[300px] h-[200px] object-cover rounded-2xl mb-6 mx-auto">
        @else
            <div class="w-[300px] h-[200px] bg-gray-200 flex items-center justify-center rounded-2xl text-gray-500 mb-6 mx-auto">
                Nincs kép
            </div>
        @endif

        <h1 class="text-3xl font-bold mb-2">{{ $event->title }}</h1>
        <p class="text-gray-600 mb-4">{{ $event->event_date_at?->format('Y. m. d. H:i') }}</p>

        <p class="mb-6">{{ $event->description }}</p>

        <a href="{{ route('events.index') }}"
           class="inline-block bg-blue-100 text-blue-800 px-4 py-2 rounded hover:bg-blue-200 transition">
            ← Vissza a főoldalra
        </a>
    </div>
</x-guest-layout>
