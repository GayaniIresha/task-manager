<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Task Manager - Welcome</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container d-flex flex-column justify-content-center align-items-center vh-100">
        <div class="text-center mb-5">
            <h1 class="display-3 fw-bold text-primary">Task Manager</h1>
            <p class="lead text-secondary">Organize your work, stay on track.</p>
        </div>
        <div class="d-flex gap-3">
            <a href="{{ route('login') }}" class="btn btn-primary btn-lg px-4 shadow-sm">Login</a>
            <a href="{{ route('register') }}" class="btn btn-outline-primary btn-lg px-4 shadow-sm">Register</a>
        </div>
    </div>
</body>
</html>
