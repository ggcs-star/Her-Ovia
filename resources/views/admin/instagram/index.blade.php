@extends('layouts.admin')

@section('title', 'Instagram Integration')

@section('content')
<div class="container-fluid px-3 px-lg-4">
    <!-- Session Messages -->
    @if(session('success'))
        <div class="alert-custom success">
            <i class="bi bi-check-circle-fill"></i>
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert-custom error">
            <i class="bi bi-exclamation-circle-fill"></i>
            {{ session('error') }}
        </div>
    @endif

    @php
        $account = \App\Models\SocialAccount::where('platform','instagram')->first();
        $isConnected = $account && $account->is_active;
        $hasExpiry = $account && $account->expires_at;
        $hasUsername = $account && $account->instagram_username;
        $hasBusinessId = $account && $account->instagram_business_id;
    @endphp

    <!-- Breadcrumb -->
    <div class="breadcrumb-wrapper">
        <span class="breadcrumb-item">PAGES</span>
        <i class="bi bi-chevron-right"></i>
        <span class="breadcrumb-item active">Control Panel</span>
    </div>

    <!-- Connection Card -->
    <div class="connection-card">
        <div class="row align-items-center">
            <div class="col-lg-7">
                <div class="d-flex align-items-center gap-4">
                    <div class="instagram-logo-wrapper">
                        <img src="https://upload.wikimedia.org/wikipedia/commons/a/a5/Instagram_icon.png" alt="Instagram" class="instagram-logo">
                    </div>
                    <div>
                        <h2 class="connection-title">Instagram Integration</h2>
                        <div class="status-wrapper">
                            <span class="status-badge {{ $isConnected ? 'connected' : 'disconnected' }}">
                                <i class="bi bi-circle-fill"></i>
                                {{ $isConnected ? 'Connected' : 'Disconnected' }}
                            </span>
                            @if($account && $account->expires_at)
                                <span class="token-info">
                                    <i class="bi bi-clock-history"></i>
                                    Token valid until {{ $account->expires_at->format('d M Y H:i') }}
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-5">
                <div class="connection-actions">
                    @if($account)
                        <a href="{{ route('facebook.connect') }}" class="btn btn-primary btn-connect">
                            <i class="bi bi-arrow-repeat"></i> Reconnect
                        </a>
                        <form method="POST" action="{{ route('instagram.disconnect') }}" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-outline-danger btn-disconnect" onclick="return confirm('Disconnect current Instagram account?')">
                                <i class="bi bi-unlink"></i> Disconnect
                            </button>
                        </form>
                    @else
                        <a href="{{ route('facebook.connect') }}" class="btn btn-primary btn-connect">
                            <i class="bi bi-instagram"></i> Connect Instagram
                        </a>
                    @endif
                </div>
            </div>
        </div>

        @if($account)
        <div class="row mt-4 pt-3 border-top border-light">
            <div class="col-md-4">
                <div class="info-item">
                    <label><i class="bi bi-person"></i> Username</label>
                    <p>{{ $account->instagram_username ?? 'Not set' }}</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="info-item">
                    <label><i class="bi bi-id-badge"></i> Business ID</label>
                    <p>{{ $account->instagram_business_id ?? 'Not set' }}</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="info-item">
                    <label><i class="bi bi-calendar-check"></i> Status</label>
                    <p><span class="status-dot {{ $isConnected ? 'active' : 'inactive' }}"></span> {{ $isConnected ? 'Active' : 'Inactive' }}</p>
                </div>
            </div>
        </div>
        @endif
    </div>

    <!-- How to Connect Section -->
    <div class="how-to-section">
        <div class="section-header">
            <h2 class="section-title">How to Connect Instagram Business Account</h2>
            <p class="section-subtitle">Follow these simple steps to connect your Instagram Business Account with Her Ovia Admin Panel.</p>
        </div>

        <!-- Step Cards -->
        <div class="row g-3">
            <!-- Step 01 -->
            <div class="col-lg-3 col-md-6">
                <div class="step-card">
                    <div class="step-number">01</div>
                    <div class="step-icon">
                        <i class="bi bi-facebook"></i>
                    </div>
                    <h5 class="step-title">Facebook Login</h5>
                    <p class="step-desc">Login to Facebook Developer Console</p>
                    <div class="step-meta">
                        <span><i class="bi bi-clock"></i> 2 mins</span>
                    </div>
                </div>
            </div>

            <!-- Step 02 -->
            <div class="col-lg-3 col-md-6">
                <div class="step-card">
                    <div class="step-number">02</div>
                    <div class="step-icon">
                        <i class="bi bi-key"></i>
                    </div>
                    <h5 class="step-title">Get Short Token</h5>
                    <p class="step-desc">Authorization → short-lived token</p>
                    <div class="step-meta">
                        <span><i class="bi bi-clock"></i> 1 min</span>
                    </div>
                </div>
            </div>

            <!-- Step 03 -->
            <div class="col-lg-3 col-md-6">
                <div class="step-card">
                    <div class="step-number">03</div>
                    <div class="step-icon">
                        <i class="bi bi-arrow-repeat"></i>
                    </div>
                    <h5 class="step-title">Get Long Token</h5>
                    <p class="step-desc">short-lived → long-lived token</p>
                    <div class="step-meta">
                        <span><i class="bi bi-clock"></i> 2 mins</span>
                    </div>
                </div>
            </div>

            <!-- Step 04 -->
            <div class="col-lg-3 col-md-6">
                <div class="step-card">
                    <div class="step-number">04</div>
                    <div class="step-icon">
                        <i class="bi bi-file-earmark-text"></i>
                    </div>
                    <h5 class="step-title">Fetch Pages</h5>
                    <p class="step-desc">Get Facebook Pages list</p>
                    <div class="step-meta">
                        <span><i class="bi bi-clock"></i> 1 min</span>
                    </div>
                </div>
            </div>

            <!-- Step 05 -->
            <div class="col-lg-3 col-md-6">
                <div class="step-card">
                    <div class="step-number">05</div>
                    <div class="step-icon">
                        <i class="bi bi-building"></i>
                    </div>
                    <h5 class="step-title">Get Business ID</h5>
                    <p class="step-desc">Retrieve Instagram Business ID</p>
                    <div class="step-meta">
                        <span><i class="bi bi-clock"></i> 2 mins</span>
                    </div>
                </div>
            </div>

            <!-- Step 06 -->
            <div class="col-lg-3 col-md-6">
                <div class="step-card">
                    <div class="step-number">06</div>
                    <div class="step-icon">
                        <i class="bi bi-person"></i>
                    </div>
                    <h5 class="step-title">Get Profile</h5>
                    <p class="step-desc">Fetch profile information</p>
                    <div class="step-meta">
                        <span><i class="bi bi-clock"></i> 1 min</span>
                    </div>
                </div>
            </div>

            <!-- Step 07 -->
            <div class="col-lg-3 col-md-6">
                <div class="step-card">
                    <div class="step-number">07</div>
                    <div class="step-icon">
                        <i class="bi bi-save"></i>
                    </div>
                    <h5 class="step-title">Save Account</h5>
                    <p class="step-desc">Account saved successfully</p>
                    <div class="step-meta">
                        <span><i class="bi bi-clock"></i> 1 min</span>
                    </div>
                </div>
            </div>

            <!-- Step 08 -->
            <div class="col-lg-3 col-md-6">
                <div class="step-card">
                    <div class="step-number">08</div>
                    <div class="step-icon">
                        <i class="bi bi-cloud-upload"></i>
                    </div>
                    <h5 class="step-title">Auto Sync</h5>
                    <p class="step-desc">Scheduled: instagram:sync-posts</p>
                    <div class="step-meta">
                        <span><i class="bi bi-clock"></i> Auto</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bottom Cards -->
        <div class="row g-3 mt-2">
            <!-- Requirements -->
            <div class="col-lg-3 col-md-6">
                <div class="feature-card">
                    <div class="feature-header">
                        <div class="feature-icon"><i class="bi bi-check-circle-fill"></i></div>
                        <h6 class="feature-title">Requirements</h6>
                    </div>
                    <ul class="feature-list">
                        <li><i class="bi bi-check"></i> Instagram Business Account</li>
                        <li><i class="bi bi-check"></i> Facebook Page Connected</li>
                        <li><i class="bi bi-check"></i> Admin Access on Page</li>
                        <li><i class="bi bi-check"></i> Meta App Configured</li>
                        <li><i class="bi bi-check"></i> Stable Internet</li>
                    </ul>
                </div>
            </div>

            <!-- Permissions -->
            <div class="col-lg-3 col-md-6">
                <div class="feature-card">
                    <div class="feature-header">
                        <div class="feature-icon"><i class="bi bi-shield-lock"></i></div>
                        <h6 class="feature-title">Permissions Used</h6>
                    </div>
                    <div class="permissions-list">
                        <span class="permission-pill">
                            <i class="bi bi-shield-check"></i> instagram_basic
                        </span>
                        <span class="permission-pill">
                            <i class="bi bi-shield-check"></i> pages_show_list
                        </span>
                        <span class="permission-pill">
                            <i class="bi bi-shield-check"></i> business_management
                        </span>
                    </div>
                </div>
            </div>

            <!-- Automatic Features -->
            <div class="col-lg-3 col-md-6">
                <div class="feature-card">
                    <div class="feature-header">
                        <div class="feature-icon"><i class="bi bi-gear"></i></div>
                        <h6 class="feature-title">Automatic Features</h6>
                    </div>
                    <ul class="feature-list">
                        <li><i class="bi bi-check"></i> Token Auto Refresh</li>
                        <li><i class="bi bi-check"></i> Automatic Post Sync</li>
                        <li><i class="bi bi-check"></i> Secure OAuth 2.0</li>
                        <li><i class="bi bi-check"></i> Fast API Calls</li>
                    </ul>
                </div>
            </div>

            <!-- Need Help -->
            <div class="col-lg-3 col-md-6">
                <div class="feature-card help-card">
                    <div class="feature-header">
                        <div class="feature-icon"><i class="bi bi-headset"></i></div>
                        <h6 class="feature-title">Need Help?</h6>
                    </div>
                    <p class="help-text">If you face any issue while connecting Instagram, please disconnect and reconnect. If the problem persists, contact the system administrator.</p>
                    <a href="#" class="btn btn-primary btn-sm w-100">
                        <i class="bi bi-envelope"></i> Contact Administrator
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* Base */
    .container-fluid {
        font-family: 'Poppins', -apple-system, BlinkMacSystemFont, sans-serif;
        background: #FCF7F9;
        min-height: 100vh;
        padding-top: 1.5rem;
        padding-bottom: 2rem;
    }

    /* Breadcrumb */
    .breadcrumb-wrapper {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        margin-bottom: 1.5rem;
        font-size: 0.8rem;
    }

    .breadcrumb-item {
        color: #6E6E6E;
        font-weight: 500;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        font-size: 0.7rem;
    }

    .breadcrumb-item.active {
        color: #8B2452;
        font-weight: 600;
    }

    .breadcrumb-wrapper i {
        color: #F2DDE7;
        font-size: 0.6rem;
    }

    /* Alerts */
    .alert-custom {
        border-radius: 12px;
        padding: 0.7rem 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
        font-size: 0.9rem;
        margin-bottom: 1.5rem;
        width: 100%;
    }

    .alert-custom.success {
        background: #e8f5ed;
        color: #1a7a4a;
        border: 1px solid #c8e6d9;
    }
    .alert-custom.success i { color: #2EAF68; }

    .alert-custom.error {
        background: #fde8e8;
        color: #b23b3b;
        border: 1px solid #f5d0d0;
    }
    .alert-custom.error i { color: #E74C3C; }

    /* Connection Card */
    .connection-card {
        background: #FFFFFF;
        border-radius: 16px;
        padding: 1.75rem 2rem;
        box-shadow: 0 2px 12px rgba(139, 36, 82, 0.06);
        border: 1px solid #F2DDE7;
        margin-bottom: 2rem;
        transition: all 0.3s ease;
    }

    .connection-card:hover {
        box-shadow: 0 8px 30px rgba(139, 36, 82, 0.08);
    }

    .instagram-logo-wrapper {
        flex-shrink: 0;
    }

    .instagram-logo {
        width: 56px;
        height: 56px;
        object-fit: contain;
        display: block;
    }

    .connection-title {
        font-size: 1.5rem;
        font-weight: 700;
        color: #2C2C2C;
        margin: 0;
    }

    .status-wrapper {
        display: flex;
        align-items: center;
        gap: 1rem;
        flex-wrap: wrap;
        margin-top: 0.2rem;
    }

    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        font-weight: 600;
        font-size: 0.8rem;
        padding: 0.25rem 1rem;
        border-radius: 50px;
        background: #FCF7F9;
        border: 1px solid #F2DDE7;
    }

    .status-badge i { font-size: 0.5rem; }
    .status-badge.connected i { color: #2EAF68; }
    .status-badge.disconnected i { color: #E74C3C; }

    .token-info {
        font-size: 0.75rem;
        color: #6E6E6E;
        display: inline-flex;
        align-items: center;
        gap: 0.3rem;
    }

    .connection-actions {
        display: flex;
        gap: 0.75rem;
        justify-content: flex-end;
        flex-wrap: wrap;
    }

    .btn {
        font-family: 'Poppins', sans-serif;
        padding: 0.5rem 1.5rem;
        border-radius: 12px;
        font-weight: 500;
        font-size: 0.85rem;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        text-decoration: none;
        cursor: pointer;
        border: 1px solid transparent;
    }

    .btn-primary {
        background: #8B2452;
        border-color: #8B2452;
        color: #FFFFFF;
    }
    .btn-primary:hover {
        background: #741D45;
        border-color: #741D45;
        color: #FFFFFF;
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(139, 36, 82, 0.25);
    }

    .btn-outline-danger {
        background: transparent;
        border-color: #E74C3C;
        color: #E74C3C;
    }
    .btn-outline-danger:hover {
        background: #E74C3C;
        color: #FFFFFF;
        transform: translateY(-2px);
    }

    .btn-sm { padding: 0.35rem 1rem; font-size: 0.8rem; }

    .info-item { margin-bottom: 0.25rem; }
    .info-item label {
        font-size: 0.7rem;
        font-weight: 600;
        color: #6E6E6E;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        display: block;
        margin-bottom: 0.1rem;
    }
    .info-item label i { margin-right: 0.3rem; color: #8B2452; }
    .info-item p {
        font-size: 0.9rem;
        font-weight: 500;
        color: #2C2C2C;
        margin: 0;
    }

    .status-dot {
        display: inline-block;
        width: 8px;
        height: 8px;
        border-radius: 50%;
        margin-right: 0.4rem;
    }
    .status-dot.active { background: #2EAF68; }
    .status-dot.inactive { background: #E74C3C; }

    .border-light { border-color: #F2DDE7 !important; }

    /* How to Connect */
    .how-to-section { margin-top: 0.5rem; }

    .section-header {
        margin-bottom: 1.75rem;
    }

    .section-title {
        font-size: 1.6rem;
        font-weight: 700;
        color: #2C2C2C;
        margin-bottom: 0.3rem;
    }

    .section-subtitle {
        font-size: 0.95rem;
        color: #6E6E6E;
        margin: 0;
    }

    /* Step Cards */
    .step-card {
        background: #FFFFFF;
        border-radius: 14px;
        padding: 1.25rem 1rem;
        text-align: center;
        border: 1px solid #F2DDE7;
        box-shadow: 0 2px 8px rgba(139, 36, 82, 0.04);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        height: 100%;
        position: relative;
    }

    .step-card:hover {
        transform: translateY(-4px);
        border-color: #8B2452;
        box-shadow: 0 12px 40px rgba(139, 36, 82, 0.08);
    }

    .step-number {
        font-size: 0.7rem;
        font-weight: 700;
        color: #8B2452;
        letter-spacing: 0.05em;
        margin-bottom: 0.4rem;
    }

    .step-icon {
        width: 48px;
        height: 48px;
        margin: 0 auto 0.5rem;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #FCF7F9;
        border-radius: 12px;
        border: 1px solid #F2DDE7;
        transition: all 0.3s ease;
    }

    .step-card:hover .step-icon {
        background: #8B2452;
        border-color: #8B2452;
        transform: scale(1.05);
    }

    .step-icon i {
        font-size: 1.3rem;
        color: #8B2452;
        transition: all 0.3s ease;
    }

    .step-card:hover .step-icon i { color: #FFFFFF; }

    .step-title {
        font-size: 0.85rem;
        font-weight: 700;
        color: #2C2C2C;
        margin-bottom: 0.2rem;
    }

    .step-desc {
        font-size: 0.75rem;
        color: #6E6E6E;
        margin-bottom: 0.5rem;
        line-height: 1.3;
    }

    .step-meta {
        font-size: 0.65rem;
        color: #6E6E6E;
        padding-top: 0.4rem;
        border-top: 1px solid #F2DDE7;
    }

    .step-meta i { color: #8B2452; margin-right: 0.2rem; }

    /* Feature Cards */
    .feature-card {
        background: #FFFFFF;
        border-radius: 14px;
        padding: 1.25rem 1.25rem 1.5rem;
        border: 1px solid #F2DDE7;
        box-shadow: 0 2px 8px rgba(139, 36, 82, 0.04);
        transition: all 0.3s ease;
        height: 100%;
    }

    .feature-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 30px rgba(139, 36, 82, 0.08);
    }

    .feature-header {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        margin-bottom: 0.75rem;
    }

    .feature-icon {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 32px;
        height: 32px;
        background: #FCF7F9;
        border-radius: 8px;
        border: 1px solid #F2DDE7;
        flex-shrink: 0;
        transition: all 0.3s ease;
    }

    .feature-card:hover .feature-icon {
        background: #8B2452;
        border-color: #8B2452;
    }

    .feature-icon i {
        font-size: 1rem;
        color: #8B2452;
        transition: all 0.3s ease;
    }

    .feature-card:hover .feature-icon i { color: #FFFFFF; }

    .feature-title {
        font-size: 0.9rem;
        font-weight: 700;
        color: #2C2C2C;
        margin: 0;
    }

    .feature-list {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .feature-list li {
        display: flex;
        align-items: center;
        gap: 0.4rem;
        font-size: 0.8rem;
        color: #2C2C2C;
        padding: 0.2rem 0;
    }

    .feature-list li i {
        color: #2EAF68;
        font-size: 0.7rem;
        flex-shrink: 0;
    }

    .permissions-list {
        display: flex;
        flex-wrap: wrap;
        gap: 0.4rem;
        padding-top: 0.2rem;
    }

    .permission-pill {
        display: inline-flex;
        align-items: center;
        gap: 0.3rem;
        padding: 0.3rem 0.8rem;
        background: #FCF7F9;
        color: #8B2452;
        border: 1px solid #F2DDE7;
        border-radius: 50px;
        font-size: 0.65rem;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .permission-pill i {
        font-size: 0.6rem;
        color: #8B2452;
    }

    .permission-pill:hover {
        background: #8B2452;
        color: #FFFFFF;
        border-color: #8B2452;
    }

    .permission-pill:hover i { color: #FFFFFF; }

    .help-card { background: #FCF7F9; }
    .help-text {
        font-size: 0.8rem;
        color: #6E6E6E;
        line-height: 1.5;
        margin-bottom: 0.75rem;
    }

    /* Responsive */
    @media (max-width: 992px) {
        .connection-actions { justify-content: flex-start; margin-top: 0.75rem; }
        .section-title { font-size: 1.4rem; }
        .connection-card { padding: 1.5rem; }
    }

    @media (max-width: 768px) {
        .connection-actions { flex-direction: column; width: 100%; }
        .connection-actions .btn { width: 100%; justify-content: center; }
        .connection-card { padding: 1.25rem; }
        .connection-title { font-size: 1.2rem; }
        .instagram-logo { width: 44px; height: 44px; }
        .section-title { font-size: 1.2rem; }
        .status-wrapper { flex-direction: column; align-items: flex-start; gap: 0.3rem; }
    }

    @media (max-width: 480px) {
        .step-card { padding: 1rem; }
        .feature-card { padding: 1rem; }
        .section-title { font-size: 1rem; }
        .section-subtitle { font-size: 0.85rem; }
        .permissions-list { flex-direction: column; }
        .permission-pill { width: 100%; justify-content: center; }
    }
</style>
@endsection