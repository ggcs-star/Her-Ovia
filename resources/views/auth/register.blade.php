<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=yes">
    <title>Register | Her-Ovia</title>
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
        
        .benefits {
            margin-top: 40px;
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 25px;
        }
        
        .benefit-item {
            display: flex;
            align-items: center;
            gap: 12px;
        }
        
        .benefit-icon {
            background: rgba(255, 255, 255, 0.15);
            width: 40px;
            height: 40px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
        }
        
        .benefit-item h4 {
            font-size: 15px;
            font-weight: 600;
            margin-bottom: 4px;
        }
        
        .benefit-item p {
            font-size: 13px !important;
            opacity: 0.8;
        }
        
        /* REGISTER SECTION */
        .register-section {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px;
            background: white;
        }
        
        .register-container {
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
        
        .register-header {
            text-align: center;
            margin-bottom: 40px;
        }
        
        .register-header h2 {
            font-size: 28px;
            color: #1e293b;
            margin-bottom: 8px;
            font-weight: 700;
        }
        
        .register-header p {
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
        
        .password-wrapper {
            position: relative;
        }
        
        .password-toggle {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            cursor: pointer;
            padding: 0;
            width: 24px;
            height: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .eye-icon {
            fill: #64748b;
            transition: fill 0.3s;
            width: 20px;
            height: 20px;
        }
        
        .password-toggle:hover .eye-icon {
            fill: #8B2452;
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
            
            .benefits {
                gap: 20px;
            }
            
            .benefit-item h4 {
                font-size: 14px;
            }
            
            .benefit-item p {
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
            
            .benefits {
                grid-template-columns: repeat(2, 1fr);
                gap: 15px;
                margin-top: 30px;
            }
            
            .benefit-item {
                justify-content: center;
            }
            
            .benefit-item h4 {
                font-size: 13px;
            }
            
            .benefit-item p {
                font-size: 11px !important;
            }
            
            .benefit-icon {
                width: 35px;
                height: 35px;
                font-size: 16px;
            }
            
            .register-section {
                flex: none;
                padding: 30px 20px;
            }
            
            .register-container {
                max-width: 100%;
            }
            
            .logo {
                font-size: 24px;
            }
            
            .register-header h2 {
                font-size: 24px;
            }
            
            .register-header p {
                font-size: 13px;
            }
            
            .register-header {
                margin-bottom: 30px;
            }
            
            .form-group input {
                padding: 12px 16px;
                font-size: 14px;
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
            
            .benefits {
                gap: 12px;
                margin-top: 25px;
            }
            
            .benefit-icon {
                width: 30px;
                height: 30px;
                font-size: 14px;
            }
            
            .benefit-item h4 {
                font-size: 12px;
            }
            
            .benefit-item p {
                font-size: 10px !important;
            }
            
            .register-section {
                padding: 25px 16px;
            }
            
            .logo {
                font-size: 22px;
            }
            
            .register-header h2 {
                font-size: 22px;
            }
            
            .register-header {
                margin-bottom: 25px;
            }
            
            .form-group {
                margin-bottom: 16px;
            }
            
            .form-group input {
                padding: 10px 14px;
                font-size: 14px;
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
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="image-section">
            <h1>Join Her-Ovia Today</h1>
            <p>Create your account and unlock exclusive benefits, personalized recommendations, and faster checkout experience.</p>
            
            <div class="benefits">
                <div class="benefit-item">
                    <div class="benefit-icon">🎁</div>
                    <div>
                        <h4>Welcome Bonus</h4>
                        <p>Get 20% off on first order</p>
                    </div>
                </div>
                <div class="benefit-item">
                    <div class="benefit-icon">⭐</div>
                    <div>
                        <h4>Exclusive Deals</h4>
                        <p>Member-only discounts</p>
                    </div>
                </div>
                <div class="benefit-item">
                    <div class="benefit-icon">📱</div>
                    <div>
                        <h4>Wishlist</h4>
                        <p>Save items for later</p>
                    </div>
                </div>
                <div class="benefit-item">
                    <div class="benefit-icon">⚡</div>
                    <div>
                        <h4>Fast Checkout</h4>
                        <p>Save shipping details</p>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="register-section">
            <div class="register-container">
                <div class="logo">Her Ovia</div>
                <div class="register-header">
                    <h2>Create Account</h2>
                    <p>Join our community of happy shoppers</p>
                </div>
                
                <form method="POST" action="/register">
                    @csrf
                    
                    @if(session('success'))
                        <div class="success-message">
                            {{ session('success') }}
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

                    <div class="form-group">
                        <label class="form-label">Full Name</label>
                        <input type="text" name="name" placeholder="Enter your full name" required>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Email Address</label>
                        <input type="email" name="email" placeholder="Enter your email address" required>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Password</label>
                        <div class="password-wrapper">
                            <input type="password" name="password" id="password" placeholder="Create a password" required>
                            <button type="button" class="password-toggle" onclick="togglePassword('password')">
                                <svg class="eye-icon" viewBox="0 0 24 24" width="20" height="20">
                                    <path d="M12 4.5C7 4.5 2.73 7.61 1 12c1.73 4.39 6 7.5 11 7.5s9.27-3.11 11-7.5c-1.73-4.39-6-7.5-11-7.5zM12 17c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5zm0-8c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3z"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Confirm Password</label>
                        <div class="password-wrapper">
                            <input type="password" name="password_confirmation" id="confirmPassword" placeholder="Confirm your password" required>
                            <button type="button" class="password-toggle" onclick="togglePassword('confirmPassword')">
                                <svg class="eye-icon" viewBox="0 0 24 24" width="20" height="20">
                                    <path d="M12 4.5C7 4.5 2.73 7.61 1 12c1.73 4.39 6 7.5 11 7.5s9.27-3.11 11-7.5c-1.73-4.39-6-7.5-11-7.5zM12 17c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5zm0-8c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3z"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                    
                    <button type="submit" class="submit-btn">Create Account</button>
                </form>
                
                <div class="login-section">
                    <div class="login-text">Already have an account?</div>
                    <a href="/login" class="login-btn">Sign In Here</a>
                </div>
            </div>
        </div>
    </div>

    <script>
    function togglePassword(fieldId) {
        const passwordInput = document.getElementById(fieldId);
        const eyeIcon = passwordInput.nextElementSibling.querySelector('.eye-icon');
        
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            eyeIcon.innerHTML = `<path d="M12 4.5C7 4.5 2.73 7.61 1 12c1.73 4.39 6 7.5 11 7.5s9.27-3.11 11-7.5c-1.73-4.39-6-7.5-11-7.5zM12 17c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5zm0-8c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3z" style="opacity:0.5"/><path d="M2 2l20 20M9.88 9.88a3 3 0 1 0 4.24 4.24M17.66 17.66A10.71 10.71 0 0 1 12 20.5c-5 0-9.27-3.11-11-7.5a10.84 10.84 0 0 1 3.16-4.84"/>`;
        } else {
            passwordInput.type = 'password';
            eyeIcon.innerHTML = `<path d="M12 4.5C7 4.5 2.73 7.61 1 12c1.73 4.39 6 7.5 11 7.5s9.27-3.11 11-7.5c-1.73-4.39-6-7.5-11-7.5zM12 17c-2.76 0-5-2.24-5-5s2.24-5 5-5 5 2.24 5 5-2.24 5-5 5zm0-8c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3z"/>`;
        }
    }
    </script>
</body>
</html>