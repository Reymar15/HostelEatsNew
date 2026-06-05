<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-xl font-semibold text-gray-800">Manage Foods</h2>
            <a href="{{ route('admin.foods.create') }}" class="rounded-md bg-emerald-700 px-4 py-2 text-sm font-bold text-white">Add Food</a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            @if (session('status'))
                <div class="mb-4 rounded-md bg-emerald-50 p-4 text-sm font-medium text-emerald-700">{{ session('status') }}</div>
            @endif

            <div class="overflow-hidden rounded-lg border bg-white shadow-sm">
                <table class="w-full divide-y text-left text-sm">
                    <thead class="bg-gray-50 text-xs uppercase text-gray-500">
                        <tr>
                            <th class="px-5 py-3">Food</th>
                            <th class="px-5 py-3">Branch</th>
                            <th class="px-5 py-3">Price</th>
                            <th class="px-5 py-3 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y">
                        @foreach ($foods as $food)
                            <tr>
                                <td class="px-5 py-4">
                                    <div class="flex items-center gap-3">
                                        <img src="{{ $food->image ?: 'https://placehold.co/80x80?text=Food' }}" alt="{{ $food->name }}" class="h-12 w-12 rounded-md object-cover">
                                        <span class="font-bold">{{ $food->name }}</span>
                                    </div>
                                </td>
                                <td class="px-5 py-4 text-gray-600">{{ $food->branch->name }}</td>
                                <td class="px-5 py-4">PHP {{ number_format($food->price, 2) }}</td>
                                <td class="px-5 py-4">
                                    <div class="flex justify-end gap-2">
                                        <a href="{{ route('admin.foods.edit', $food) }}" class="rounded-md border px-3 py-1.5 font-bold">Edit</a>
                                        <form method="POST" action="{{ route('admin.foods.destroy', $food) }}" onsubmit="return confirm('Delete this food?')">
                                            @csrf
                                            @method('DELETE')
                                            <button class="rounded-md px-3 py-1.5 font-bold text-red-600">Delete</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
