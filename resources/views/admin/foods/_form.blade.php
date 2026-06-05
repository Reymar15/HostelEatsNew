@csrf

<div class="grid gap-4 sm:grid-cols-2">
    <div>
        <label class="block text-sm font-bold text-gray-700" for="branch_id">Branch</label>
        <select id="branch_id" name="branch_id" class="mt-1 w-full rounded-md border-gray-300" required>
            @foreach ($branches as $branch)
                <option value="{{ $branch->id }}" @selected((int) old('branch_id', $food->branch_id ?? 0) === $branch->id)>{{ $branch->name }}</option>
            @endforeach
        </select>
        @error('branch_id') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>

    <div>
        <label class="block text-sm font-bold text-gray-700" for="name">Food Name</label>
        <input id="name" name="name" value="{{ old('name', $food->name ?? '') }}" class="mt-1 w-full rounded-md border-gray-300" required>
        @error('name') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>

    <div>
        <label class="block text-sm font-bold text-gray-700" for="price">Price</label>
        <input id="price" name="price" type="number" step="0.01" min="1" value="{{ old('price', $food->price ?? '') }}" class="mt-1 w-full rounded-md border-gray-300" required>
        @error('price') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>

    <div>
        <label class="block text-sm font-bold text-gray-700" for="image">Image URL</label>
        <input id="image" name="image" value="{{ old('image', $food->image ?? '') }}" class="mt-1 w-full rounded-md border-gray-300">
        @error('image') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>

    <div class="sm:col-span-2">
        <label class="block text-sm font-bold text-gray-700" for="description">Description</label>
        <textarea id="description" name="description" rows="4" class="mt-1 w-full rounded-md border-gray-300">{{ old('description', $food->description ?? '') }}</textarea>
        @error('description') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>
</div>

<div class="mt-6 flex justify-end gap-3">
    <a href="{{ route('admin.foods.index') }}" class="rounded-md border px-4 py-2 text-sm font-bold text-gray-700">Cancel</a>
    <button class="rounded-md bg-emerald-700 px-4 py-2 text-sm font-bold text-white">{{ $button }}</button>
</div>
