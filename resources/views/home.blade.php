<!-- resources/views/home.blade.php -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home Page</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}"> <!-- Если у вас есть стили -->
</head>
<body>
<header>
    <h1>Welcome to My Laravel App</h1>
</header>
<main>
    <p>This is the home page of your Laravel application!</p>
    <p>Feel free to customize it as needed.</p>
</main>
<footer>
    <p>&copy; {{ date('Y') }} Your Name. All rights reserved.</p>
</footer>
</body>
</html>
