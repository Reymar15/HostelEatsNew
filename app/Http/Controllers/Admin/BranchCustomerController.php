<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BranchCustomerController extends Controller
{
    public function index(int $branchId): View|RedirectResponse
    {
        if (session('auth_admin_scope') === 'branch' && (int) session('auth_branch_id') !== $branchId) {
            abort(403, 'You can only view customers from your assigned branch.');
        }

        return $this->renderForBranch($branchId);
    }

    public function all(Request $request): View|RedirectResponse
    {
        if (session('auth_admin_scope') !== 'super') {
            $branchId = (int) session('auth_branch_id');
            if ($branchId) {
                return redirect()->route('admin.store.customers', $branchId);
            }
            abort(403);
        }

        $branchId = (int) $request->query('branch_id', 0);

        if ($branchId) {
            return $this->renderForBranch($branchId);
        }

        // Super Admin: all customers across all branches
        $customers = User::where('role', 'customer')
            ->whereHas('orders')
            ->withCount('orders as branch_order_count')
            ->withSum('orders as branch_total_spent', 'total')
            ->with(['orders' => fn ($q) => $q->with('branch')->latest()->limit(10)])
            ->get();

        $now          = now();
        $startOfMonth = now()->startOfMonth();

        $totalCustomers     = $customers->count();
        $activeCustomers    = $customers->filter(fn ($u) => $u->orders->where('status', '!=', 'Cancelled')->count() > 0)->count();
        $newThisMonth       = $customers->filter(fn ($u) => $u->created_at >= $startOfMonth)->count();
        $returningCustomers = $customers->filter(fn ($u) => $u->branch_order_count > 1)->count();

        $recentCustomers = Order::with(['user', 'branch'])->latest()->limit(10)->get();
        $branches        = Branch::orderBy('name')->get();

        return view('admin.customers.all', compact(
            'customers',
            'totalCustomers',
            'activeCustomers',
            'newThisMonth',
            'returningCustomers',
            'recentCustomers',
            'branches'
        ));
    }

    private function renderForBranch(int $branchId): View
    {
        $customers = User::where('role', 'customer')
            ->whereHas('orders', fn ($q) => $q->where('branch_id', $branchId))
            ->withCount(['orders as branch_order_count' => fn ($q) => $q->where('branch_id', $branchId)])
            ->withSum(['orders as branch_total_spent' => fn ($q) => $q->where('branch_id', $branchId)], 'total')
            ->with(['orders' => fn ($q) => $q->where('branch_id', $branchId)->latest()->limit(10)])
            ->get();

        $startOfMonth = now()->startOfMonth();

        $totalCustomers     = $customers->count();
        $activeCustomers    = $customers->filter(fn ($u) => $u->orders->where('status', '!=', 'Cancelled')->count() > 0)->count();
        $newThisMonth       = $customers->filter(fn ($u) => $u->created_at >= $startOfMonth)->count();
        $returningCustomers = $customers->filter(fn ($u) => $u->branch_order_count > 1)->count();

        $recentCustomers = Order::where('branch_id', $branchId)
            ->with('user')
            ->latest()
            ->limit(10)
            ->get();

        $branchName = Branch::find($branchId)?->name ?? 'Branch';

        return view('admin.customers.branch', compact(
            'customers',
            'totalCustomers',
            'activeCustomers',
            'newThisMonth',
            'returningCustomers',
            'recentCustomers',
            'branchId',
            'branchName'
        ));
    }
}
