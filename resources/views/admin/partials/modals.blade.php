@foreach ([
    'food-modal' => ['title' => 'Food Form', 'body' => 'Add or edit fake food information, image preview, branch, category, price, and availability.'],
    'branch-modal' => ['title' => 'Branch Form', 'body' => 'Add or edit branch name, logo upload, branch status, and operating details.'],
    'order-modal' => ['title' => 'Order Details', 'body' => 'Review customer information, ordered foods, payment method, status, and delivery notes.'],
] as $id => $modal)
    <div class="admin-modal" data-modal-id="{{ $id }}" aria-hidden="true">
        <div class="admin-modal-card">
            <header>
                <div>
                    <p class="crumb">Admin Action</p>
                    <h2>{{ $modal['title'] }}</h2>
                </div>
                <button type="button" class="secondary-button icon-button" data-close-admin-modal>X</button>
            </header>
            <p>{{ $modal['body'] }}</p>
            <div class="admin-form-grid">
                <input type="text" placeholder="Name or title">
                <input type="text" placeholder="Branch / status">
                <input type="number" placeholder="Price or stock">
                <input type="file">
            </div>
            <div class="dialog-actions">
                <button type="button" class="secondary-button" data-close-admin-modal>Cancel</button>
                <button type="button" class="primary-action" data-close-admin-modal>Save Changes</button>
            </div>
        </div>
    </div>
@endforeach

<div class="floating-actions">
    <button type="button" data-admin-modal="food-modal">Add Food</button>
    <button type="button" data-admin-modal="branch-modal">Add Branch</button>
    <button type="button" data-fake-download>Generate Report</button>
</div>
