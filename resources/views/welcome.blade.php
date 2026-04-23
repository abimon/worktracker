<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WorkTracker - Professional Project Management for Developers</title>
    <meta name="description" content="Streamline your development projects with WorkTracker. Track tasks, manage payments, collaborate with teams, and get verified as a professional developer.">
    <!-- Using Google Fonts for better typography -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">

    <style>
        /* --- CSS Variables for consistent theming --- */
        :root {
            --primary-color: #4F46E5; /* Indigo */
            --primary-hover: #4338ca;
            --secondary-color: #10B981; /* Emerald */
            --accent-color: #F59E0B; /* Amber */
            --text-dark: #1F2937;
            --text-light: #6B7280;
            --bg-light: #F9FAFB;
            --bg-white: #FFFFFF;
            --bg-dark: #111827;
            --max-width: 1200px;
            --transition: all 0.3s ease;
        }

        /* --- Global Reset & Base Styles --- */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            color: var(--text-dark);
            line-height: 1.6;
            background-color: var(--bg-white);
        }

        a {
            text-decoration: none;
            color: inherit;
        }

        ul {
            list-style: none;
        }

        img {
            max-width: 100%;
            height: auto;
            display: block;
        }

        /* --- Layout Utilities --- */
        .container {
            max-width: var(--max-width);
            margin: 0 auto;
            padding: 0 20px;
        }

        .section-padding {
            padding: 80px 0;
        }

        .text-center {
            text-align: center;
        }

        /* --- Header / Navigation --- */
        header {
            background-color: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            position: fixed;
            width: 100%;
            top: 0;
            z-index: 1000;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }

        nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
            height: 70px;
        }

        .logo {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--primary-color);
        }

        .nav-links {
            display: flex;
            gap: 30px;
        }

        .nav-links a {
            font-weight: 500;
            color: var(--text-dark);
            transition: var(--transition);
        }

        .nav-links a:hover {
            color: var(--primary-color);
        }

        .btn-nav {
            padding: 8px 20px;
            background-color: var(--primary-color);
            color: white !important;
            border-radius: 6px;
        }

        .btn-nav:hover {
            background-color: var(--primary-hover);
        }

        /* --- Hero Section --- */
        .hero {
            padding-top: 150px; /* Account for fixed header */
            padding-bottom: 100px;
            background: linear-gradient(135deg, #f0fdf4 0%, #eef2ff 100%);
            position: relative;
            overflow: hidden;
        }

        .hero::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grid" width="10" height="10" patternUnits="userSpaceOnUse"><path d="M 10 0 L 0 0 0 10" fill="none" stroke="rgba(79,70,229,0.1)" stroke-width="0.5"/></pattern></defs><rect width="100" height="100" fill="url(%23grid)"/></svg>');
            opacity: 0.3;
        }

        .hero-content {
            max-width: 800px;
            margin: 0 auto;
            text-align: center;
            position: relative;
            z-index: 1;
        }

        .hero h1 {
            font-size: 3.5rem;
            font-weight: 800;
            margin-bottom: 20px;
            line-height: 1.2;
            color: var(--text-dark);
        }

        .hero p {
            font-size: 1.25rem;
            color: var(--text-light);
            margin-bottom: 40px;
        }

        .cta-group {
            display: flex;
            justify-content: center;
            gap: 20px;
        }

        .btn {
            display: inline-block;
            padding: 12px 30px;
            border-radius: 8px;
            font-weight: 600;
            transition: var(--transition);
            cursor: pointer;
        }

        .btn-primary {
            background-color: var(--primary-color);
            color: white;
            border: 2px solid var(--primary-color);
        }

        .btn-primary:hover {
            background-color: var(--primary-hover);
            border-color: var(--primary-hover);
            transform: translateY(-2px);
        }

        .btn-outline {
            background-color: transparent;
            color: var(--primary-color);
            border: 2px solid var(--primary-color);
        }

        .btn-outline:hover {
            background-color: var(--primary-color);
            color: white;
            transform: translateY(-2px);
        }

        /* --- Features Section --- */
        .features {
            background-color: var(--bg-white);
        }

        .section-title {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 15px;
        }

        .section-subtitle {
            color: var(--text-light);
            margin-bottom: 60px;
            max-width: 600px;
            margin-left: auto;
            margin-right: auto;
        }

        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 40px;
        }

        .feature-card {
            padding: 30px;
            border-radius: 12px;
            background-color: var(--bg-light);
            transition: var(--transition);
            border: 1px solid transparent;
        }

        .feature-card:hover {
            transform: translateY(-5px);
            background-color: white;
            box-shadow: 0 10px 30px rgba(0,0,0,0.08);
            border-color: rgba(79, 70, 229, 0.1);
        }

        .icon-box {
            width: 50px;
            height: 50px;
            background-color: #e0e7ff;
            color: var(--primary-color);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 20px;
            font-size: 1.5rem;
        }

        .feature-card h3 {
            margin-bottom: 10px;
            font-size: 1.25rem;
        }

        .feature-card p {
            color: var(--text-light);
            font-size: 0.95rem;
        }

        /* --- How It Works Section --- */
        .how-it-works {
            background-color: var(--bg-light);
        }

        .steps-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 40px;
        }

        .step-card {
            text-align: center;
            padding: 30px;
            background-color: white;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.05);
        }

        .step-number {
            width: 60px;
            height: 60px;
            background-color: var(--primary-color);
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            font-weight: 700;
            margin: 0 auto 20px;
        }

        .step-card h3 {
            margin-bottom: 15px;
        }

        /* --- Testimonials Section --- */
        .testimonials {
            background-color: var(--bg-white);
        }

        .testimonials-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 30px;
        }

        .testimonial-card {
            padding: 30px;
            background-color: var(--bg-light);
            border-radius: 12px;
            border-left: 4px solid var(--primary-color);
        }

        .testimonial-text {
            font-style: italic;
            margin-bottom: 20px;
            color: var(--text-dark);
        }

        .testimonial-author {
            font-weight: 600;
            color: var(--primary-color);
        }

        .testimonial-role {
            color: var(--text-light);
            font-size: 0.9rem;
        }

        /* --- Pricing Section --- */
        .pricing {
            background-color: var(--bg-light);
        }

        .pricing-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 30px;
        }

        .pricing-card {
            background-color: white;
            border-radius: 12px;
            padding: 40px 30px;
            text-align: center;
            box-shadow: 0 4px 6px rgba(0,0,0,0.05);
            border: 2px solid transparent;
            transition: var(--transition);
        }

        .pricing-card:hover {
            transform: translateY(-5px);
            border-color: var(--primary-color);
        }

        .pricing-card.popular {
            border-color: var(--primary-color);
            position: relative;
        }

        .pricing-card.popular::before {
            content: 'Most Popular';
            position: absolute;
            top: -12px;
            left: 50%;
            transform: translateX(-50%);
            background-color: var(--primary-color);
            color: white;
            padding: 4px 12px;
            border-radius: 12px;
            font-size: 0.8rem;
            font-weight: 600;
        }

        .pricing-price {
            font-size: 3rem;
            font-weight: 800;
            color: var(--primary-color);
            margin: 20px 0;
        }

        .pricing-price span {
            font-size: 1rem;
            font-weight: 400;
            color: var(--text-light);
        }

        .pricing-features {
            list-style: none;
            margin: 30px 0;
        }

        .pricing-features li {
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .pricing-features li::before {
            content: "✓";
            color: var(--secondary-color);
            font-weight: bold;
            margin-right: 10px;
        }

        /* --- CTA Section --- */
        .cta-section {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-hover) 100%);
            color: white;
            text-align: center;
        }

        .cta-section h2 {
            font-size: 2.5rem;
            margin-bottom: 20px;
        }

        .cta-section p {
            font-size: 1.25rem;
            margin-bottom: 40px;
            opacity: 0.9;
        }

        /* --- Footer --- */
        footer {
            background-color: var(--bg-dark);
            color: #d1d5db;
            padding: 60px 0 20px;
        }

        .footer-content {
            display: flex;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 40px;
            margin-bottom: 40px;
            border-bottom: 1px solid #374151;
            padding-bottom: 40px;
        }

        .footer-col h4 {
            color: white;
            margin-bottom: 20px;
            font-size: 1.1rem;
        }

        .footer-col ul li {
            margin-bottom: 10px;
        }

        .footer-col ul li a:hover {
            color: white;
        }

        .copyright {
            text-align: center;
            font-size: 0.9rem;
        }

        /* --- Responsive Design --- */
        @media (max-width: 768px) {
            .hero h1 {
                font-size: 2.5rem;
            }

            .nav-links {
                display: none; /* Simple hiding for mobile for this demo */
            }

            .cta-group {
                flex-direction: column;
                padding: 0 40px;
            }

            .features-grid,
            .steps-grid,
            .testimonials-grid,
            .pricing-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>

    <!-- Header -->
    <header>
        <div class="container">
            <nav>
                <a href="#" class="logo">WorkTracker</a>
                <ul class="nav-links">
                    <li><a href="#features">Features</a></li>
                    <li><a href="#how-it-works">How It Works</a></li>
                    <li><a href="#pricing">Pricing</a></li>
                    <li><a href="{{ route('login') }}" class="btn-nav">Get Started</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <!-- Hero Section -->
    <section class="hero">
        <div class="container hero-content">
            <h1>Professional Project Management Made Simple</h1>
            <p>Streamline your development workflow with WorkTracker. Track projects, manage tasks, handle payments, and collaborate seamlessly with clients and team members. Get verified and build trust in your development career.</p>
            <div class="cta-group">
                <a href="{{ route('register') }}" class="btn btn-primary">Start Free Today</a>
                <a href="#features" class="btn btn-outline">Learn More</a>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="section-padding features">
        <div class="container">
            <div class="text-center">
                <h2 class="section-title">Everything You Need to Succeed</h2>
                <p class="section-subtitle">WorkTracker provides comprehensive tools for developers, clients, and teams to manage projects efficiently and professionally.</p>
            </div>

            <div class="features-grid">
                <!-- Feature 1 -->
                <div class="feature-card">
                    <div class="icon-box">📋</div>
                    <h3>Project Management</h3>
                    <p>Create and manage projects with detailed tracking, progress monitoring, and deadline management. Keep everything organized in one place.</p>
                </div>
                <!-- Feature 2 -->
                <div class="feature-card">
                    <div class="icon-box">✅</div>
                    <h3>Task Tracking</h3>
                    <p>Break down projects into manageable tasks with priority levels, due dates, and completion tracking. Never miss a deadline again.</p>
                </div>
                <!-- Feature 3 -->
                <div class="feature-card">
                    <div class="icon-box">💰</div>
                    <h3>Payment Integration</h3>
                    <p>Handle payments seamlessly with support for M-Pesa, credit cards, bank transfers, and manual payments. Track all financial transactions.</p>
                </div>
                <!-- Feature 4 -->
                <div class="feature-card">
                    <div class="icon-box">🤝</div>
                    <h3>Team Collaboration</h3>
                    <p>Invite team members and clients with role-based permissions. Share projects securely with unique tokens and manage access levels.</p>
                </div>
                <!-- Feature 5 -->
                <div class="feature-card">
                    <div class="icon-box">⭐</div>
                    <h3>Feedback System</h3>
                    <p>Collect and manage feedback from clients with ratings, comments, and file attachments. Build better relationships through transparent communication.</p>
                </div>
                <!-- Feature 6 -->
                <div class="feature-card">
                    <div class="icon-box">🛡️</div>
                    <h3>Developer Verification</h3>
                    <p>Get verified as a professional developer by submitting documents and building your reputation. Increase client trust and opportunities.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- How It Works Section -->
    <section id="how-it-works" class="section-padding how-it-works">
        <div class="container">
            <div class="text-center">
                <h2 class="section-title">How WorkTracker Works</h2>
                <p class="section-subtitle">Get started in just three simple steps and transform your project management workflow.</p>
            </div>

            <div class="steps-grid">
                <div class="step-card">
                    <div class="step-number">1</div>
                    <h3>Sign Up & Verify</h3>
                    <p>Create your account, complete your profile, and get verified as a professional developer to build trust with clients.</p>
                </div>
                <div class="step-card">
                    <div class="step-number">2</div>
                    <h3>Create Projects</h3>
                    <p>Set up your projects with detailed requirements, budgets, and timelines. Invite collaborators and start tracking progress.</p>
                </div>
                <div class="step-card">
                    <div class="step-number">3</div>
                    <h3>Manage & Deliver</h3>
                    <p>Track tasks, handle payments, collect feedback, and deliver successful projects while maintaining professional relationships.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Testimonials Section -->
    <section class="section-padding testimonials">
        <div class="container">
            <div class="text-center">
                <h2 class="section-title">What Our Users Say</h2>
                <p class="section-subtitle">Join thousands of developers and clients who trust WorkTracker for their project management needs.</p>
            </div>

            <div class="testimonials-grid">
                <div class="testimonial-card">
                    <p class="testimonial-text">"WorkTracker has completely transformed how I manage my freelance projects. The payment integration and client feedback system are game-changers!"</p>
                    <p class="testimonial-author">Sarah Johnson</p>
                    <p class="testimonial-role">Full-Stack Developer</p>
                </div>
                <div class="testimonial-card">
                    <p class="testimonial-text">"As a client, I love being able to track project progress in real-time and provide feedback directly through the platform. Very professional and transparent."</p>
                    <p class="testimonial-author">Michael Chen</p>
                    <p class="testimonial-role">Startup Founder</p>
                </div>
                <div class="testimonial-card">
                    <p class="testimonial-text">"The verification system gives me confidence when hiring developers. WorkTracker makes collaboration smooth and payments hassle-free."</p>
                    <p class="testimonial-author">Emma Rodriguez</p>
                    <p class="testimonial-role">Project Manager</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Pricing Section -->
    <section id="pricing" class="section-padding pricing">
        <div class="container">
            <div class="text-center">
                <h2 class="section-title">Simple, Transparent Pricing</h2>
                <p class="section-subtitle">Choose the plan that fits your needs. Start free and upgrade as you grow.</p>
            </div>

            <div class="pricing-grid">
                <div class="pricing-card">
                    <h3>Free</h3>
                    <div class="pricing-price">$0<span>/month</span></div>
                    <ul class="pricing-features">
                        <li>Up to 3 projects</li>
                        <li>Basic task tracking</li>
                        <li>Client feedback</li>
                        <li>Email support</li>
                    </ul>
                    <a href="{{ route('register') }}" class="btn btn-primary">Get Started</a>
                </div>
                <div class="pricing-card popular">
                    <h3>Professional</h3>
                    <div class="pricing-price">$19<span>/month</span></div>
                    <ul class="pricing-features">
                        <li>Unlimited projects</li>
                        <li>Advanced task management</li>
                        <li>Payment integration</li>
                        <li>Team collaboration</li>
                        <li>Developer verification</li>
                        <li>Priority support</li>
                    </ul>
                    <a href="{{ route('register') }}" class="btn btn-primary">Start Free Trial</a>
                </div>
                <div class="pricing-card">
                    <h3>Enterprise</h3>
                    <div class="pricing-price">$49<span>/month</span></div>
                    <ul class="pricing-features">
                        <li>Everything in Professional</li>
                        <li>Custom integrations</li>
                        <li>Advanced analytics</li>
                        <li>Dedicated account manager</li>
                        <li>24/7 phone support</li>
                    </ul>
                    <a href="{{ route('register') }}" class="btn btn-outline">Contact Sales</a>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="section-padding cta-section">
        <div class="container">
            <h2>Ready to Transform Your Workflow?</h2>
            <p>Join WorkTracker today and experience professional project management like never before.</p>
            <a href="{{ route('register') }}" class="btn btn-primary" style="background-color: white; color: var(--primary-color); border-color: white;">Start Your Free Trial</a>
        </div>
    </section>

    <!-- Footer -->
    <footer>
        <div class="container">
            <div class="footer-content">
                <div class="footer-col" style="flex: 2; min-width: 250px;">
                    <h4>WorkTracker</h4>
                    <p>Empowering developers and clients with professional project management tools. Build better, collaborate better, succeed together.</p>
                </div>
                <div class="footer-col">
                    <h4>Product</h4>
                    <ul>
                        <li><a href="#features">Features</a></li>
                        <li><a href="#pricing">Pricing</a></li>
                        <li><a href="#how-it-works">How It Works</a></li>
                        <li><a href="{{ route('login') }}">Sign In</a></li>
                    </ul>
                </div>
                <div class="footer-col">
                    <h4>Support</h4>
                    <ul>
                        <li><a href="#">Help Center</a></li>
                        <li><a href="#">Documentation</a></li>
                        <li><a href="#">Contact Us</a></li>
                        <li><a href="#">Status</a></li>
                    </ul>
                </div>
                <div class="footer-col">
                    <h4>Company</h4>
                    <ul>
                        <li><a href="#">About</a></li>
                        <li><a href="#">Blog</a></li>
                        <li><a href="#">Careers</a></li>
                        <li><a href="#">Privacy Policy</a></li>
                    </ul>
                </div>
            </div>
            <div class="copyright">
                <p>&copy; 2024 WorkTracker. All rights reserved.</p>
            </div>
        </div>
    </footer>

</body>
</html>