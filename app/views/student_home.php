<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');

// If a password attempt already failed, jump straight to the password form
// so the person doesn't have to click "Log In" again to see the error.
$show_form = !empty($error);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title ?? 'Student Home'); ?></title>
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --green: #22c55e;
            --green-dim: #16a34a;
            --red: #ef4444;
            --red-bg: rgba(239,68,68,0.12);
            --red-border: rgba(239,68,68,0.35);
            --bg: #0b1220;
            --card: #131c2e;
            --border: rgba(255,255,255,0.08);
            --text: #f4f6fa;
            --text-muted: #8b93a7;
            --input-bg: #0f1826;
        }

        html, body { height: 100%; }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            background:
                radial-gradient(circle at 50% 35%, rgba(59,90,140,0.25), transparent 60%),
                var(--bg);
            color: var(--text);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px;
        }

        .card {
            width: 100%;
            max-width: 360px;
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: 16px;
            padding: 32px 28px;
            text-align: center;
            box-shadow: 0 20px 50px rgba(0,0,0,0.35);
        }

        h1 { font-size: 1.15rem; font-weight: 700; margin-bottom: 8px; }

        p.sub {
            color: var(--text-muted);
            font-size: 0.82rem;
            line-height: 1.4;
            margin-bottom: 20px;
        }

        .badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: 0.72rem;
            font-weight: 600;
            padding: 5px 12px;
            border-radius: 999px;
            margin-bottom: 20px;
            background: var(--red-bg);
            border: 1px solid var(--red-border);
            color: #fca5a5;
        }

        .badge .dot {
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: var(--red);
        }

        button, .btn {
            width: 100%;
            padding: 11px 12px;
            border-radius: 8px;
            border: none;
            font-size: 0.9rem;
            font-weight: 600;
            cursor: pointer;
            display: block;
        }

        .btn-locked {
            background: rgba(255,255,255,0.06);
            color: var(--text-muted);
            margin-bottom: 10px;
            cursor: not-allowed;
        }

        .btn-primary { background: var(--green); color: #06210f; }
        .btn-primary:hover { background: var(--green-dim); }

        .error {
            background: var(--red-bg);
            border: 1px solid var(--red-border);
            color: #fca5a5;
            font-size: 0.8rem;
            padding: 9px 12px;
            border-radius: 8px;
            margin-bottom: 16px;
            text-align: left;
        }

        input[type="password"] {
            width: 100%;
            padding: 10px 12px;
            border-radius: 8px;
            border: 1px solid var(--border);
            background: var(--input-bg);
            color: var(--text);
            font-size: 0.9rem;
            margin-bottom: 12px;
        }

        input[type="password"]:focus { outline: none; border-color: var(--green); }

        label {
            display: block;
            text-align: left;
            font-size: 0.75rem;
            color: var(--text-muted);
            margin-bottom: 6px;
        }

        #loginPrompt.hidden,
        #loginForm.hidden { display: none; }
    </style>
</head>
<body>
    <div class="card">
        <h1>Student Profile Hub</h1>
        <p class="sub">Welcome to the Student Information System.</p>

        <div id="loginPrompt" class="<?= $show_form ? 'hidden' : ''; ?>">
            <span class="badge"><span class="dot"></span>Not Logged In</span>
            <button type="button" class="btn-locked" disabled>View Profile (Locked)</button>
            <button type="button" class="btn btn-primary" onclick="document.getElementById('loginPrompt').classList.add('hidden'); document.getElementById('loginForm').classList.remove('hidden'); document.getElementById('password').focus();">Log In</button>
        </div>

        <div id="loginForm" class="<?= $show_form ? '' : 'hidden'; ?>">
            <?php if (!empty($error)): ?>
                <div class="error"><?= htmlspecialchars($error); ?></div>
            <?php endif; ?>
            <form method="post" action="<?= base_url('student'); ?>">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" autofocus required>
                <button type="submit" class="btn btn-primary">Unlock Profile</button>
            </form>
        </div>
    </div>
</body>
</html>