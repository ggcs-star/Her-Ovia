<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=yes">
        <title>Reset Password | Her Ovia</title>
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
        
        /* RESET SECTION */
        .reset-section {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px;
            background: white;
        }
        
        .reset-container {
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
        
        .reset-header {
            text-align: center;
            margin-bottom: 40px;
        }
        
        .reset-header h2 {
            font-size: 28px;
            color: #1e293b;
            margin-bottom: 8px;
            font-weight: 700;
        }
        
        .reset-header p {
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
            font-size: 20px;
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
            
            .reset-section {
                flex: none;
                padding: 30px 20px;
            }
            
            .reset-container {
                max-width: 100%;
            }
            
            .logo {
                font-size: 24px;
            }
            
            .reset-header h2 {
                font-size: 24px;
            }
            
            .reset-header p {
                font-size: 13px;
            }
            
            .reset-header {
                margin-bottom: 30px;
            }
            
            .form-group input {
                padding: 12px 16px;
                font-size: 14px;
            }
            
            .otp-input {
                padding: 12px 16px;
                font-size: 18px;
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
            
            .reset-section {
                padding: 25px 16px;
            }
            
            .logo {
                font-size: 22px;
            }
            
            .reset-header h2 {
                font-size: 22px;
            }
            
            .reset-header {
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
                font-size: 16px;
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
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="image-section">
            <h1>Set New Password</h1>
            <p>Enter the OTP sent to your email and create a strong new password for your account.</p>
            
            <div class="features">
                <div class="feature-item">
                    <div class="feature-icon">🔐</div>
                    <div>
                        <h4>Strong Security</h4>
                        <p>Password encryption enabled</p>
                    </div>
                </div>
                <div class="feature-item">
                    <div class="feature-icon">⏱️</div>
                    <div>
                        <h4>OTP Expires</h4>
                        <p>Valid for 10 minutes only</p>
                    </div>
                </div>
                <div class="feature-item">
                    <div class="feature-icon">✅</div>
                    <div>
                        <h4>Instant Update</h4>
                        <p>Password updates immediately</p>
                    </div>
                </div>
                <div class="feature-item">
                    <div class="feature-icon">🔍</div>
                    <div>
                        <h4>Strength Check</h4>
                        <p>Real-time password strength</p>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="reset-section">
            <div class="reset-container">
                <div class="logo">Her Ovia</div>
                
                <div class="back-section">
                    <a href="/forgot-password" class="back-link">
                        ← Back to Forgot Password
                    </a>
                </div>
                
                <div class="reset-header">
                    <h2>Reset Password</h2>
                    <p>Enter OTP and set your new password</p>
                </div>
                
                <form method="POST" action="/reset-password">
                    @csrf
                    
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
                    
                    <input type="hidden" name="email" value="{{ session('reset_email') }}">
                    
                    <div class="form-group">
                        <label class="form-label">OTP Code</label>
                        <input type="text" name="otp" maxlength="6" class="otp-input" placeholder="Enter 6-digit OTP" required>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">New Password</label>
                        <input type="password" name="password" placeholder="Enter new password" required>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Confirm New Password</label>
                        <input type="password" name="password_confirmation" placeholder="Confirm new password" required>
                    </div>
                    
                    <button type="submit" class="submit-btn">Reset Password</button>
                </form>
                
                <div class="login-section">
                    <div class="login-text">Remember your password?</div>
                    <a href="/login" class="login-btn">Sign In Instead</a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>