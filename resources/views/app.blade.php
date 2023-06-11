<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/nprogress/0.2.0/nprogress.min.css" />
    @vite('resources/js/app.js')
    @inertiaHead
    @routes
</head>
<body>
<v-app app>
    @inertia
</v-app>
</body>
</html>
