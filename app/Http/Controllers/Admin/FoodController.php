<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\Food;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class FoodController extends Controller
{
    public function index(): View
    {
        $foods = Food::with('branch')->orderBy('name')->get();

        return view('admin.foods.index', compact('foods'));
    }

    public function create(): View
    {
        return view('admin.foods.create', [
            'branches' => Branch::orderBy('name')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        Food::create($this->validated($request));

        return redirect()->route('admin.foods.index')->with('status', 'Food created.');
    }

    public function edit(Food $food): View
    {
        return view('admin.foods.edit', [
            'food' => $food,
            'branches' => Branch::orderBy('name')->get(),
        ]);
    }

    public function update(Request $request, Food $food): RedirectResponse
    {
        $food->update($this->validated($request));

        return redirect()->route('admin.foods.index')->with('status', 'Food updated.');
    }

    public function destroy(Food $food): RedirectResponse
    {
        $food->delete();

        return redirect()->route('admin.foods.index')->with('status', 'Food deleted.');
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'branch_id' => ['required', 'exists:branches,id'],
            'name' => ['required', 'string', 'max:255'],
            'price' => ['required', 'numeric', 'min:1', 'max:99999'],
            'image' => ['nullable', 'string', 'max:2048'],
            'description' => ['nullable', 'string', 'max:1000'],
        ]);
    }
}
