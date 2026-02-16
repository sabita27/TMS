<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TMS PRO - Excellence in Customer Support</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary: #6366f1;
            --primary-dark: #4f46e5;
            --secondary: #10b981;
            --dark: #0f172a;
            --light: #f8fafc;
            --accent: #f59e0b;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Outfit', sans-serif; background-color: var(--light); color: var(--dark); line-height: 1.6; overflow-x: hidden; }

        /* Navigation */
        nav { padding: 1.5rem 10%; display: flex; justify-content: space-between; align-items: center; background: rgba(255,255,255,0.8); backdrop-filter: blur(10px); position: sticky; top: 0; z-index: 1000; border-bottom: 1px solid rgba(0,0,0,0.05); }
        .logo { font-size: 1.75rem; font-weight: 800; color: var(--primary); letter-spacing: -1px; display: flex; align-items: center; gap: 0.5rem; text-decoration: none; }
        .nav-links { display: flex; gap: 2.5rem; align-items: center; }
        .nav-links a { text-decoration: none; color: #475569; font-weight: 500; transition: 0.3s; font-size: 0.95rem; }
        .nav-links a:hover { color: var(--primary); }
        .btn-login { background: #fff; border: 1px solid #e2e8f0; color: var(--dark); padding: 0.6rem 1.5rem; border-radius: 0.75rem; font-weight: 600; text-decoration: none; transition: 0.3s; }
        .btn-login:hover { background: #f1f5f9; }
        .btn-register { background: var(--primary); color: #fff; padding: 0.6rem 1.5rem; border-radius: 0.75rem; font-weight: 600; text-decoration: none; transition: 0.3s; box-shadow: 0 4px 14px rgba(99, 102, 241, 0.4); }
        .btn-register:hover { transform: translateY(-2px); box-shadow: 0 6px 20px rgba(99, 102, 241, 0.6); }

        /* Hero Section */
        .hero { padding: 8rem 10% 6rem; background: radial-gradient(circle at top right, #eef2ff 0%, #fff 50%); display: flex; align-items: center; gap: 4rem; }
        .hero-content { flex: 1; }
        .hero-badge { display: inline-block; padding: 0.5rem 1rem; background: #e0e7ff; color: var(--primary); border-radius: 2rem; font-size: 0.85rem; font-weight: 700; margin-bottom: 1.5rem; text-transform: uppercase; letter-spacing: 1px; }
        .hero-h1 { font-size: 4.5rem; font-weight: 800; line-height: 1.1; margin-bottom: 1.5rem; letter-spacing: -2px; color: var(--dark); }
        .hero-p { font-size: 1.25rem; color: #64748b; margin-bottom: 2.5rem; max-width: 540px; }
        .hero-btns { display: flex; gap: 1.25rem; }

        /* Features */
        .features { padding: 6rem 10%; background: #fff; }
        .section-header { text-align: center; margin-bottom: 4rem; }
        .section-header h2 { font-size: 2.5rem; font-weight: 800; margin-bottom: 1rem; }
        .section-header p { color: #64748b; font-size: 1.1rem; }
        .feature-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 2.5rem; }
        .feature-card { padding: 2.5rem; border-radius: 1.5rem; background: #f8fafc; border: 1px solid #f1f5f9; transition: 0.3s; }
        .feature-card:hover { background: #fff; transform: translateY(-10px); box-shadow: 0 20px 40px rgba(0,0,0,0.05); }
        .feature-icon { width: 60px; height: 60px; background: var(--primary); color: #fff; border-radius: 1rem; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; margin-bottom: 1.5rem; }
        .feature-card h3 { font-size: 1.5rem; font-weight: 700; margin-bottom: 1rem; }
        .feature-card p { color: #64748b; line-height: 1.7; }

        /* Footer */
        footer { padding: 4rem 10% 2rem; background: var(--dark); color: #94a3b8; }
        .footer-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 4rem; margin-bottom: 4rem; }
        .footer-logo { font-size: 1.5rem; font-weight: 800; color: #fff; text-decoration: none; margin-bottom: 1.5rem; display: block; }
        .footer-h4 { color: #fff; margin-bottom: 1.5rem; font-size: 1.1rem; }
        .footer-links { list-style: none; }
        .footer-links li { margin-bottom: 0.75rem; }
        .footer-links a { color: #94a3b8; text-decoration: none; transition: 0.3s; }
        .footer-links a:hover { color: var(--primary); }
        .copyright { text-align: center; padding-top: 2rem; border-top: 1px solid #1e293b; font-size: 0.9rem; }

        @media (max-width: 1024px) {
            .hero { flex-direction: column; text-align: center; padding-top: 4rem; }
            .hero-p { margin: 0 auto 2.5rem; }
            .hero-btns { justify-content: center; }
            .hero-h1 { font-size: 3.5rem; }
            .feature-grid { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>
    <nav>
        <a href="/" class="logo"><i class="fas fa-ticket-alt"></i> TMS PRO</a>
        <div class="nav-links">
            <a href="#features">Features</a>
            <a href="#about">Our Solution</a>
            @auth
                <a href="{{ url('/dashboard') }}" class="btn-register">Go to Dashboard</a>
            @else
                <a href="{{ route('login') }}" class="btn-login">Sign In</a>
                <a href="{{ route('register') }}" class="btn-register">Get Started</a>
            @endauth
        </div>
    </nav>

    <header class="hero">
        <div class="hero-content">
            <span class="hero-badge">Next-Gen Support Platform</span>
            <h1 class="hero-h1">Modern Support for Modern Teams.</h1>
            <p class="hero-p">The all-in-one ticket management system designed to make customer support seamless, professional, and fast. Build better relationships with every ticket.</p>
            <div class="hero-btns">
                <a href="{{ route('register') }}" class="btn-register" style="padding: 1rem 2.5rem; font-size: 1.1rem;">Try for Free</a>
                <a href="#features" class="btn-login" style="padding: 1rem 2.5rem; font-size: 1.1rem;">View Features</a>
            </div>
        </div>
        <div style="flex: 1; position: relative;">
            <div style="background: var(--primary); width: 100%; height: 500px; border-radius: 2rem; overflow: hidden; box-shadow: 0 30px 60px rgba(99, 102, 241, 0.3);">
                <img src="https://images.unsplash.com/photo-1551434678-e076c223a692?ixlib=rb-1.2.1&auto=format&fit=crop&w=1000&q=80" style="width: 100%; height: 100%; object-fit: cover; opacity: 0.9;" alt="Dashboard Preview">
            </div>
            <!-- Floating Stats Card -->
            <div style="position: absolute; bottom: 30px; left: -30px; background: #fff; padding: 1.5rem; border-radius: 1rem; box-shadow: 0 10px 30px rgba(0,0,0,0.1); display: flex; align-items: center; gap: 1rem;">
                <div style="width: 40px; height: 40px; background: #dcfce7; color: #10b981; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                    <i class="fas fa-check"></i>
                </div>
                <div>
                    <div style="font-weight: 700; color: var(--dark);">99.9% Success</div>
                    <div style="font-size: 0.8rem; color: #64748b;">Ticket Resolution Rate</div>
                </div>
            </div>
        </div>
    </header>

    <section id="features" class="features">
        <div class="section-header">
            <h2>Everything you need to succeed.</h2>
            <p>Powerful tools designed to simplify your workflow and wow your customers.</p>
        </div>
        <div class="feature-grid">
            <div class="feature-card">
                <div class="feature-icon"><i class="fas fa-bolt"></i></div>
                <h3>Fast Response</h3>
                <p>Industry-leading ticket assignment logic ensuring no customer is left waiting.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon" style="background: #10b981;"><i class="fas fa-shield-alt"></i></div>
                <h3>Role-Based Security</h3>
                <p>Granular permissions for Admins, Managers, and Staff to keep data secure and organized.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon" style="background: #f59e0b;"><i class="fas fa-chart-line"></i></div>
                <h3>Advanced Analytics</h3>
                <p>Gain deep insights into your support performance with real-time data visualization.</p>
            </div>
        </div>
    </section>

    <footer>
        <div class="footer-grid">
            <div>
                <a href="/" class="footer-logo">TMS PRO</a>
                <p>The premium choice for enterprise support management since 2024.</p>
            </div>
            <div>
                <h4 class="footer-h4">Product</h4>
                <ul class="footer-links">
                    <li><a href="#">Features</a></li>
                    <li><a href="#">Security</a></li>
                    <li><a href="#">Roadmap</a></li>
                </ul>
            </div>
            <div>
                <h4 class="footer-h4">Company</h4>
                <ul class="footer-links">
                    <li><a href="#">About Us</a></li>
                    <li><a href="#">Careers</a></li>
                    <li><a href="#">Contact</a></li>
                </ul>
            </div>
            <div>
                <h4 class="footer-h4">Legal</h4>
                <ul class="footer-links">
                    <li><a href="#">Privacy Policy</a></li>
                    <li><a href="#">Terms of Service</a></li>
                </ul>
            </div>
        </div>
        <div class="copyright">
            &copy; {{ date('Y') }} TMS PRO. All rights reserved. Created with <i class="fas fa-heart" style="color: #ef4444;"></i> for Support Excellence.
        </div>
    </footer>
</body>
</html>
