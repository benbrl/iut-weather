<x-app-layout>
    <div class="container mx-auto p-6">
        <h2 class="text-2xl font-semibold mb-6 text-gray-800">Villes enregistrées</h2>

        <!-- Messages de confirmation -->
        @if (session('success'))
            <div class="bg-green-500 text-white p-4 rounded-lg shadow-md mb-6">
                {{ session('success') }}
            </div>
        @elseif (session('error'))
            <div class="bg-red-500 text-white p-4 rounded-lg shadow-md mb-6">
                {{ session('error') }}
            </div>
        @endif

        <!-- Affichage des villes enregistrées -->
        @if ($cities->isEmpty())
            <p class="text-gray-600">Aucune ville enregistrée. Ajoutez-en une pour voir les informations météo.</p>
        @else
            <ul class="space-y-4">
                @foreach ($cities as $city)
                    <li class="bg-white p-4 rounded-lg shadow-md hover:bg-gray-50 transition">
                        <!-- Nom de la ville cliquable qui redirige vers la page météo -->
                        <a href="{{ route('weather') }}?city={{ $city->place->name }}"
                            class="font-semibold text-blue-600 hover:text-blue-800 text-lg">
                            {{ $city->place->name }}
                        </a>
                        <p>{{ $city }} </p>
                        <div class="mt-4 flex space-x-4">
                            <!-- Vérifie si la ville est marquée comme favorite -->
                            @if ($city->is_favorite)
                                <button
                                    class="px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-lg hover:bg-red-500 focus:outline-none">
                                    <a href="{{ route('remove_favorite', ['city_id' => $city->place->id]) }}">Supprimer
                                        des favoris</a>
                                </button>
                            @else
                                <button
                                    class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-500 focus:outline-none">
                                    <a href="{{ route('add_favorite', ['city_id' => $city->place->id]) }}">Ajouter aux
                                        favoris</a>
                                </button>
                            @endif

                            <!-- Bouton pour s'abonner aux rapports journaliers -->
                            <button
                                class="px-4 py-2 text-sm font-medium text-white bg-green-600 rounded-lg hover:bg-green-500 focus:outline-none">
                                <a href="{{ route('subscribe_report', ['city_id' => $city->place->id]) }}">Recevoir un
                                    rapport journalier</a>
                            </button>

                            <!-- Formulaire pour supprimer la ville enregistrée -->
                            <form action="{{ route('removeCity') }}" method="POST" class="inline-block">
                                @csrf
                                @method('DELETE')
                                <input type="hidden" name="city_id" value="{{ $city->place->id }}">
                                <button type="submit" class="text-red-600 hover:text-red-800 text-sm">
                                    Supprimer
                                </button>
                            </form>
                        </div>
                    </li>
                @endforeach
            </ul>
        @endif
    </div>
</x-app-layout>
