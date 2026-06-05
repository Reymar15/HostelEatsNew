@extends('layouts.app')

@section('content')
    <section class="settings-grid">
        <article class="panel">
            <div class="panel-head">
                <div>
                    <h2>Preferences</h2>
                    <p>Stored in this browser for the demo.</p>
                </div>
            </div>

            <label class="toggle-row">
                <span>
                    <strong>Dark mode</strong>
                    <small>Switch to a deeper interface theme.</small>
                </span>
                <input type="checkbox" data-dark-toggle>
            </label>

            <label class="toggle-row">
                <span>
                    <strong>Notifications</strong>
                    <small>Receive fake order status alerts.</small>
                </span>
                <input type="checkbox" checked data-notification-toggle>
            </label>
        </article>

        <article class="panel">
            <div class="panel-head">
                <div>
                    <h2>Change Password</h2>
                    <p>Frontend-only form. No account data is changed.</p>
                </div>
            </div>

            <form class="password-form" data-password-form>
                <label>Current password <input type="password" required></label>
                <label>New password <input type="password" required minlength="6"></label>
                <label>Confirm password <input type="password" required minlength="6"></label>
                <button type="submit" class="primary-action">Update Password</button>
                <p class="form-message" data-form-message></p>
            </form>
        </article>
    </section>
@endsection
