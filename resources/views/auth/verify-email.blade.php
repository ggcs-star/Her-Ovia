<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=yes">
    <title>Verify Email | Her Ovia</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
        }
        
        body {
            background: #f8fafc;
            min-height: 100vh;
            display: flex;
        }
        
        .container {
            display: flex;
            width: 100%;
            min-height: 100vh;
        }
        
        /* IMAGE SECTION */
        .image-section {
            flex: 1;
            background: linear-gradient(135deg, rgba(107, 26, 58, 0.85) 0%, rgba(75, 15, 38, 0.9) 100%), 
                url('https://images.pexels.com/photos/1927259/pexels-photo-1927259.jpeg?auto=compress&cs=tinysrgb&w=1600');
            background-size: cover;
            background-position: center;
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 60px;
            color: white;
        }
        
        .image-section h1 {
            font-size: 42px;
            margin-bottom: 20px;
            font-weight: 700;
            line-height: 1.2;
            letter-spacing: -0.02em;
        }
        
        .image-section p {
            font-size: 18px;
            opacity: 0.9;
            line-height: 1.6;
            max-width: 500px;
        }
        
        .features {
            margin-top: 40px;
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 25px;
        }
        
        .feature-item {
            display: flex;
            align-items: center;
            gap: 12px;
        }
        
        .feature-icon {
            background: rgba(255, 255, 255, 0.15);
            width: 40px;
            height: 40px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
        }
        
        .feature-item h4 {
            font-size: 15px;
            font-weight: 600;
            margin-bottom: 4px;
        }
        
        .feature-item p {
            font-size: 13px !important;
            opacity: 0.8;
        }
        
        /* VERIFY SECTION */
        .verify-section {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px;
            background: white;
        }
        
        .verify-container {
            width: 100%;
            max-width: 420px;
        }
        
        .logo {
            font-size: 28px;
            font-weight: 800;
            margin-bottom: 10px;
            text-align: center;
            color: #8B2452;
            letter-spacing: -0.02em;
        }
        
        .verify-header {
            text-align: center;
            margin-bottom: 40px;
        }
        
        .verify-header h2 {
            font-size: 28px;
            color: #1e293b;
            margin-bottom: 8px;
            font-weight: 700;
        }
        
        .verify-header p {
            color: #64748b;
            font-size: 15px;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-label {
            display: block;
            color: #475569;
            font-size: 13px;
            font-weight: 600;
            margin-bottom: 6px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .form-group input {
            width: 100%;
            padding: 14px 20px;
            border: 1.5px solid #e2e8f0;
            border-radius: 12px;
            font-size: 15px;
            transition: all 0.3s;
            background: #ffffff;
        }
        
        .form-group input:focus {
            outline: none;
            border-color: #8B2452;
            background: white;
            box-shadow: 0 0 0 3px rgba(139, 36, 82, 0.1);
        }
        
        .otp-input {
            width: 100%;
            padding: 14px 20px;
            text-align: center;
            font-size: 24px;
            font-weight: 600;
            letter-spacing: 4px;
            border: 1.5px solid #e2e8f0;
            border-radius: 12px;
            background: #ffffff;
            transition: all 0.3s;
        }
        
        .otp-input:focus {
            border-color: #8B2452;
            background: white;
            box-shadow: 0 0 0 3px rgba(139, 36, 82, 0.1);
            outline: none;
        }
        
        .timer {
            text-align: center;
            margin: 15px 0;
            color: #64748b;
            font-size: 14px;
        }
        
        .timer span {
            color: #8B2452;
            font-weight: 600;
        }
        
        .resend-link {
            text-align: center;
            margin-bottom: 20px;
        }
        
        .resend-btn {
            color: #8B2452;
            background: none;
            border: none;
            font-weight: 500;
            cursor: pointer;
            font-size: 14px;
            text-decoration: underline;
        }
        
        .resend-btn:hover {
            color: #6B1A3A;
        }
        
        .resend-btn.disabled {
            color: #94a3b8;
            cursor: not-allowed;
            text-decoration: none;
        }
        
        .submit-btn {
            width: 100%;
            padding: 14px;
            background: #8B2452;
            color: white;
            border: none;
            border-radius: 12px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            margin-top: 10px;
        }
        
        .submit-btn:hover {
            background: #6B1A3A;
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(107, 26, 58, 0.2);
        }
        
        .login-section {
            text-align: center;
            margin-top: 30px;
            padding-top: 25px;
            border-top: 1px solid #e2e8f0;
        }
        
        .login-text {
            color: #64748b;
            font-size: 15px;
            margin-bottom: 15px;
        }
        
        .login-btn {
            display: inline-block;
            padding: 12px 32px;
            background: white;
            border: 2px solid #8B2452;
            border-radius: 12px;
            color: #8B2452;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s;
        }
        
        .login-btn:hover {
            background: #8B2452;
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(139, 36, 82, 0.2);
        }
        
        .back-section {
            margin-bottom: 30px;
        }
        
        .back-link {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            color: #64748b;
            text-decoration: none;
            font-weight: 500;
            transition: color 0.3s;
        }
        
        .back-link:hover {
            color: #8B2452;
        }
        
        .error-container {
            background: #fef2f2;
            border: 1px solid #fee2e2;
            border-radius: 12px;
            padding: 16px;
            margin-bottom: 24px;
        }
        
        .error-list {
            list-style: none;
        }
        
        .error-list li {
            color: #dc2626;
            font-size: 14px;
            padding: 4px 0;
            display: flex;
            align-items: center;
        }
        
        .success-message {
            background: #ecfdf5;
            border: 1px solid #a7f3d0;
            color: #065f46;
            padding: 14px;
            border-radius: 12px;
            margin-bottom: 20px;
            font-size: 14px;
        }
        
        /* ========== RESPONSIVE DESIGN ========== */
        
        /* Tablet */
        @media (max-width: 1024px) {
            .image-section h1 {
                font-size: 32px;
            }
            
            .image-section p {
                font-size: 16px;
            }
            
            .features {
                gap: 20px;
            }
            
            .feature-item h4 {
                font-size: 14px;
            }
            
            .feature-item p {
                font-size: 12px !important;
            }
        }
        
        /* Mobile - Image section TOP, Form BOTTOM */
        @media (max-width: 768px) {
            .container {
                flex-direction: column;
            }
            
            .image-section {
                flex: none;
                padding: 40px 30px;
                text-align: center;
            }
            
            .image-section h1 {
                font-size: 28px;
                text-align: center;
            }
            
            .image-section p {
                font-size: 14px;
                text-align: center;
                max-width: 100%;
            }
            
            .features {
                grid-template-columns: repeat(2, 1fr);
                gap: 15px;
                margin-top: 30px;
            }
            
            .feature-item {
                justify-content: center;
            }
            
            .feature-item h4 {
                font-size: 13px;
            }
            
            .feature-item p {
                font-size: 11px !important;
            }
            
            .feature-icon {
                width: 35px;
                height: 35px;
                font-size: 16px;
            }
            
            .verify-section {
                flex: none;
                padding: 30px 20px;
            }
            
            .verify-container {
                max-width: 100%;
            }
            
            .logo {
                font-size: 24px;
            }
            
            .verify-header h2 {
                font-size: 24px;
            }
            
            .verify-header p {
                font-size: 13px;
            }
            
            .verify-header {
                margin-bottom: 30px;
            }
            
            .form-group input {
                padding: 12px 16px;
                font-size: 14px;
            }
            
            .otp-input {
                padding: 12px 16px;
                font-size: 20px;
                letter-spacing: 2px;
            }
            
            .submit-btn {
                padding: 12px;
                font-size: 15px;
            }
            
            .login-btn {
                padding: 10px 24px;
                font-size: 14px;
            }
            
            .login-text {
                font-size: 13px;
            }
            
            .back-link {
                font-size: 13px;
            }
            
            .timer {
                font-size: 13px;
            }
            
            .resend-btn {
                font-size: 13px;
            }
        }
        
        /* Small Mobile (below 480px) */
        @media (max-width: 480px) {
            .image-section {
                padding: 30px 20px;
            }
            
            .image-section h1 {
                font-size: 24px;
            }
            
            .image-section p {
                font-size: 13px;
            }
            
            .features {
                gap: 12px;
                margin-top: 25px;
            }
            
            .feature-icon {
                width: 30px;
                height: 30px;
                font-size: 14px;
            }
            
            .feature-item h4 {
                font-size: 12px;
            }
            
            .feature-item p {
                font-size: 10px !important;
            }
            
            .verify-section {
                padding: 25px 16px;
            }
            
            .logo {
                font-size: 22px;
            }
            
            .verify-header h2 {
                font-size: 22px;
            }
            
            .verify-header {
                margin-bottom: 25px;
            }
            
            .form-group {
                margin-bottom: 16px;
            }
            
            .form-group input {
                padding: 10px 14px;
                font-size: 14px;
            }
            
            .otp-input {
                padding: 10px 14px;
                font-size: 18px;
                letter-spacing: 1px;
            }
            
            .form-label {
                font-size: 11px;
                margin-bottom: 4px;
            }
            
            .submit-btn {
                padding: 10px;
                font-size: 14px;
                margin-top: 8px;
            }
            
            .login-section {
                margin-top: 24px;
                padding-top: 20px;
            }
            
            .login-btn {
                padding: 10px 20px;
                font-size: 13px;
            }
            
            .login-text {
                font-size: 13px;
                margin-bottom: 12px;
            }
            
            .back-section {
                margin-bottom: 20px;
            }
            
            .timer {
                font-size: 12px;
                margin: 10px 0;
            }
            
            .resend-btn {
                font-size: 12px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="image-section">
            <h1>Verify Your Email</h1>
            <p>Enter the 6-digit OTP sent to your email address to complete the verification process.</p>
            
            <div class="features">
                <div class="feature-item">
                    <div class="feature-icon">✉️</div>
                    <div>
                        <h4>Email Sent</h4>
                        <p>Check your inbox for OTP</p>
                    </div>
                </div>
                <div class="feature-item">
                    <div class="feature-icon">⏱️</div>
                    <div>
                        <h4>Time Limited</h4>
                        <p>OTP expires in 10 minutes</p>
                    </div>
                </div>
                <div class="feature-item">
                    <div class="feature-icon">✅</div>
                    <div>
                        <h4>Secure Access</h4>
                        <p>One-time use only</p>
                    </div>
                </div>
                <div class="feature-item">
                    <div class="feature-icon">🔄</div>
                    <div>
                        <h4>Can't find it?</h4>
                        <p>Resend OTP available</p>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="verify-section">
            <div class="verify-container">
                <div class="logo">Her Ovia</div>
                
                <div class="back-section">
                    <a href="/register" class="back-link">
                        ← Back to Registration
                    </a>
                </div>
                
                <div class="verify-header">
                    <h2>Email Verification</h2>
                    <p>Enter the OTP sent to your email address</p>
                </div>
                
                <form method="POST" action="/verify-email">
                    @csrf
                    
                    @if(session('success'))
                        <div class="success-message">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="error-container">
                            <ul class="error-list">
                                <li>{{ session('error') }}</li>
                            </ul>
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="error-container">
                            <ul class="error-list">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    
                    <input type="hidden" name="email" value="{{ $email }}">
                    
                    <div class="form-group">
                        <label class="form-label">OTP Code</label>
                        <input type="text" name="otp" maxlength="6" class="otp-input" placeholder="000000" required>
                    </div>
                    
                    <div class="timer">
                        Time remaining: <span id="countdown">10:00</span>
                    </div>
                    
                    <div class="resend-link">
                        <button type="button" class="resend-btn" id="resendBtn" disabled>
                            Resend OTP (60s)
                        </button>
                    </div>
                    
                    <button type="submit" class="submit-btn">Verify Email</button>
                </form>
                
                <div class="login-section">
                    <div class="login-text">Already verified your email?</div>
                    <a href="/login" class="login-btn">Sign In Now</a>
                </div>
            </div>
        </div>
    </div>

    <script>
        let totalSeconds = 600;
        let resendTimer = 60;
        const countdownEl = document.getElementById('countdown');
        const resendBtn = document.getElementById('resendBtn');
        
        function updateTimer() {
            const minutes = Math.floor(totalSeconds / 60);
            const seconds = totalSeconds % 60;
            countdownEl.textContent = `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
            
            if (totalSeconds > 0) {
                totalSeconds--;
                setTimeout(updateTimer, 1000);
            } else {
                countdownEl.textContent = "00:00";
                countdownEl.style.color = "#dc2626";
            }
        }
        
        function updateResendTimer() {
            if (resendTimer > 0) {
                resendBtn.textContent = `Resend OTP (${resendTimer}s)`;
                resendTimer--;
                setTimeout(updateResendTimer, 1000);
            } else {
                resendBtn.textContent = "Resend OTP";
                resendBtn.classList.remove('disabled');
                resendBtn.disabled = false;
            }
        }
        
        resendBtn.addEventListener('click', function() {
            if (!this.disabled) {
                alert('OTP has been resent to your email!');
                this.classList.add('disabled');
                this.disabled = true;
                resendTimer = 60;
                updateResendTimer();
            }
        });
        
        document.querySelector('.otp-input').focus();
        
        updateTimer();
        updateResendTimer();
        
        const otpInput = document.querySelector('.otp-input');
        otpInput.addEventListener('input', function(e) {
            if (this.value.length === this.maxLength) {
                document.querySelector('.submit-btn').focus();
            }
        });
    </script>
</body>
</html>