<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title inertia>{{ config('app.name', 'Laravel') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <?php
    // Cek apakah server vite (npm run dev) sedang aktif
    $viteDevServer = @fsockopen('localhost', 5173, $errno, $errstr, 0.5);
    ?>

    @if ($viteDevServer)
    <script type="module" src="http://localhost:5173/@vite/client"></script>
    <script type="module" src="http://localhost:5173/resources/js/app.js"></script>
    <?php fclose($viteDevServer); ?>
    @else
    @endif

    <script>
        window.Ziggy = <?php echo json_encode(app(\Tightenco\Ziggy\Ziggy::class)->toArray()); ?>;
    </script>

    @inertiaHead
</head>

<body class="font-sans antialiased">
    @inertia
</body>

</html>