<title>Prévisions Météo</title>
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Weather forecast') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-100 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="container mx-auto p-4">
                        <h1 class="text-center text-2xl font-bold text-blue-500 mb-6">Prévisions Météo</h1>
                        <div class="flex flex-wrap gap-6 justify-center">
                            @foreach ($forecast['list'] as $forecastItem)
                                <div class="bg-white dark:bg-gray-700 rounded-lg shadow-md p-4 flex flex-col items-center w-1/5 min-w-[150px] text-center">
                                    <p class="text-gray-600 font-semibold mb-2">{{ \Carbon\Carbon::createFromTimestamp($forecastItem['dt'])->format('d/m/Y H:i') }}</p>
                                    <img class="w-12 h-12 mb-2" src="http://openweathermap.org/img/wn/{{ $forecastItem['weather'][0]['icon'] }}.png" alt="Weather icon">
                                    <p class="text-orange-500 text-xl font-semibold mb-1">{{ $forecastItem['main']['temp'] }} °C</p>
                                    <p class="text-gray-500 text-sm">{{ $forecastItem['weather'][0]['description'] }}</p>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
