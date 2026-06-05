<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800">Add Branch</h2>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-3xl px-4 sm:px-6 lg:px-8">
            <form method="POST" action="{{ route('admin.branches.store') }}" class="rounded-lg border bg-white p-6 shadow-sm">
                @include('admin.branches._form', ['button' => 'Create Branch'])
            </form>
        </div>
    </div>
</x-app-layout>
