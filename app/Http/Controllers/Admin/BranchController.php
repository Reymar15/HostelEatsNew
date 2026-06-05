<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BranchController extends Controller
{
    public function index(): View
    {
        $branches = Branch::withCount('foods')->orderBy('name')->get();

        return view('admin.branches.index', compact('branches'));
    }

    public function create(): View
    {
        return view('admin.branches.create');
    }

    public function store(Request $request): RedirectResponse
    {
        Branch::create($this->validated($request));

        return redirect()->route('admin.branches.index')->with('status', 'Branch created.');
    }

    public function edit(Branch $branch): View
    {
        return view('admin.branches.edit', compact('branch'));
    }

    public function update(Request $request, Branch $branch): RedirectResponse
    {
        $branch->update($this->validated($request));

        return redirect()->route('admin.branches.index')->with('status', 'Branch updated.');
    }

    public function destroy(Branch $branch): RedirectResponse
    {
        $branch->delete();

        return redirect()->route('admin.branches.index')->with('status', 'Branch deleted.');
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:255'],
            'logo' => ['nullable', 'string', 'max:2048'],
        ]);
    }
}
