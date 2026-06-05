<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use Illuminate\View\View;

class BranchController extends Controller
{
    public function index(): View
    {
        $branches = Branch::withCount('foods')->orderBy('name')->get();

        return view('branches.index', compact('branches'));
    }

    public function show(Branch $branch): View
    {
        $branch->load(['foods' => fn ($query) => $query->orderBy('name')]);

        return view('branches.show', compact('branch'));
    }
}
