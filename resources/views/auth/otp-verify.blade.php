@extends('layouts.auth', [
    'headline'    => 'Check your Gmail.',
    'subheadline' => 'Enter the 6-digit code we sent to activate your HostelEats account.',
])

@section('content')
<div class="auth-card">
    <div class="auth-card-head">
        <p class="crumb">OTP Verification</p>
        <h2>Enter Verification Code</h2>
        <span>
            We sent a 6-digit code to
            <strong>{{ $otpEmail ?? 'your email' }}</strong>
        </span>
    </div>

    @if (session('success'))
        <p style="color:#16a34a;background:#f0fdf4;padding:.75rem 1rem;border-radius:8px;font-size:.875rem;margin-bottom:.5rem;">
            ✓ {{ session('success') }}
        </p>
    @endif
    @if (session('error'))
        <p class="form-error">{{ session('error') }}</p>
    @endif

    {{-- DEV MODE: show OTP when email is not configured --}}
    @if (session('dev_otp') && config('mail.default') === 'log')
        <div style="background:#fefce8;border:2px dashed #eab308;border-radius:10px;padding:1rem 1.25rem;margin-bottom:1rem;text-align:center;">
            <p style="font-size:.8rem;color:#92400e;font-weight:600;margin:0 0 .35rem;">⚙ Dev Mode — Gmail SMTP not configured</p>
            <p style="font-size:.85rem;color:#78350f;margin:0 0 .5rem;">Your verification code is:</p>
            <p style="font-size:2rem;font-weight:900;letter-spacing:.5rem;color:#b45309;margin:0;">{{ session('dev_otp') }}</p>
        </div>
    @endif

    <form method="POST" action="{{ route('otp.verify') }}" id="otp-form">
        @csrf

        {{-- 6 individual boxes --}}
        <div style="display:flex;gap:.5rem;justify-content:center;margin:1rem 0 1.25rem;">
            @for ($i = 1; $i <= 6; $i++)
                <input
                    type="text"
                    inputmode="numeric"
                    maxlength="1"
                    pattern="[0-9]"
                    class="otp-box"
                    id="otp-box-{{ $i }}"
                    style="width:2.75rem;height:3rem;text-align:center;font-size:1.4rem;font-weight:700;border:2px solid #dfe8e1;border-radius:8px;outline:none;transition:border-color .15s;"
                    autocomplete="off"
                    required
                >
            @endfor
        </div>

        {{-- Hidden combined input --}}
        <input type="hidden" name="otp" id="otp-combined">

        @error('otp')
            <p class="form-error" style="text-align:center;">{{ $message }}</p>
        @enderror

        <p style="font-size:.8rem;color:#9ca3af;text-align:center;margin-bottom:1rem;">
            Code expires in <strong>10 minutes</strong>. Maximum 5 attempts.
        </p>

        <button type="submit" class="primary-action" style="width:100%;">
            Verify Code
        </button>
    </form>

    <form method="POST" action="{{ route('otp.resend') }}" style="margin-top:.75rem;">
        @csrf
        <button type="submit" class="secondary-action" style="width:100%;">
            Resend New Code
        </button>
    </form>

    <p class="auth-link muted-link" style="text-align:center;font-size:.8rem;margin-top:.75rem;">
        Wrong account? <a href="{{ route('login') }}">Back to Login</a>
    </p>
</div>

<script>
(function () {
    const boxes = Array.from({ length: 6 }, (_, i) => document.getElementById('otp-box-' + (i + 1)));
    const combined = document.getElementById('otp-combined');

    boxes.forEach((box, idx) => {
        box.addEventListener('input', function () {
            this.value = this.value.replace(/\D/g, '').slice(0, 1);
            combined.value = boxes.map(b => b.value).join('');
            if (this.value && idx < 5) boxes[idx + 1].focus();
        });
        box.addEventListener('keydown', function (e) {
            if (e.key === 'Backspace' && !this.value && idx > 0) boxes[idx - 1].focus();
        });
        box.addEventListener('paste', function (e) {
            e.preventDefault();
            const text = (e.clipboardData || window.clipboardData).getData('text').replace(/\D/g, '').slice(0, 6);
            text.split('').forEach((ch, i) => { if (boxes[i]) boxes[i].value = ch; });
            combined.value = boxes.map(b => b.value).join('');
            if (boxes[text.length - 1]) boxes[Math.min(text.length, 5)].focus();
        });
        box.addEventListener('focus', function () { this.style.borderColor = '#16a34a'; });
        box.addEventListener('blur',  function () { this.style.borderColor = '#dfe8e1'; });
    });

    document.getElementById('otp-form').addEventListener('submit', function () {
        combined.value = boxes.map(b => b.value).join('');
    });
})();
</script>
@endsection
