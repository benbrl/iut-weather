@extends('layouts.layout')

@section('title', 'Accueil')

@section('content')
    <div class="container px-6 py-16 mx-auto text-center">
        <div class="max-w-lg mx-auto">
            <h1 class="text-3xl font-semibold text-gray-800 dark:text-white lg:text-4xl">IUT Weather App
            </h1>
            <p class="mt-6 text-gray-500 dark:text-gray-300">Application météo utilisant Laravel et l'API d'OpenWeathermap.
            </p>
            <button
                class="px-5 py-2 mt-6 text-sm font-medium leading-5 text-center text-white capitalize bg-blue-600 rounded-lg hover:bg-blue-500 lg:mx-0 lg:w-auto focus:outline-none">
                <a href="{{ route('login') }}">Login</a>
            </button>
        </div>
    </div>
@endsection
