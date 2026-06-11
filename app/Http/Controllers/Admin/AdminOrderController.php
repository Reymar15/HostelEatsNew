<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\Food;
use App\Models\Order;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminOrderController extends Controller
{
    private function authorizeOrder(Order $order): void
    {
        $user = auth()->user();

        if ($user->isBranchAdmin() && (int) $order->branch_id !== (int) $user->branch_id) {
            abort(403, 'You can only manage orders for your own branch.');
        }
    }

    private function baseQuery()
    {
        $user = auth()->user();
        $query = Order::with(['branch', 'user'])->latest('order_date');

        if ($user->isBranchAdmin()) {
            $query->where('branch_id', $user->branch_id);
        }

        return $query;
    }

    public function index(Request $request): View
    {
        $user = auth()->user();
        $branches = $user->isSuperAdmin() ? Branch::orderBy('name')->get() : collect();

        $query = $this->baseQuery();

        if ($request->filled('branch_id') && $user->isSuperAdmin()) {
            $query->where('branch_id', $request->branch_id);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('customer_name', 'like', "%{$search}%")
                  ->orWhere('food_item', 'like', "%{$search}%")
                  ->orWhere('id', 'like', "%{$search}%");
            });
        }

        $orders = $query->paginate(20)->withQueryString();

        return view('admin.orders.index', compact('orders', 'branches'));
    }

    public function update(Request $request, Order $order): RedirectResponse
    {
        $this->authorizeOrder($order);

        $validated = $request->validate([
            'customer_name' => ['required', 'string', 'max:120'],
            'food_item'     => ['required', 'string', 'max:120'],
            'quantity'      => ['required', 'integer', 'min:1'],
            'total'         => ['required', 'numeric', 'min:0'],
            'notes'         => ['nullable', 'string', 'max:500'],
        ]);

        $order->update($validated);

        $redirect = auth()->user()->isBranchAdmin()
            ? route('admin.store.dashboard', auth()->user()->branch_id)
            : route('admin.orders.index');

        return redirect($redirect)->with('success', 'Order updated successfully.');
    }

    public function updateStatus(Request $request, Order $order): RedirectResponse
    {
        $this->authorizeOrder($order);

        $validated = $request->validate([
            'status' => ['required', 'in:Pending,Preparing,Ready for Pickup,Completed,Cancelled'],
        ]);

        $order->update(['status' => $validated['status']]);

        // Send email notification when order is marked Completed
        if ($validated['status'] === 'Completed' && $order->user) {
            try {
                $order->user->notify(new \App\Notifications\OrderCompletedNotification(
                    (string) $order->id,
                    $order->food_item ?? 'Your order',
                    (float) $order->total,
                    $order->branch->name ?? 'HostelEats'
                ));
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error('Order completed email failed: ' . $e->getMessage());
            }
        }

        return back()->with('success', 'Order status updated.');
    }

    public function destroy(Order $order): RedirectResponse
    {
        $user = auth()->user();

        if (! $user->isSuperAdmin()) {
            abort(403, 'Only Super Admins can delete orders.');
        }

        $order->delete();

        return back()->with('success', 'Order deleted.');
    }

    public function storeDashboardOrders(int $branchId): View
    {
        $user = auth()->user();

        if ($user->isBranchAdmin() && (int) $user->branch_id !== $branchId) {
            abort(403, 'You can only view your own branch orders.');
        }

        $branch = Branch::findOrFail($branchId);
        $orders = Order::with('user')
            ->where('branch_id', $branchId)
            ->latest('order_date')
            ->get();

        $foods = Food::where('branch_id', $branchId)->orderBy('name')->get();

        return view('admin.orders.branch', compact('orders', 'branch', 'foods'));
    }
}
