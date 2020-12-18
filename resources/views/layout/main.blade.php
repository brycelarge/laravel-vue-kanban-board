<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Laravel & VueJs Kanban Board</title>
    <link rel="stylesheet" href="{{ mix('/css/app.css') }}">
</head>
<body class="bg-dark">
@yield('content')
<script src="{{ mix('/js/app.js') }}"></script>
@yield('scripts')
</body>
</html>
