<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800">Order #{{ $order->id }}</h2>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto grid max-w-6xl gap-6 px-4 sm:px-6 lg:grid-cols-[1fr_320px] lg:px-8">
            @if (session('status'))
                <div class="rounded-md bg-emerald-50 p-4 text-sm font-medium text-emerald-700 lg:col-span-2">{{ session('status') }}</div>
            @endif

            <section class="rounded-lg border bg-white p-6 shadow-sm">
                <h3 class="text-lg font-black text-gray-900">Items</h3>
                <div class="mt-4 divide-y">
                    @foreach ($order->items as $item)
                        <div class="flex justify-between gap-4 py-3 text-sm">
                            <div>
                                <p class="font-bold">{{ $item->food->name ?? 'Deleted food' }}</p>
                                <p class="text-gray-500">{{ $item->food->branch->name ?? '' }} x {{ $item->quantity }}</p>
                            </div>
                            <p class="font-bold">PHP {{ number_format($item->price * $item->quantity, 2) }}</p>
                        </div>
                    @endforeach
                </div>
                <div class="mt-5 flex justify-between border-t pt-5 text-lg font-black">
                    <span>Total</span>
                    <span>PHP {{ number_format($order->total, 2) }}</span>
                </div>
            </section>

            <aside class="rounded-lg border bg-white p-6 shadow-sm">
                <h3 class="text-lg font-black text-gray-900">Customer</h3>
                <p class="mt-2 font-bold">{{ $order->user->name }}</p>
                <p class="text-sm text-gray-600">{{ $order->user->email }}</p>

                <form method="POST" action="{{ route('admin.orders.update', $order) }}" class="mt-6">
                    @csrf
                    @method('PATCH')
                    <label class="block text-sm font-bold text-gray-700" for="status">Order Status</label>
                    <select id="status" name="status" class="mt-1 w-full rounded-md border-gray-300">
                        @foreach (['Pending', 'Preparing', 'Out for Delivery', 'Completed', 'Cancelled'] as $status)
                            <option value="{{ $status }}" @selected($order->status === $status)>{{ $status }}</option>
                        @endforeach
                    </select>
                    <button class="mt-4 w-full rounded-md bg-emerald-700 px-4 py-2 text-sm font-bold text-white">Update Status</button>
                </form>
            </aside>
        </div>
    </div>
</x-app-layout>
