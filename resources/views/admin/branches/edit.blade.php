<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800">Edit Branch</h2>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-3xl px-4 sm:px-6 lg:px-8">
            <form method="POST" action="{{ route('admin.branches.update', $branch) }}" class="rounded-lg border bg-white p-6 shadow-sm">
                @method('PATCH')
                @include('admin.branches._form', ['button' => 'Update Branch'])
            </form>
        </div>
    </div>
</x-app-layout>
