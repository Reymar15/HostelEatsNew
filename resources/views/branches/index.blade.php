<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800">Browse Branches</h2>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            @if (session('status'))
                <div class="mb-4 rounded-md bg-emerald-50 p-4 text-sm font-medium text-emerald-700">{{ session('status') }}</div>
            @endif

            <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ($branches as $branch)
                    <a href="{{ route('branches.show', $branch) }}" class="overflow-hidden rounded-lg border bg-white shadow-sm transition hover:-translate-y-0.5 hover:shadow-md">
                        <img src="{{ $branch->logo ?: 'https://placehold.co/800x450?text='.urlencode($branch->name) }}" alt="{{ $branch->name }}" class="h-44 w-full object-cover">
                        <div class="p-5">
                            <div class="flex items-start justify-between gap-4">
                                <div>
                                    <h3 class="text-lg font-bold text-gray-900">{{ $branch->name }}</h3>
                                    <p class="mt-1 text-sm text-gray-600">{{ $branch->description }}</p>
                                </div>
                                <span class="rounded-full bg-emerald-50 px-3 py-1 text-xs font-bold text-emerald-700">{{ $branch->foods_count }} foods</span>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    </div>
</x-app-layout>
