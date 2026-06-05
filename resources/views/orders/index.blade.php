<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800">Order History</h2>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-6xl px-4 sm:px-6 lg:px-8">
            @if (session('status'))
                <div class="mb-4 rounded-md bg-emerald-50 p-4 text-sm font-medium text-emerald-700">{{ session('status') }}</div>
            @endif

            <div class="space-y-4">
                @forelse ($orders as $order)
                    <article class="rounded-lg border bg-white p-5 shadow-sm">
                        <div class="flex flex-wrap items-start justify-between gap-4">
                            <div>
                                <h3 class="font-black text-gray-900">Order #{{ $order->id }}</h3>
                                <p class="text-sm text-gray-500">{{ $order->created_at->format('M d, Y h:i A') }}</p>
                            </div>
                            <div class="text-right">
                                <span class="rounded-full bg-gray-100 px-3 py-1 text-xs font-bold text-gray-700">{{ $order->status }}</span>
                                <p class="mt-2 font-black text-emerald-700">PHP {{ number_format($order->total, 2) }}</p>
                            </div>
                        </div>
                        <div class="mt-4 divide-y">
                            @foreach ($order->items as $item)
                                <div class="flex justify-between gap-4 py-2 text-sm">
                                    <span>{{ $item->food->name ?? 'Deleted food' }} x {{ $item->quantity }}</span>
                                    <span>PHP {{ number_format($item->price * $item->quantity, 2) }}</span>
                                </div>
                            @endforeach
                        </div>
                    </article>
                @empty
                    <div class="rounded-lg border bg-white p-8 text-center text-gray-600">No orders yet.</div>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>
