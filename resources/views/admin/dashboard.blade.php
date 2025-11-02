<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Irányítópult') }}
            </h2>
            <div>
                <span class="text-sm text-gray-600">Admin felület</span>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            {{-- Összegző kártyák --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                <div class="bg-white shadow rounded-lg p-4">
                    <div class="text-sm text-gray-500">Összes esemény</div>
                    <div class="text-2xl font-bold">{{ $totalEvents ?? 0 }}</div>
                </div>

                <div class="bg-white shadow rounded-lg p-4">
                    <div class="text-sm text-gray-500">Összes eladott jegy</div>
                    <div class="text-2xl font-bold">{{ $totalTicketsSold ?? 0 }}</div>
                </div>

                <div class="bg-white shadow rounded-lg p-4">
                    <div class="text-sm text-gray-500">Összbevétel</div>
                    <div class="text-2xl font-bold">{{ number_format($totalRevenue ?? 0, 0, ',', ' ') }} Ft</div>
                </div>

                <div class="bg-white shadow rounded-lg p-4">
                    <div class="text-sm text-gray-500">Top 3 ülőhely</div>
                    <div class="mt-2 space-y-1">
                        @if(!empty($topSeats) && count($topSeats) > 0)
                            @foreach($topSeats as $idx => $s)
                                <div class="flex justify-between">
                                    <div class="text-sm">{{ ($idx+1) }}. {{ $s['seat_number'] }}</div>
                                    <div class="text-sm font-medium">{{ $s['tickets_sold'] }} db</div>
                                </div>
                            @endforeach
                        @else
                            <div class="text-sm text-gray-500">Nincs adat</div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Esemény lista (lapozható, 5 / oldal) --}}
            <div class="bg-white shadow rounded-lg">
                <div class="p-4 border-b">
                    <h3 class="text-lg font-semibold">Események (lapozható, oldalanként 5)</h3>
                </div>

                <div class="p-4 overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">#</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cím</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Dátum</th>
                                <th class="px-4 py-2 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Szabad jegyek</th>
                                <th class="px-4 py-2 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Eddigi bevétel (Ft)</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            {{-- events várhatóan egy paginator vagy collection --}}
                            @forelse($events as $index => $ev)
                                <tr>
                                    <td class="px-4 py-3 text-sm text-gray-700">{{ ($events->firstItem() ?? ($index+1)) + $index }}</td>
                                    <td class="px-4 py-3 text-sm text-gray-700">{{ $ev->title ?? '—' }}</td>
                                    <td class="px-4 py-3 text-sm text-gray-700">{{ optional($ev->event_date_at)->format('Y-m-d H:i') ?? '—' }}</td>
                                    <td class="px-4 py-3 text-sm text-gray-700 text-right">{{ $ev->available_seats ?? ($ev->total_seats ?? '—') }}</td>
                                    <td class="px-4 py-3 text-sm text-gray-700 text-right">{{ number_format($ev->revenue ?? 0, 0, ',', ' ') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-4 py-6 text-center text-gray-500">Nincsenek események</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Lapozás (ha paginator) --}}
                <div class="p-4 flex items-center justify-center">
                    @if(method_exists($events, 'links'))
                        {{ $events->links() }}
                    @else
                        {{-- statikus példa lapozás: --}}
                        <nav class="inline-flex -space-x-px rounded-md shadow-sm" aria-label="Pagination">
                            <a href="#" class="px-3 py-2 rounded-l-md border bg-white text-sm">Előző</a>
                            <a href="#" class="px-3 py-2 border bg-white text-sm">1</a>
                            <a href="#" class="px-3 py-2 border bg-white text-sm">2</a>
                            <a href="#" class="px-3 py-2 rounded-r-md border bg-white text-sm">Következő</a>
                        </nav>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
