@csrf

<div class="space-y-4">
    <div>
        <label class="block text-sm font-bold text-gray-700" for="name">Name</label>
        <input id="name" name="name" value="{{ old('name', $branch->name ?? '') }}" class="mt-1 w-full rounded-md border-gray-300" required>
        @error('name') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>

    <div>
        <label class="block text-sm font-bold text-gray-700" for="description">Description</label>
        <input id="description" name="description" value="{{ old('description', $branch->description ?? '') }}" class="mt-1 w-full rounded-md border-gray-300" required>
        @error('description') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>

    <div>
        <label class="block text-sm font-bold text-gray-700" for="logo">Logo/Image URL</label>
        <input id="logo" name="logo" value="{{ old('logo', $branch->logo ?? '') }}" class="mt-1 w-full rounded-md border-gray-300">
        @error('logo') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>
</div>

<div class="mt-6 flex justify-end gap-3">
    <a href="{{ route('admin.branches.index') }}" class="rounded-md border px-4 py-2 text-sm font-bold text-gray-700">Cancel</a>
    <button class="rounded-md bg-emerald-700 px-4 py-2 text-sm font-bold text-white">{{ $button }}</button>
</div>
