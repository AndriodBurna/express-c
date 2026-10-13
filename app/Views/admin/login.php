<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sign In | Ephraitech Admin</title>
    <link rel="icon" type="image/svg+xml" href="<?= base_url('assets/images/favicon.svg') ?>">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@600;700;800&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer">
    <link rel="stylesheet" href="<?= base_url('assets/css/admin.css') ?>?v=1.1.1">

    <style>
        :root {
            --c-blue: #1e3fa0;
            --c-blue-light: #4FB3FF;
            --c-gold: #C9A227;
            --c-bg: #0A0A0B;
            --c-surface: #131316;
            --c-border: rgba(255,255,255,.08);
            --c-text: #ffffff;
            --c-text-muted: rgba(255,255,255,.55);
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }

        body.login-body {
            font-family: 'Inter', system-ui, sans-serif;
            background: var(--c-bg);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem 1.25rem;
            position: relative;
            overflow: hidden;
        }

        /* Ambient background glow */
        body.login-body::before,
        body.login-body::after {
            content: '';
            position: fixed;
            border-radius: 50%;
            filter: blur(140px);
            opacity: .35;
            pointer-events: none;
            z-index: 0;
        }
        body.login-body::before {
            width: 520px; height: 520px;
            background: var(--c-blue);
            top: -180px; left: -180px;
        }
        body.login-body::after {
            width: 420px; height: 420px;
            background: var(--c-gold);
            bottom: -160px; right: -160px;
            opacity: .18;
        }

        .login-wrap {
            width: 100%;
            max-width: 420px;
            position: relative;
            z-index: 1;
        }

        .login-card {
            background: var(--c-surface);
            border: 1px solid var(--c-border);
            border-radius: 20px;
            padding: 2.75rem 2.25rem 2rem;
            box-shadow:
                0 24px 60px rgba(0,0,0,.45),
                0 0 0 1px rgba(255,255,255,.02) inset;
            backdrop-filter: blur(10px);
        }

        /* Logo block */
        .login-brand {
            display: flex;
            align-items: center;
            gap: .85rem;
            margin-bottom: 2.25rem;
            padding-bottom: 1.75rem;
            border-bottom: 1px solid var(--c-border);
        }
        .login-brand img {
            height: 40px;
            width: auto;
            flex-shrink: 0;
            display: block;
        }
        .login-brand-text {
            display: flex;
            flex-direction: column;
            line-height: 1.1;
        }
        .login-brand-text strong {
            font-family: 'Manrope', sans-serif;
            font-weight: 800;
            font-size: 1.05rem;
            letter-spacing: .04em;
            color: #fff;
        }
        .login-brand-text small {
            font-size: .72rem;
            color: var(--c-text-muted);
            letter-spacing: .18em;
            text-transform: uppercase;
            margin-top: .15rem;
        }

        .login-heading {
            font-family: 'Manrope', sans-serif;
            font-size: 1.55rem;
            font-weight: 700;
            color: #fff;
            letter-spacing: -.02em;
            margin-bottom: .35rem;
        }
        .login-subheading {
            font-size: .9rem;
            color: var(--c-text-muted);
            margin-bottom: 2rem;
        }

        /* Fields */
        .login-field {
            margin-bottom: 1.15rem;
        }
        .login-field label {
            display: block;
            font-size: .78rem;
            font-weight: 600;
            color: rgba(255,255,255,.75);
            letter-spacing: .02em;
            text-transform: uppercase;
            margin-bottom: .55rem;
        }
        .login-input-wrap {
            position: relative;
        }
        .login-input-wrap i {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: rgba(255,255,255,.35);
            font-size: .95rem;
            pointer-events: none;
            transition: color .2s;
        }
        .login-input {
            width: 100%;
            padding: .85rem 1rem .85rem 2.85rem;
            background: rgba(255,255,255,.03);
            border: 1px solid var(--c-border);
            border-radius: 12px;
            color: #fff;
            font-size: .95rem;
            font-family: inherit;
            transition: all .2s ease;
            outline: none;
        }
        .login-input::placeholder {
            color: rgba(255,255,255,.3);
        }
        .login-input:hover {
            background: rgba(255,255,255,.05);
        }
        .login-input:focus {
            border-color: var(--c-blue-light);
            background: rgba(79,179,255,.06);
            box-shadow: 0 0 0 3px rgba(79,179,255,.12);
        }
        .login-input:focus + i,
        .login-input-wrap:focus-within i {
            color: var(--c-blue-light);
        }

        /* Submit button */
        .login-submit {
            width: 100%;
            padding: .95rem 1rem;
            background: linear-gradient(135deg, var(--c-blue) 0%, #2c56c9 100%);
            color: #fff;
            border: none;
            border-radius: 12px;
            font-family: 'Manrope', sans-serif;
            font-weight: 700;
            font-size: .95rem;
            letter-spacing: .01em;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: .6rem;
            margin-top: 1.75rem;
            transition: transform .15s ease, box-shadow .2s ease, filter .2s ease;
            box-shadow: 0 8px 24px rgba(30,63,160,.35);
        }
        .login-submit:hover {
            filter: brightness(1.1);
            transform: translateY(-1px);
            box-shadow: 0 12px 30px rgba(30,63,160,.45);
        }
        .login-submit:active {
            transform: translateY(0);
        }
        .login-submit i {
            font-size: .85rem;
        }

        /* Alerts */
        .login-alert {
            padding: .8rem 1rem;
            border-radius: 10px;
            font-size: .85rem;
            margin-bottom: 1.25rem;
            display: flex;
            align-items: flex-start;
            gap: .6rem;
            line-height: 1.5;
        }
        .login-alert.error {
            background: rgba(220,38,38,.12);
            border: 1px solid rgba(220,38,38,.3);
            color: #fca5a5;
        }
        .login-alert.success {
            background: rgba(34,197,94,.12);
            border: 1px solid rgba(34,197,94,.3);
            color: #86efac;
        }
        .login-alert i { margin-top: .15rem; }

        /* Footer */
        .login-footer {
            text-align: center;
            margin-top: 1.75rem;
            padding-top: 1.5rem;
            border-top: 1px solid var(--c-border);
        }
        .login-footer a {
            color: var(--c-text-muted);
            text-decoration: none;
            font-size: .85rem;
            display: inline-flex;
            align-items: center;
            gap: .4rem;
            transition: color .2s;
        }
        .login-footer a:hover {
            color: var(--c-blue-light);
        }

        .login-copyright {
            text-align: center;
            margin-top: 1.5rem;
            font-size: .75rem;
            color: rgba(255,255,255,.3);
            letter-spacing: .02em;
        }

        @media (max-width: 480px) {
            .login-card { padding: 2rem 1.5rem 1.75rem; border-radius: 16px; }
            .login-heading { font-size: 1.35rem; }
            .login-brand { margin-bottom: 1.75rem; padding-bottom: 1.35rem; }
        }
    </style>
</head>
<body class="login-body">
    <div class="login-wrap">
        <div class="login-card">

            <!-- Brand -->
            <div class="login-brand">
                <img src="<?= base_url('assets/images/logo%20no%20bg.png') ?>" alt="Ephraitech">
                <div class="login-brand-text">
                    <strong>EPHRAITECH</strong>
                    <small>Unified Solutions</small>
                </div>
            </div>

            <!-- Heading -->
            <h1 class="login-heading">Welcome back</h1>
            <p class="login-subheading">Sign in to manage your website.</p>

            <!-- Alerts -->
            <?php if (session()->getFlashdata('error')): ?>
                <div class="login-alert error">
                    <i class="fa-solid fa-circle-exclamation"></i>
                    <span><?= esc(session()->getFlashdata('error')) ?></span>
                </div>
            <?php endif; ?>
            <?php if (session()->getFlashdata('success')): ?>
                <div class="login-alert success">
                    <i class="fa-solid fa-circle-check"></i>
                    <span><?= esc(session()->getFlashdata('success')) ?></span>
                </div>
            <?php endif; ?>

            <!-- Form -->
            <form method="post" action="<?= url('control/login') ?>" novalidate>
                <?= csrf_field() ?>

                <div class="login-field">
                    <label for="username">Username</label>
                    <div class="login-input-wrap">
                        <input class="login-input"
                               id="username" name="username" type="text"
                               autocomplete="username" required autofocus
                               placeholder="Enter your username"
                               value="<?= esc(old('username') ?? '') ?>">
                        <i class="fa-solid fa-user"></i>
                    </div>
                </div>

                <div class="login-field">
                    <label for="password">Password</label>
                    <div class="login-input-wrap">
                        <input class="login-input"
                               id="password" name="password" type="password"
                               autocomplete="current-password" required
                               placeholder="Enter your password">
                        <i class="fa-solid fa-lock"></i>
                    </div>
                </div>

                <button type="submit" class="login-submit">
                    Sign In
                    <i class="fa-solid fa-arrow-right"></i>
                </button>
            </form>

            <!-- Footer -->
            <div class="login-footer">
                <a href="<?= url('/') ?>">
                    <i class="fa-solid fa-arrow-left"></i>
                    Back to website
                </a>
            </div>
        </div>

        <p class="login-copyright">
            &copy; <?= date('Y') ?> Ephraitech Unified Solutions. All rights reserved.
        </p>
    </div>
</body>
</html>