<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800">Your Cart</h2>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-5xl px-4 sm:px-6 lg:px-8">
            @if (session('status'))
                <div class="mb-4 rounded-md bg-emerald-50 p-4 text-sm font-medium text-emerald-700">{{ session('status') }}</div>
            @endif

            <div class="overflow-hidden rounded-lg border bg-white shadow-sm">
                @if ($items->isEmpty())
                    <div class="p-8 text-center">
                        <p class="text-gray-600">Your cart is empty.</p>
                        <a href="{{ route('branches.index') }}" class="mt-4 inline-flex rounded-md bg-emerald-700 px-4 py-2 text-sm font-bold text-white">Browse branches</a>
                    </div>
                @else
                    <div class="divide-y">
                        @foreach ($items as $food)
                            <div class="grid gap-4 p-5 sm:grid-cols-[96px_1fr_auto] sm:items-center">
                                <img src="{{ $food->image ?: 'https://placehold.co/300x200?text='.urlencode($food->name) }}" alt="{{ $food->name }}" class="h-24 w-24 rounded-md object-cover">
                                <div>
                                    <h3 class="font-bold text-gray-900">{{ $food->name }}</h3>
                                    <p class="text-sm text-gray-600">{{ $food->branch->name }}</p>
                                    <p class="mt-1 text-sm font-semibold text-emerald-700">PHP {{ number_format($food->price, 2) }}</p>
                                </div>
                                <div class="flex flex-wrap items-center gap-2">
                                    <form method="POST" action="{{ route('cart.update', $food) }}" class="flex items-center gap-2">
                                        @csrf
                                        @method('PATCH')
                                        <input type="number" name="quantity" value="{{ $food->cart_quantity }}" min="0" max="20" class="w-20 rounded-md border-gray-300 text-sm">
                                        <button class="rounded-md border px-3 py-2 text-sm font-bold">Update</button>
                                    </form>
                                    <form method="POST" action="{{ route('cart.destroy', $food) }}">
                                        @csrf
                                        @method('DELETE')
                                        <button class="rounded-md px-3 py-2 text-sm font-bold text-red-600">Remove</button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="border-t bg-gray-50 p-5">
                        <div class="flex items-center justify-between text-lg font-black">
                            <span>Total</span>
                            <span>PHP {{ number_format($total, 2) }}</span>
                        </div>
                        <div class="mt-5 flex flex-wrap justify-end gap-3">
                            <form method="POST" action="{{ route('cart.clear') }}">
                                @csrf
                                @method('DELETE')
                                <button class="rounded-md border px-4 py-2 text-sm font-bold text-gray-700">Clear Cart</button>
                            </form>
                            <a href="{{ route('checkout') }}" class="rounded-md bg-emerald-700 px-4 py-2 text-sm font-bold text-white">Proceed to Checkout</a>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
