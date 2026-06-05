<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800">Checkout</h2>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8">
            <div class="rounded-lg border bg-white p-6 shadow-sm">
                <h3 class="text-lg font-bold text-gray-900">Order Summary</h3>
                <div class="mt-4 divide-y">
                    @foreach ($items as $food)
                        <div class="flex justify-between gap-4 py-3 text-sm">
                            <span>{{ $food->name }} x {{ $food->cart_quantity }}</span>
                            <span class="font-bold">PHP {{ number_format($food->line_total, 2) }}</span>
                        </div>
                    @endforeach
                </div>
                <div class="mt-5 flex justify-between border-t pt-5 text-lg font-black">
                    <span>Total</span>
                    <span>PHP {{ number_format($total, 2) }}</span>
                </div>
                <form method="POST" action="{{ route('checkout.store') }}" class="mt-6 flex justify-end">
                    @csrf
                    <button class="rounded-md bg-emerald-700 px-5 py-2.5 text-sm font-bold text-white">Place Order</button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
