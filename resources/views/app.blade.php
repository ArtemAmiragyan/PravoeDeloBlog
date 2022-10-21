<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ env('APP_NAME') }}</title>

    <link rel="icon" sizes="50x50" href="/assets/images/logo.png">

    <link href="{{ mix('build/app/app.css') }}" rel="stylesheet" type="text/css"/>
</head>
<body>
<div id="app">
</div>
<script src="{{ mix('build/app/app.js') }}"></script>
</body>
</html>
