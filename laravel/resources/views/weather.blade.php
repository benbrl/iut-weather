<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Weather') }}
        </h2>
    </x-slot>

    <!-- Messages de statut -->
    @if (session('status'))
        <div
            class="mt-4 text-{{ session('status') === 'success' ? 'green' : (session('status') === 'error' ? 'red' : 'yellow') }}-500">
            {{ session('message') }}
        </div>
    @endif

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h1>Weather Conditions</h1>
                    <form method="GET" action="{{ route('weather') }}">
                        <div class="flex-grow">
                            <label for="city">Find City</label>
                            <input id="city" name="city" type="text" required>
                            @error('city')
                                <div class="mt-1 text-red-500 text-sm">{{ $message }}</div>
                            @enderror
                        </div>

                        <button
                            class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-500 focus:outline-none"
                            type="submit">Send</button>
                    </form>

                    @if (isset($weather))
                        <h2>City: {{ $weather['name'] }}</h2>
                        <p>Temperature: {{ $weather['main']['temp'] }} °C</p>
                        <p>Conditions: {{ $weather['weather']['0']['description'] }}</p>
                        <p>Humidity: {{ $weather['main']['humidity'] }}%</p>

                        <div class="flex justify-center mt-4 space-x-4">
                            <!-- Download CSV -->
                            <a href="{{ route('download.csv', ['city' => strtolower($weather['name'])]) }}"
                                class="flex items-center px-4 py-2 font-medium tracking-wide text-white capitalize transition-colors duration-300 transform bg-blue-600 rounded-lg hover:bg-blue-500 focus:outline-none focus:ring focus:ring-blue-300 focus:ring-opacity-80">
                                <span class="mx-1">Download CSV</span>
                            </a>

                            <!-- See forecasts -->
                            <a href="{{ route('forecast', ['city' => strtolower($weather['name'])]) }}"
                                class="px-4 py-2 font-medium tracking-wide text-white bg-blue-600 rounded-lg hover:bg-blue-500 focus:outline-none focus:ring focus:ring-blue-300 focus:ring-opacity-80">
                                See forecasts
                            </a>

                            <!-- Add or remove favorites -->
                            @if ($isFavorite)
                                <form action="{{ route('remove_favorite', ['city_id' => $cityId]) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="flex items-center px-4 py-2 font-medium tracking-wide text-white capitalize transition-colors duration-300 transform bg-red-600 rounded-lg hover:bg-red-500 focus:outline-none focus:ring focus:ring-red-300 focus:ring-opacity-80">
                                        <span class="mx-1">Remove from Favorites</span>
                                    </button>
                                </form>
                            @else
                                <form method="POST">

                                    @csrf
                                    <button type="submit"
                                        class="flex items-center px-4 py-2 font-medium tracking-wide text-white capitalize transition-colors duration-300 transform bg-blue-600 rounded-lg hover:bg-blue-500 focus:outline-none focus:ring focus:ring-blue-300 focus:ring-opacity-80">
                                        <span class="mx-1">Add to Favorites</span>
                                    </button>
                                </form>
                            @endif

                            <!-- Save City -->
                            @if ($isSaved)
                                <form action="{{ route('removeCity', ['city_id' => $cityId]) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="flex items-center px-4 py-2 font-medium tracking-wide text-white capitalize transition-colors duration-300 transform bg-red-600 rounded-lg hover:bg-red-500 focus:outline-none focus:ring focus:ring-red-300 focus:ring-opacity-80">
                                        <span class="mx-1">Remove from Saved City</span>
                                    </button>
                                </form>
                            @else
                                <form method="POST" action="{{ route('savecity') }}">
                                    @csrf
                                    <input type="hidden" name="name" value="{{ strtolower($weather['name']) }}">
                                    <button type="submit"
                                        class="flex items-center px-4 py-2 font-medium tracking-wide text-white capitalize transition-colors duration-300 transform bg-blue-600 rounded-lg hover:bg-blue-500 focus:outline-none focus:ring focus:ring-blue-300 focus:ring-opacity-80">
                                        <span class="mx-1">Save City</span>
                                    </button>
                                </form>
                            @endif
                        </div>
                    @elseif (isset($error))
                        <p>{{ $error }}</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
