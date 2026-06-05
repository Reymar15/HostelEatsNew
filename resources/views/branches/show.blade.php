<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-wrap items-center justify-between gap-3">
            <div>
                <h2 class="text-xl font-semibold text-gray-800">{{ $branch->name }}</h2>
                <p class="text-sm text-gray-500">{{ $branch->description }}</p>
            </div>
            <a href="{{ route('cart.index') }}" class="rounded-md bg-gray-900 px-4 py-2 text-sm font-bold text-white">View Cart</a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            @if (session('status'))
                <div class="mb-4 rounded-md bg-emerald-50 p-4 text-sm font-medium text-emerald-700">{{ session('status') }}</div>
            @endif

            <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
                @forelse ($branch->foods as $food)
                    <article class="overflow-hidden rounded-lg border bg-white shadow-sm">
                        <img src="{{ $food->image ?: 'https://placehold.co/800x450?text='.urlencode($food->name) }}" alt="{{ $food->name }}" class="h-44 w-full object-cover">
                        <div class="p-5">
                            <h3 class="text-lg font-bold text-gray-900">{{ $food->name }}</h3>
                            <p class="mt-1 min-h-10 text-sm text-gray-600">{{ $food->description }}</p>
                            <div class="mt-4 flex items-center justify-between gap-4">
                                <strong class="text-lg text-emerald-700">PHP {{ number_format($food->price, 2) }}</strong>
                                <form method="POST" action="{{ route('cart.store', $food) }}" class="flex items-center gap-2">
                                    @csrf
                                    <input type="number" name="quantity" value="1" min="1" max="20" class="w-16 rounded-md border-gray-300 text-sm">
                                    <button class="rounded-md bg-emerald-700 px-4 py-2 text-sm font-bold text-white">Add</button>
                                </form>
                            </div>
                        </div>
                    </article>
                @empty
                    <div class="rounded-lg border bg-white p-8 text-gray-600">No foods yet for this branch.</div>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>
