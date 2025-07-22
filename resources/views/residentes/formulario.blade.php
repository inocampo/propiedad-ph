<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulario de Residentes - Conjunto Residencial Gualanday</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="{{ asset('css/formulario.css') }}">
</head>
<body class="bg-gray-100">
    <div class="min-h-screen py-10">
        <div class="container mx-auto px-4">
            <div class="max-w-4xl mx-auto bg-white rounded-lg shadow-lg p-8">
                <div class="text-center mb-8">
                    <h1 class="text-3xl font-bold text-gray-800">Formulario de Registro</h1>
                    <p class="text-gray-600 mt-2">Apartamento: {{ $apartamento ? $apartamento->number : request()->route('number') }}</p>
                    <p class="text-gray-600 mt-2"><span class="text-red-600">*</span> Campos obligatorios</p>
                </div>

                @if ($errors->any())
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('residentes.guardar') }}" method="POST">
                    @csrf
                    <input type="hidden" name="number" value="{{ $apartamento ? $apartamento->number : request()->route('number') }}">
                    
                    <!-- Secciones con Acordeones -->
                    <div class="space-y-4">
                        <!-- Acordeón de Información General -->
                        <div class="accordion-section mb-4 border rounded-lg overflow-hidden bg-white shadow-sm">
                            <div class="accordion-header cursor-pointer bg-blue-50 px-4 py-3 flex justify-between items-center" id="info-general-header">
                                <div class="flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                    <span class="font-medium text-lg">Información General</span>
                                </div>
                                <button type="button" class="accordion-toggle focus:outline-none" onclick="toggleAccordion('info-general-header')">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 transform transition-transform duration-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                    </svg>
                                </button>
                            </div>
                            <div class="accordion-body p-4" id="info-general-body">
                                @include('residentes.partials.info-general-form')
                            </div>
                        </div>

                        <!-- Acordeón de Propietarios -->
                        <div class="accordion-section mb-4 border rounded-lg overflow-hidden bg-white shadow-sm">
                            <div class="accordion-header cursor-pointer bg-blue-50 px-4 py-3 flex justify-between items-center" id="propietarios-header">
                                <div class="flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                    </svg>
                                    <span class="font-medium text-lg">Propietarios</span>
                                    <span class="text-sm bg-blue-100 text-blue-800 py-1 px-3 rounded-full ml-3" id="owners-counter">0</span>
                                </div>
                                <button type="button" class="accordion-toggle focus:outline-none">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 transform transition-transform duration-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                    </svg>
                                </button>
                            </div>
                            <div class="accordion-body p-4 hidden" id="propietarios-body">
                                @include('residentes.partials.owners-table')
                            </div>
                        </div>
                        
                        <!-- Acordeón de Residentes -->
                        <div class="accordion-section mb-4 border rounded-lg overflow-hidden bg-white shadow-sm">
                            <div class="accordion-header cursor-pointer bg-blue-50 px-4 py-3 flex justify-between items-center" id="residentes-header">
                                <div class="flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                    </svg>
                                    <span class="font-medium text-lg">Residentes</span>
                                    <span class="text-sm bg-blue-100 text-blue-800 py-1 px-3 rounded-full ml-3" id="residents-counter">0</span>
                                </div>
                                <button type="button" class="accordion-toggle focus:outline-none">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 transform transition-transform duration-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                    </svg>
                                </button>
                            </div>
                            <div class="accordion-body p-4 hidden" id="residentes-body">
                                @include('residentes.partials.residents-table')
                            </div>
                        </div>
                        
                        <!-- Acordeón de Menores de Edad -->
                        <div class="accordion-section mb-4 border rounded-lg overflow-hidden bg-white shadow-sm">
                            <div class="accordion-header cursor-pointer bg-blue-50 px-4 py-3 flex justify-between items-center" id="menores-header">
                                <div class="flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                    </svg>
                                    <span class="font-medium text-lg">Menores de Edad</span>
                                    <span class="text-sm bg-blue-100 text-blue-800 py-1 px-3 rounded-full ml-3" id="minors-counter">0</span>
                                </div>
                                <button type="button" class="accordion-toggle focus:outline-none">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 transform transition-transform duration-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                    </svg>
                                </button>
                            </div>
                            <div class="accordion-body p-4 hidden" id="menores-body">
                                @include('residentes.partials.minors-table')
                            </div>
                        </div>
                        
                        <!-- Acordeón de Vehículos -->
                        <div class="accordion-section mb-4 border rounded-lg overflow-hidden bg-white shadow-sm">
                            <div class="accordion-header cursor-pointer bg-blue-50 px-4 py-3 flex justify-between items-center" id="vehiculos-header">
                                <div class="flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m-4 6H4m0 0l4 4m-4-4l4-4" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 15a4 4 0 004 4h9a5 5 0 10-.1-9.999 5.002 5.002 0 10-9.78 2.096A4.001 4.001 0 003 15z" />
                                    </svg>
                                    <span class="font-medium text-lg">Vehículos</span>
                                    <span class="text-sm bg-blue-100 text-blue-800 py-1 px-3 rounded-full ml-3" id="vehicles-counter">0</span>
                                </div>
                                <button type="button" class="accordion-toggle focus:outline-none" onclick="toggleAccordion('vehiculos-header')">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 transform transition-transform duration-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                    </svg>
                                </button>
                            </div>
                            <div class="accordion-body p-4 hidden" id="vehiculos-body">
                                @include('residentes.partials.vehicles-table')
                            </div>
                        </div>
                        
                        <!-- Acordeón de Mascotas -->
                        <div class="accordion-section mb-4 border rounded-lg overflow-hidden bg-white shadow-sm">
                            <div class="accordion-header cursor-pointer bg-blue-50 px-4 py-3 flex justify-between items-center" id="mascotas-header">
                                <div class="flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                    </svg>
                                    <span class="font-medium text-lg">Mascotas</span>
                                    <span class="text-sm bg-blue-100 text-blue-800 py-1 px-3 rounded-full ml-3" id="pets-counter">0</span>
                                </div>
                                <button type="button" class="accordion-toggle focus:outline-none" onclick="toggleAccordion('mascotas-header')">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 transform transition-transform duration-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                    </svg>
                                </button>
                            </div>
                            <div class="accordion-body p-4 hidden" id="mascotas-body">
                                @include('residentes.partials.pets-table')
                            </div>
                        </div>
                    </div>
                    
                    <!-- Templates -->
                    @include('residentes.templates.owner-template')
                    @include('residentes.templates.resident-template')
                    @include('residentes.templates.minor-template')
                    @include('residentes.templates.vehicle-template')
                    @include('residentes.templates.pet-template')
                    
                    <!-- Botón de Envío -->
                    <div class="flex items-center justify-center mt-8">
                        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-lg focus:outline-none focus:shadow-outline">
                            Guardar Información
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Scripts Modulares -->
    <script>
        // Datos del apartamento para JavaScript
        window.apartamentoData = {!! json_encode($apartamento ?? null) !!};
        window.relationshipsData = {!! json_encode($relationships ?? []) !!};
        window.brandsData = {!! json_encode($brands ?? []) !!};
        window.colorsData = {!! json_encode($colors ?? []) !!};
        window.breedsData = {!! json_encode($breeds ?? []) !!};
    </script>
    
    <!-- Core: Utilidades y funciones base -->
    <script src="{{ asset('js/core/utilities.js') }}"></script>
    
    <!-- Módulos independientes -->
    <script src="{{ asset('js/modules/owners.js') }}"></script>
    <script src="{{ asset('js/modules/residents.js') }}"></script>
    <script src="{{ asset('js/modules/minors.js') }}"></script>
    <script src="{{ asset('js/modules/vehicles.js') }}"></script>
    <script src="{{ asset('js/modules/pets.js') }}"></script>
    
    <!-- Core: Funcionalidades especiales y cargador de datos -->
    <script src="{{ asset('js/core/special-features.js') }}"></script>
    <script src="{{ asset('js/core/data-loader.js') }}"></script>
    
    <!-- Aplicación principal -->
    <script src="{{ asset('js/app.js') }}"></script>
</body>
</html>