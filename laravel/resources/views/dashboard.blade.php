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

                    <form method="GET" action="{{ route('weather') }}">
                        <div class="flex-grow">
                            <label for="city" class="">Rechercher une ville</label>
                            <input id="city" name="city" type="text" required>
                            @error('city')
                                <div class="mt-1 text-red-500 text-sm">{{ $message }}</div>
                            @enderror
                        </div>

                        <button type="submit">Envoyer</button>
                    </form>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
