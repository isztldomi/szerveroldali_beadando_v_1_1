<x-guest-layout>
    <div class="max-w-4xl mx-auto py-10">
        <img src="{{ $event->image }}" alt="{{ $event->title }}" class="w-full h-72 object-cover rounded-2xl mb-6">

        <h1 class="text-3xl font-bold mb-2">{{ $event->title }}</h1>
        <p class="text-gray-600 mb-4">{{ $event->event_date_at?->format('Y. m. d. H:i') }}</p>

        <p class="mb-6">{{ $event->description }}</p>

        <a href="{{ route('events.index') }}" class="text-blue-600 underline">← Vissza a főoldalra</a>
    </div>
</x-guest-layout>
