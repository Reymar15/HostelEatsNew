@extends('layouts.app')

@section('content')
    <section class="panel">
        <div class="panel-head">
            <div>
                <h2>Completed Orders</h2>
                <p>Completed fake orders saved in your current Laravel session.</p>
            </div>
        </div>

        <div class="table-wrap">
            <table class="history-table">
                <thead>
                    <tr>
                        <th>Order Number</th>
                        <th>Food Items</th>
                        <th>Total Amount</th>
                        <th>Delivery Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($history as $order)
                        <tr data-searchable="{{ strtolower(($order['order_number'] ?? '').' '.($order['foods'] ?? '').' '.($order['delivery_status'] ?? '')) }}">
                            <td>{{ $order['order_number'] }}</td>
                            <td>{{ $order['foods'] }}</td>
                            <td>PHP{{ number_format($order['total'], 2) }}</td>
                            <td><span class="status-pill delivered">{{ $order['delivery_status'] ?? 'Delivered' }}</span></td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4">No completed fake orders yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
@endsection
