<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Weather') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h1>Conditions Météorologiques</h1>
                    <form method="GET" action="{{ route('weather') }}">
                        <div class="flex-grow">
                            <label for="city">Rechercher une ville</label>
                            <input id="city" name="city" type="text" required>
                            @error('city')
                                <div class="mt-1 text-red-500 text-sm">{{ $message }}</div>
                            @enderror
                        </div>

                        <button type="submit">Envoyer</button>
                    </form>

                    @if (isset($weather))
                        <h2>Ville : {{ $weather['name'] }}</h2>
                        <p>Température : {{ $weather['main']['temp'] }} °C</p>
                        <p>Conditions : {{ $weather['weather'][0]['description'] }}</p>
                        <p>Humidité : {{ $weather['main']['humidity'] }}%</p>
                        <div class="flex justify-center mt-4">
                            <button
                                class="flex items-center px-4 py-2 font-medium tracking-wide text-white capitalize transition-colors duration-300 transform bg-blue-600 rounded-lg hover:bg-blue-500 focus:outline-none focus:ring focus:ring-blue-300 focus:ring-opacity-80">

                                <span class="mx-1">Download CSV</span>
                            </button>
                            <a href="{{ route('forecast', ['city' => strtolower($weather['name'])]) }}"
                                class="px-4 py-2 font-medium tracking-wide text-white bg-blue-600 rounded-lg hover:bg-blue-500 focus:outline-none focus:ring focus:ring-blue-300 focus:ring-opacity-80">
                                Voir les prévisions
                            </a>
                            <button
                                class="flex items-center px-4 py-2 font-medium tracking-wide text-white capitalize transition-colors duration-300 transform bg-blue-600 rounded-lg hover:bg-blue-500 focus:outline-none focus:ring focus:ring-blue-300 focus:ring-opacity-80">

                                <span class="mx-1">ajouter aux favoris</span>
                            </button>
                        </div>
                    @elseif (isset($error))
                        <p>{{ $error }}</p>
                    @endif

                </div>

            </div>

        </div>

    </div>


</x-app-layout>
