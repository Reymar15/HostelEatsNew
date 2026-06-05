<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function welcome(): View
    {
        $branches = Schema::hasTable('branches')
            ? Branch::with('foods')->take(5)->get()
            : collect();

        return view('welcome', compact('branches'));
    }

    public function dashboard(): RedirectResponse
    {
        if (auth()->user()?->is_admin) {
            return redirect()->route('admin.orders.index');
        }

        return redirect()->route('branches.index');
    }
}
