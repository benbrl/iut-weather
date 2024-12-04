<x-app-layout>
    <div class="container mx-auto p-6">
        <h2 class="text-2xl font-semibold mb-6 text-gray-800">Saved Cities</h2>

        <!-- Messages de confirmation -->
        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 p-4 rounded-lg shadow-md mb-6">
                {{ session('success') }}
            </div>
        @elseif (session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 p-4 rounded-lg shadow-md mb-6">
                {{ session('error') }}
            </div>
        @endif

        @if ($cities->isEmpty())
            <p class="text-gray-600">No cities registered. Add one to view weather information.</p>
        @else
            <ul class="space-y-4">
                @foreach ($cities as $city)
                    <li class="bg-white p-4 rounded-lg shadow-md hover:bg-gray-50 transition">
                        <!-- Nom de la ville cliquable -->
                        <a href="{{ route('weather') }}?city={{ $city->place->name }}"
                            class="font-semibold text-blue-600 hover:text-blue-800 text-lg">
                            {{ $city->place->name }}
                        </a>
                        <div class="mt-4 flex flex-wrap gap-4">
                            <!-- Bouton pour ajouter ou retirer des favoris -->
                            @if ($city->is_favorite)
                                <form action="{{ route('remove_favorite', ['city_id' => $city->place->id]) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-lg hover:bg-red-500 focus:ring focus:ring-red-300">
                                        Remove from Favorites
                                    </button>
                                </form>
                            @else
                                <form action="{{ route('add_favorite', ['city_id' => $city->place->id]) }}" method="GET">
                                    @csrf
                                    <button type="submit"
                                        class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-500 focus:ring focus:ring-blue-300">
                                        Add to Favorites
                                    </button>
                                </form>
                            @endif


                            {{-- @if --}}
                            <!-- Bouton pour recevoir un rapport quotidien -->
                            <form action="{{ route('subscribe_report', ['city_id' => $city->place->id]) }}" method="GET">
                                <button type="submit"
                                    class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-500 focus:ring focus:ring-blue-300">
                                    Subscribe to Daily Reports
                                </button>
                            </form>
                            {{-- @else --}}
                            {{-- <form action="{{ route('subscribe_report', ['city_id' => $city->place->id]) }}" method="GET">
                                <button type="submit"
                                    class="px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-lg hover:bg-red-500 focus:ring focus:ring-red-300">
                                    unsubscribe to  Daily Reports
                                </button>
                            </form> --}}
                            {{-- @endif --}}
                            <!-- Bouton pour supprimer une ville -->
                            <form action="{{ route('removeCity') }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <input type="hidden" name="city_id" value="{{ $city->place->id }}">
                                <button type="submit"
                                    class="px-4 py-2 text-sm font-medium text-red-600 bg-red-100 border border-red-400 rounded-lg hover:bg-red-200 focus:ring focus:ring-red-300">
                                    Delete City
                                </button>
                            </form>
                        </div>
                    </li>
                @endforeach
            </ul>
        @endif
    </div>
</x-app-layout>
