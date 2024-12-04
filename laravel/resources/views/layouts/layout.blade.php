<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body>
    <!-- Include the header -->
    @include('partials.header')

    <div class="content">
        <!-- This section will be filled by specific pages -->
        @yield('content')
    </div>

    <footer>
        <!-- Footer content -->
        <footer class="bg-white dark:bg-gray-900">
            <div class="container px-6 py-8 mx-auto">
                <hr class="my-10 border-gray-200 dark:border-gray-700" />

                <div class="flex flex-col items-center sm:flex-row sm:justify-between">
                    <p class="text-sm text-gray-500">© {{ date('Y') }} - MMI3 Dev - Benoît Baraille</p>

                    <div class="flex mt-3 -mx-2 sm:mt-0">
                        <a href="#"
                            class="mx-2 text-sm text-gray-500 transition-colors duration-300 hover:text-gray-500 dark:hover:text-gray-300"
                            aria-label="Reddit"> Teams </a>

                        <a href="#"
                            class="mx-2 text-sm text-gray-500 transition-colors duration-300 hover:text-gray-500 dark:hover:text-gray-300"
                            aria-label="Reddit"> Privacy </a>

                        <a href="#"
                            class="mx-2 text-sm text-gray-500 transition-colors duration-300 hover:text-gray-500 dark:hover:text-gray-300"
                            aria-label="Reddit"> Cookies </a>
                    </div>
                </div>
            </div>
        </footer>
    </footer>
</body>

</html>
