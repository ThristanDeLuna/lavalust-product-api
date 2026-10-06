<?php
defined('PREVENT_DIRECT_ACCESS') OR exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title ?? 'My Profile'); ?></title>
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --lava: #dd4814;
            --lava-dim: #b83a10;
            --bg: #0a0a0b;
            --bg2: #111113;
            --bg3: #18181b;
            --border: rgba(255,255,255,0.07);
            --text: #f4f4f5;
            --text-muted: #71717a;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            background: var(--bg);
            color: var(--text);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .card {
            width: 100%;
            max-width: 420px;
            background: var(--bg2);
            border: 1px solid var(--border);
            border-radius: 14px;
            padding: 32px;
        }

        h1 { font-size: 1.25rem; margin-bottom: 20px; }

        dl { display: grid; grid-template-columns: 110px 1fr; row-gap: 10px; }
        dt { color: var(--text-muted); font-size: 0.8rem; }
        dd { font-size: 0.9rem; }

        a.logout {
            display: inline-block;
            margin-top: 24px;
            font-size: 0.85rem;
            color: var(--lava);
            text-decoration: none;
        }

        a.logout:hover { color: var(--lava-dim); text-decoration: underline; }
    </style>
</head>
<body>
    <div class="card">
        <h1>My Profile</h1>
        <dl>
            <dt>Student ID</dt><dd><?= htmlspecialchars($student['student_id']); ?></dd>
            <dt>Name</dt><dd><?= htmlspecialchars($student['name']); ?></dd>
            <dt>Course</dt><dd><?= htmlspecialchars($student['course']); ?></dd>
            <dt>Year</dt><dd><?= htmlspecialchars($student['year']); ?></dd>
            <dt>Section</dt><dd><?= htmlspecialchars($student['section']); ?></dd>
            <dt>Email</dt><dd><?= htmlspecialchars($student['email']); ?></dd>
        </dl>
        <a class="logout" href="<?= base_url('student/logout'); ?>">Log out</a>
    </div>
</body>
</html>