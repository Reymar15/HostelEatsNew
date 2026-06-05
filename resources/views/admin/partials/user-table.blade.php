<section class="panel admin-table-gap" id="users-table">
    <div class="panel-head">
        <div>
            <h2>User Management</h2>
            <p>Student accounts, hostel blocks, and account controls.</p>
        </div>
    </div>

    <div class="admin-filter-bar">
        <input type="search" placeholder="Search users..." data-admin-search="users-table">
        <select data-admin-filter="users-table" data-filter-key="status">
            <option value="">All statuses</option>
            <option value="active">Active</option>
            <option value="disabled">Disabled</option>
        </select>
    </div>

    <div class="table-wrap">
        <table class="history-table admin-data-table" id="users-table">
            <thead>
                <tr>
                    <th>User</th>
                    <th>Email</th>
                    <th>Hostel Block</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($adminUsers as $user)
                    <tr data-searchable="{{ strtolower($user['name'].' '.$user['email'].' '.$user['hostel_block'].' '.$user['status']) }}" data-status="{{ strtolower($user['status']) }}">
                        <td>
                            <div class="admin-media-cell">
                                <span class="avatar mini-avatar">{{ $user['avatar'] }}</span>
                                <span><strong>{{ $user['name'] }}</strong><small>Student account</small></span>
                            </div>
                        </td>
                        <td>{{ $user['email'] }}</td>
                        <td>{{ $user['hostel_block'] }}</td>
                        <td><span class="status-pill {{ strtolower($user['status']) === 'active' ? 'delivered' : 'cancelled' }}">{{ $user['status'] }}</span></td>
                        <td><button type="button" class="danger-button">Disable Account</button></td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</section>
