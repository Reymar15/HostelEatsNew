@extends('layouts.app')

@section('content')
    <section class="admin-hero compact-admin-hero">
        <div>
            <p class="crumb">Admin Settings</p>
            <h2>System preferences</h2>
            <p>Dark mode, password change, notifications, and theme color settings.</p>
        </div>
    </section>

    <section class="settings-grid">
        <article class="panel">
            <div class="panel-head">
                <div>
                    <h2>Appearance</h2>
                    <p>Personalize the admin dashboard.</p>
                </div>
            </div>
            <label class="toggle-row">
                <span><strong>Dark Mode</strong><small>Use a darker admin surface.</small></span>
                <input type="checkbox" data-dark-toggle>
            </label>
            <label class="admin-color-picker">
                Theme Color
                <input type="color" value="#0f7c55" data-theme-color>
            </label>
        </article>

        <article class="panel">
            <div class="panel-head">
                <div>
                    <h2>Change Password</h2>
                    <p>Demo-only password form, saved in this page session.</p>
                </div>
            </div>
            <form class="password-form" data-password-form>
                <label>Current Password<input type="password" required></label>
                <label>New Password<input type="password" required minlength="6"></label>
                <button class="primary-action" type="submit">Update Password</button>
                <p class="form-message" data-form-message></p>
            </form>
        </article>

        <article class="panel">
            <div class="panel-head">
                <div>
                    <h2>Notifications</h2>
                    <p>Choose which admin alerts appear.</p>
                </div>
            </div>
            <label class="toggle-row"><span><strong>New Orders</strong><small>Show order alerts.</small></span><input type="checkbox" checked data-notification-toggle></label>
            <label class="toggle-row"><span><strong>Low Stock</strong><small>Show stock warnings.</small></span><input type="checkbox" checked></label>
            <label class="toggle-row"><span><strong>Daily Reports</strong><small>Show report reminders.</small></span><input type="checkbox"></label>
        </article>
    </section>
@endsection
