<!DOCTYPE html>
<html>
<head>
    <title>Force Logout - TMS PRO</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }
        .container {
            text-align: center;
            background: rgba(255, 255, 255, 0.1);
            padding: 3rem;
            border-radius: 1rem;
            backdrop-filter: blur(10px);
        }
        h1 { margin-bottom: 1rem; }
        p { margin-bottom: 2rem; font-size: 1.1rem; }
        button {
            background: white;
            color: #667eea;
            border: none;
            padding: 1rem 2rem;
            font-size: 1.1rem;
            font-weight: bold;
            border-radius: 0.5rem;
            cursor: pointer;
            transition: transform 0.2s;
        }
        button:hover {
            transform: scale(1.05);
        }
        .info {
            margin-top: 2rem;
            font-size: 0.9rem;
            opacity: 0.9;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🔄 Session Reset Required</h1>
        <p>Click the button below to clear your session and log out completely.</p>
        
        <form action="/logout" method="POST">
            <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
            <button type="submit">Force Logout & Clear Session</button>
        </form>
        
        <div class="info">
            After logout, you'll be redirected to the login page.<br>
            Use: <strong>admin@tms.com</strong> / <strong>password</strong>
        </div>
    </div>
</body>
</html>
