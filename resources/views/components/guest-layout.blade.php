<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ config('app.name', 'Job Sharing Task') }}</title>
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600&display=swap" rel="stylesheet" />
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased text-gray-900 bg-[#eef5fe]">
        <div class="min-h-screen flex flex-col pt-6 sm:pt-0">
            <div class="absolute top-6 left-6 flex items-center">
                <!-- Using a mock logo for the JST logo -->
                <div class="w-12 h-12 bg-blue-200 text-blue-600 font-bold text-2xl flex items-center justify-center italic mr-3" style="font-family: serif;">JST</div>
                <h1 class="text-3xl font-bold text-blue-500">Job Sharing Task</h1>
            </div>

            <div class="w-full sm:max-w-md mt-24 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg mx-auto border border-gray-300">
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
