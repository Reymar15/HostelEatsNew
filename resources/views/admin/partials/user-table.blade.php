<section class="panel admin-table-gap" id="users-table">
    <div class="panel-head">
        <div>
            <h2>User Management</h2>
            <p>Registered student accounts with email verification status.</p>
        </div>
    </div>

    @php
        $dbUsers = \App\Models\User::where('role', 'customer')
            ->orWhere(fn ($q) => $q->where('is_admin', false)->whereNull('role'))
            ->latest()
            ->get();
        $hasDbUsers = $dbUsers->isNotEmpty();
    @endphp

    <div class="admin-filter-bar">
        <input type="search" placeholder="Search users..." data-admin-search="users-table">
        <select data-admin-filter="users-table" data-filter-key="verified">
            <option value="">All verification statuses</option>
            <option value="verified">Verified</option>
            <option value="unverified">Unverified</option>
        </select>
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
                    <th>Hostel Block / Room</th>
                    <th>Email Verified</th>
                    <th>Verification Date</th>
                    <th>Account Status</th>
                </tr>
            </thead>
            <tbody>
                @if ($hasDbUsers)
                    @foreach ($dbUsers as $dbUser)
                        @php
                            $isVerified   = $dbUser->hasVerifiedEmail();
                            $verifiedDate = $isVerified ? $dbUser->email_verified_at->format('M d, Y h:i A') : '—';
                            $nameParts    = array_filter(explode(' ', trim($dbUser->name)));
                            $initials     = strtoupper(substr($nameParts[0] ?? 'U', 0, 1));
                            if (count($nameParts) > 1) { $initials .= strtoupper(substr(end($nameParts), 0, 1)); }
                            $block        = $dbUser->hostel_block ? $dbUser->hostel_block . ($dbUser->room_number ? ' - ' . $dbUser->room_number : '') : ($adminUsers[0]['hostel_block'] ?? '—');
                        @endphp
                        <tr
                            data-searchable="{{ strtolower($dbUser->name . ' ' . $dbUser->email . ' ' . ($dbUser->hostel_block ?? '')) }}"
                            data-verified="{{ $isVerified ? 'verified' : 'unverified' }}"
                            data-status="active">
                            <td>
                                <div class="admin-media-cell">
                                    <span class="avatar mini-avatar">{{ $initials }}</span>
                                    <span>
                                        <strong>{{ $dbUser->name }}</strong>
                                        <small>Registered {{ $dbUser->created_at->format('M d, Y') }}</small>
                                    </span>
                                </div>
                            </td>
                            <td>{{ $dbUser->email }}</td>
                            <td>{{ $block }}</td>
                            <td>
                                @if ($isVerified)
                                    <span class="status-pill delivered">✓ Verified</span>
                                @else
                                    <span class="status-pill cancelled">✗ Unverified</span>
                                @endif
                            </td>
                            <td style="font-size:.8rem;color:#6b7280;">{{ $verifiedDate }}</td>
                            <td><span class="status-pill delivered">Active</span></td>
                        </tr>
                    @endforeach
                @else
                    {{-- Fallback to session-based sample users --}}
                    @foreach ($adminUsers as $user)
                        <tr
                            data-searchable="{{ strtolower($user['name'] . ' ' . $user['email'] . ' ' . $user['hostel_block'] . ' ' . $user['status']) }}"
                            data-verified="unverified"
                            data-status="{{ strtolower($user['status']) }}">
                            <td>
                                <div class="admin-media-cell">
                                    <span class="avatar mini-avatar">{{ $user['avatar'] }}</span>
                                    <span><strong>{{ $user['name'] }}</strong><small>Student account</small></span>
                                </div>
                            </td>
                            <td>{{ $user['email'] }}</td>
                            <td>{{ $user['hostel_block'] }}</td>
                            <td><span class="status-pill cancelled">✗ Unverified</span></td>
                            <td style="font-size:.8rem;color:#6b7280;">—</td>
                            <td>
                                <span class="status-pill {{ strtolower($user['status']) === 'active' ? 'delivered' : 'cancelled' }}">
                                    {{ $user['status'] }}
                                </span>
                            </td>
                        </tr>
                    @endforeach
                @endif
            </tbody>
        </table>
    </div>

    <p style="font-size:.78rem;color:#9ca3af;padding:.5rem 0 0;text-align:right;">
        ℹ Admin cannot manually verify user emails. Verification is done by the user via Gmail.
    </p>
</section>
