<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="{{config('app.style-default-mode')}}"
      data-theme="dracula">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title inertia>{{ config('app.name', 'Saas boilerplate') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet"/>
    <link rel="icon" sizes="64x64" href="{{ asset('img/icon.png') }}" type="image/x-icon">
    <script type="text/javascript">window.$crisp = [];
        window.CRISP_WEBSITE_ID = "12df5444-a0ea-43a6-8750-4296cfedd8fc";
        (function () {
            d = document;
            s = d.createElement("script");
            s.src = "https://client.crisp.chat/l.js";
            s.async = 1;
            d.getElementsByTagName("head")[0].appendChild(s);
        })();</script>

    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id={{config('app.google-analytics-id')}}"></script>
    <script>
        window.dataLayer = window.dataLayer || [];

        function gtag() {
            dataLayer.push(arguments);
        }

        gtag('js', new Date());

        gtag('config', '{{config('app.google-analytics-id')}}');
    </script>
    <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-0882802698917065"
            crossorigin="anonymous"></script>

    <!-- Scripts -->
    @routes
    @vite(['resources/js/app.js', "resources/js/Pages/{$page['component']}.vue"])
    @inertiaHead
</head>
<body class="font-sans antialiased">
<script async src="https://cdn.promotekit.com/promotekit.js"
        data-promotekit="{{config('services.promotekit.api_key')}}"></script>
@inertia
<div class="flex justify-center">
    <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-0882802698917065"
            crossorigin="anonymous"></script>
    <!-- progr.io -->
    <ins class="adsbygoogle"
         style="display:block"
         data-ad-client="ca-pub-0882802698917065"
         data-ad-slot="3474737115"
         data-ad-format="auto"
         data-full-width-responsive="true"></ins>
    <script>
        (adsbygoogle = window.adsbygoogle || []).push({});
    </script>
</div>
</body>
</html>
