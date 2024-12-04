<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    {{ __("You're logged in!") }}

                    <!-- Form Find CIty -->
                    <form method="GET" action="{{ route('weather') }}">
                        <div class="flex-grow">
                            <label for="city">Search for a city</label>
                            <input id="city" name="city" type="text" required>
                            @error('city')
                                <div class="mt-1 text-red-500 text-sm">{{ $message }}</div>
                            @enderror
                        </div>
                        <button type="submit" class="mt-4 px-4 py-2 bg-blue-600 text-white rounded">Send</button>
                    </form>

                       <!-- Container Favorite-->
                    <div class="mt-8">
                        <h3 class="text-lg font-semibold">Favorite city</h3>
                        @if ($favoriteCity && $favoriteCity->place)
                            <div class="mt-2 p-4 bg-gray-100 dark:bg-gray-700 rounded">
                                <p><strong>Namee :</strong> {{ $favoriteCity->place->name }}</p>
                              
                                @if ($weatherData)
                                    <p><strong>Weather:</strong> {{ $weatherData['weather'][0]['description'] }}</p>
                                    <p><strong>Temperature :</strong> {{ $weatherData['main']['temp'] }} °C</p>
                                    <p><strong>Humidity :</strong> {{ $weatherData['main']['humidity'] }}%</p>
                                    <p><strong>Wind :</strong> {{ $weatherData['wind']['speed'] }} m/s</p>
                                @else
                                    <p>Impossible to retrieve weather data for this city.</p>
                                @endif
                                <a href="{{ route('remove_favorite', ['city_id' => $favoriteCity->place->id]) }}" class="mt-4 inline-block px-4 py-2 bg-red-600 text-white rounded">
                                Delete favorites
                                </a>
                            </div>
                        @else
                            <p>No favorite city defined.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
