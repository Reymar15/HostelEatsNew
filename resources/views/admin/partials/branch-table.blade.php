<section class="panel admin-table-gap" id="branches-table">
    <div class="panel-head">
        <div>
            <h2>Branch Management</h2>
            <p>Add, edit, deactivate, and monitor partner branches.</p>
        </div>
        <button type="button" class="primary-action" data-admin-modal="branch-modal">Add Branch</button>
    </div>

    <div class="admin-filter-bar">
        <input type="search" placeholder="Search branch..." data-admin-search="branches-table">
        <select data-admin-filter="branches-table" data-filter-key="status">
            <option value="">All statuses</option>
            <option value="active">Active</option>
            <option value="inactive">Inactive</option>
        </select>
    </div>

    <div class="table-wrap">
        <table class="history-table admin-data-table" id="branches-table">
            <thead>
                <tr>
                    <th>Branch</th>
                    <th>Description</th>
                    <th>Orders Today</th>
                    <th>Status</th>
                    <th>Logo Upload</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($branches as $branch)
                    @php($status = strtolower($branch['status_label']))
                    <tr data-searchable="{{ strtolower($branch['name'].' '.$branch['description'].' '.$branch['status_label']) }}" data-status="{{ $status }}">
                        <td>
                            <div class="admin-media-cell">
                                <img src="{{ $branch['logo'] }}" alt="{{ $branch['name'] }} logo">
                                <span><strong>{{ $branch['name'] }}</strong><small>{{ $branch['status'] }}</small></span>
                            </div>
                        </td>
                        <td>{{ $branch['description'] }}</td>
                        <td>{{ $branch['orders_today'] }}</td>
                        <td><span class="status-pill {{ $status === 'active' ? 'delivered' : 'cancelled' }}">{{ $branch['status_label'] }}</span></td>
                        <td><input type="file" class="compact-file" aria-label="Upload {{ $branch['name'] }} logo"></td>
                        <td>
                            <div class="row-actions">
                                <button type="button" data-admin-modal="branch-modal">Edit</button>
                                <button type="button" class="danger">Delete</button>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</section>
