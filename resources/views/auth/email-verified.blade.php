@extends('layouts.auth', [
    'headline'    => 'Email verified!',
    'subheadline' => 'Your HostelEats account is now active. Start ordering from your favourite campus branches.',
])

@section('content')
    <div class="auth-card">
        <div class="auth-card-head">
            <p class="crumb">Verification Complete</p>
            <h2>Welcome to HostelEats! 🎉</h2>
        </div>

        <div style="background:#f0fdf4;border:1px solid #bbf7d0;border-radius:12px;padding:1.5rem;text-align:center;margin:.5rem 0 1.5rem;">
            <div style="font-size:3rem;margin-bottom:.5rem;">✅</div>
            <p style="font-size:1rem;font-weight:700;color:#166534;margin:0 0 .35rem;">
                Email Verified Successfully
            </p>
            <p style="font-size:.875rem;color:#166534;margin:0;">
                Your account is now active. You can login and start ordering food.
            </p>
        </div>

        <a href="{{ route('login') }}" class="primary-action" style="display:block;text-align:center;width:100%;box-sizing:border-box;">
            Login to HostelEats
        </a>

        <p class="auth-link muted-link" style="margin-top:1rem;text-align:center;font-size:.85rem;">
            You can now browse branches, add food to cart, and place orders.
        </p>
    </div>
@endsection
