<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-xl font-semibold text-gray-800">Manage Branches</h2>
            <a href="{{ route('admin.branches.create') }}" class="rounded-md bg-emerald-700 px-4 py-2 text-sm font-bold text-white">Add Branch</a>
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
                            <th class="px-5 py-3">Branch</th>
                            <th class="px-5 py-3">Description</th>
                            <th class="px-5 py-3">Foods</th>
                            <th class="px-5 py-3 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y">
                        @foreach ($branches as $branch)
                            <tr>
                                <td class="px-5 py-4 font-bold">{{ $branch->name }}</td>
                                <td class="px-5 py-4 text-gray-600">{{ $branch->description }}</td>
                                <td class="px-5 py-4">{{ $branch->foods_count }}</td>
                                <td class="px-5 py-4">
                                    <div class="flex justify-end gap-2">
                                        <a href="{{ route('admin.branches.edit', $branch) }}" class="rounded-md border px-3 py-1.5 font-bold">Edit</a>
                                        <form method="POST" action="{{ route('admin.branches.destroy', $branch) }}" onsubmit="return confirm('Delete this branch?')">
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
