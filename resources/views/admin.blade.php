<!DOCTYPE html>
<html lang="en">
<head>
 <meta name="viewport" content="width=device-width, initial-scale=1.0">
 <meta name="csrf-token" content="{{ csrf_token() }}">
 <link href="{{ mix('css/app.css', 'dist/admin') }}" rel="stylesheet" type="text/css"/>
 <link rel="icon" type="image/x-icon" href="/icon.png">
 <title>hello</title>
 <script type='text/javascript'>
 window.Laravel = <?php json_encode([
 'csrfToken' => csrf_token(),
 ]); ?>
 </script>
</head>
<body>
<div id="app"></div>
<script type="text/javascript" src="{{ mix('js/app.js', 'dist/admin') }}"></script>
<script type="text/javascript" src="{{ mix('js/manifest.js', 'dist/admin') }}"></script>
<script type="text/javascript" src="{{ mix('js/vendor.js', 'dist/admin') }}"></script>
</body>
</html>
