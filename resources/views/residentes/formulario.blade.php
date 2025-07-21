<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulario de Residentes - Conjunto Residencial Gualanday</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* Estilos para controlar los anchos de las columnas en las tablas */
        @media (min-width: 768px) {
            /* Tabla de propietarios */
            #owners-container td:nth-child(1),
            #owners-container th:nth-child(1) { width: 30%; }
            #owners-container td:nth-child(2),
            #owners-container th:nth-child(2) { width: 20%; }
            #owners-container td:nth-child(3),
            #owners-container th:nth-child(3) { width: 20%; }
            #owners-container td:nth-child(4),
            #owners-container th:nth-child(4) { width: 30%; }
            #owners-container td:nth-child(5),
            #owners-container th:nth-child(5) { width: 10%; }
            
            /* Tabla de residentes */
            #residents-container td:nth-child(1),
            #residents-container th:nth-child(1) { width: 30%; }
            #residents-container td:nth-child(2),
            #residents-container th:nth-child(2) { width: 20%; }
            #residents-container td:nth-child(3),
            #residents-container th:nth-child(3) { width: 20%; }
            #residents-container td:nth-child(4),
            #residents-container th:nth-child(4) { width: 30%; }
            #residents-container td:nth-child(5),
            #residents-container th:nth-child(5) { width: 10%; }
        }
        
        /* Reducir el tamaño de fuente y padding en los controles de los repeaters */
        #owners-container input, #owners-container select,
        #residents-container input, #residents-container select,
        #minors-container input, #minors-container select {
            font-size: 0.8rem; /* text-sm */
            padding-top: 0.25rem; /* py-1 */
            padding-bottom: 0.25rem;
        }
    </style>
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
                                <!-- Información del Residente Principal -->
                                <div class="mb-6">
                                    <h3 class="text-lg font-semibold text-gray-800 mb-3 pb-2 border-b">Información del Residente Principal</h3>
                                    
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div>
                                            <label for="resident_name" class="block text-gray-700 text-sm font-bold mb-2">Nombre Completo: <span class="text-red-600">*</span></label>
                                            <input type="text" id="resident_name" name="resident_name" 
                                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline uppercase"
                                                value="{{ $apartamento ? $apartamento->resident_name : old('resident_name') }}" required>
                                        </div>
                                        
                                        <div>
                                            <label for="resident_document" class="block text-gray-700 text-sm font-bold mb-2">Cédula: <span class="text-red-600">*</span></label>
                                            <input type="text" id="resident_document" name="resident_document" 
                                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                                value="{{ $apartamento ? $apartamento->resident_document : old('resident_document') }}" required>
                                        </div>
                                        
                                        <div>
                                            <label for="resident_phone" class="block text-gray-700 text-sm font-bold mb-2">Celular: <span class="text-red-600">*</span></label>
                                            <input type="tel" id="resident_phone" name="resident_phone" 
                                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                                value="{{ $apartamento ? $apartamento->resident_phone : old('resident_phone') }}" required>
                                        </div>
                                        
                                        <div>
                                            <label for="resident_email" class="block text-gray-700 text-sm font-bold mb-2">Email: <span class="text-red-600">*</span></label>
                                            <input type="email" id="resident_email" name="resident_email" 
                                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline lowercase"
                                                value="{{ $apartamento ? $apartamento->resident_email : old('resident_email') }}" required>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Información Adicional -->
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-800 mb-3 pb-2 border-b">Información Adicional</h3>
                                    
                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                                        <div class="flex items-center">
                                            <input type="checkbox" id="received_manual" name="received_manual" value="1" 
                                                class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500"
                                                {{ $apartamento && $apartamento->received_manual ? 'checked' : '' }}>
                                            <label for="received_manual" class="ml-2 text-sm font-medium text-gray-700">¿Recibió el manual de convivencia?</label>
                                        </div>
                                        
                                        <div class="flex items-center">
                                            <input type="checkbox" id="has_bicycles" name="has_bicycles" value="1" 
                                                class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500"
                                                {{ $apartamento && $apartamento->has_bicycles ? 'checked' : '' }}
                                                onchange="toggleBicyclesCount()">
                                            <label for="has_bicycles" class="ml-2 text-sm font-medium text-gray-700">¿Tienen bicicletas?</label>
                                        </div>
                                        
                                        <div id="bicycles_count_container" class="{{ ($apartamento && $apartamento->has_bicycles) ? '' : 'hidden' }}">
                                            <label for="bicycles_count" class="block text-gray-700 text-sm font-bold mb-2">Cantidad de bicicletas:</label>
                                            <input type="number" id="bicycles_count" name="bicycles_count" min="1" 
                                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                                value="{{ $apartamento ? $apartamento->bicycles_count : old('bicycles_count', 1) }}">
                                        </div>
                                    </div>
                                    
                                    <div>
                                        <label for="observations" class="block text-gray-700 text-sm font-bold mb-2">Observaciones:</label>
                                        <textarea id="observations" name="observations" rows="3" 
                                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">{{ $apartamento ? $apartamento->observations : old('observations') }}</textarea>
                                    </div>
                                </div>
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
                                <!-- Tabla de propietarios -->
                                <div class="overflow-x-auto">
                                    <table class="min-w-full bg-white">
                                        <thead class="hidden md:table-header-group">
                                            <tr class="bg-gray-100 text-gray-600 uppercase text-sm">
                                                <th class="py-3 px-4 text-left w-[30%]">Nombre Completo</th>
                                                <th class="py-3 px-4 text-left w-[20%]">Cédula</th>
                                                <th class="py-3 px-4 text-left w-[20%]">Celular</th>
                                                <th class="py-3 px-4 text-left w-[30%]">Email</th>
                                                <th class="py-3 px-2 text-center w-10"></th>
                                            </tr>
                                        </thead>
                                        <tbody id="owners-container" class="block md:table-row-group">
                                            <!-- Los propietarios se agregarán aquí dinámicamente -->
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <td colspan="5" class="py-3 px-4">
                                                    <button type="button" id="add-owner-btn" class="flex items-center justify-center w-full md:w-auto bg-blue-500 hover:bg-blue-600 text-white font-medium py-1 px-2 rounded-lg transition-colors">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                                                            <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
                                                        </svg>
                                                        Agregar Propietario
                                                    </button>
                                                </td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
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
                                <!-- Tabla de residentes -->
                                <div class="overflow-x-auto">
                                    <table class="min-w-full bg-white">
                                        <thead class="hidden md:table-header-group">
                                            <tr class="bg-gray-100 text-gray-600 uppercase text-sm">
                                                <th class="py-3 px-4 text-left">Nombre Completo</th>
                                                <th class="py-3 px-4 text-left">Cédula</th>
                                                <th class="py-3 px-4 text-left">Celular</th>
                                                <th class="py-3 px-4 text-left">Parentesco</th>
                                                <th class="py-3 px-2 text-center w-10"></th>
                                            </tr>
                                        </thead>
                                        <tbody id="residents-container" class="block md:table-row-group">
                                            <!-- Los residentes se agregarán aquí dinámicamente -->
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <td colspan="5" class="py-3 px-4">
                                                    <button type="button" id="add-resident-btn" class="flex items-center justify-center w-full md:w-auto bg-blue-500 hover:bg-blue-600 text-white font-medium py-1 px-2 rounded-lg transition-colors">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                                                            <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
                                                        </svg>
                                                        Agregar Residente
                                                    </button>
                                                </td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Acordeón de Menores de Edad a Cargo -->
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
                                <!-- Tabla de menores de edad -->
                                <div class="overflow-x-auto">
                                    <table class="min-w-full bg-white">
                                        <thead class="hidden md:table-header-group">
                                            <tr class="bg-gray-100 text-gray-600 uppercase text-sm">
                                                <th class="py-3 px-4 text-left">Nombre</th>
                                                <th class="py-3 px-4 text-left">Edad</th>
                                                <th class="py-3 px-4 text-left">Género</th>
                                                <th class="py-3 px-2 text-center w-10"></th>
                                            </tr>
                                        </thead>
                                        <tbody id="minors-container" class="block md:table-row-group">
                                            <!-- Los menores se agregarán aquí dinámicamente -->
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <td colspan="4" class="py-3 px-4">
                                                    <button type="button" id="add-minor-btn" class="flex items-center justify-center w-full md:w-auto bg-blue-500 hover:bg-blue-600 text-white font-medium py-1 px-2 rounded-lg transition-colors">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                                                            <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
                                                        </svg>
                                                        Agregar Menor de Edad
                                                    </button>
                                                </td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                                <p class="text-sm text-gray-500 mt-2">Si no hay menores, haz clic en el botón para añadir el primero.</p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Template para nuevos propietarios (oculto) -->
                    <template id="owner-template">
                        <tr class="owner-item border-b hover:bg-gray-50 block md:table-row mb-6 md:mb-0">
                            <td class="py-1 px-2 block md:table-cell before:content-['Nombre_Completo:_*'] before:font-bold before:text-gray-700 before:block md:before:hidden">
                                <input type="text" name="owners[INDEX][name]" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline uppercase owner-input-name" required>
                            </td>
                            <td class="py-1 px-2 block md:table-cell before:content-['Cédula:_*'] before:font-bold before:text-gray-700 before:block md:before:hidden">
                                <input type="text" name="owners[INDEX][document]" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                            </td>
                            <td class="py-1 px-2 block md:table-cell before:content-['Celular:'] before:font-bold before:text-gray-700 before:block md:before:hidden">
                                <input type="tel" name="owners[INDEX][phone]" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                            </td>
                            <td class="py-1 px-2 block md:table-cell md:w-[30%] before:content-['Email:'] before:font-bold before:text-gray-700 before:block md:before:hidden">
                                <input type="email" name="owners[INDEX][email]" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline lowercase">
                            </td>
                            <td class="py-1 px-2 text-center block md:table-cell">
                                <button type="button" class="remove-owner-btn w-full md:w-auto bg-red-600 hover:bg-red-700 md:bg-transparent md:hover:bg-transparent text-white md:text-red-600 font-medium py-1 px-2 rounded-lg focus:outline-none transition-colors duration-200 flex items-center justify-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline mr-1 md:mr-0" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                                    </svg>
                                    <span class="md:hidden">Eliminar</span>
                                </button>
                            </td>
                        </tr>
                    </template>
                    
                    <!-- Template para nuevos residentes (oculto) -->
                    <template id="resident-template">
                        <tr class="resident-item border-b hover:bg-gray-50 block md:table-row mb-6 md:mb-0">
                            <td class="py-1 px-2 block md:table-cell before:content-['Nombre_Completo:_*'] before:font-bold before:text-gray-700 before:block md:before:hidden">
                                <input type="text" name="residents[INDEX][name]" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline uppercase resident-input-name" required>
                            </td>
                            <td class="py-1 px-2 block md:table-cell before:content-['Cédula:_*'] before:font-bold before:text-gray-700 before:block md:before:hidden">
                                <input type="text" name="residents[INDEX][document]" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                            </td>
                            <td class="py-1 px-2 block md:table-cell before:content-['Celular:'] before:font-bold before:text-gray-700 before:block md:before:hidden">
                                <input type="tel" name="residents[INDEX][phone]" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                            </td>
                            <td class="py-1 px-2 block md:table-cell before:content-['Parentesco:'] before:font-bold before:text-gray-700 before:block md:before:hidden">
                                <select name="residents[INDEX][relationship_id]" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                    <option value="">Seleccionar...</option>
                                    @foreach($relationships as $relationship)
                                        <option value="{{ $relationship->id }}">{{ $relationship->name }}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td class="py-1 px-2 text-center block md:table-cell">
                                <button type="button" class="remove-resident-btn w-full md:w-auto bg-red-600 hover:bg-red-700 md:bg-transparent md:hover:bg-transparent text-white md:text-red-600 font-medium py-1 px-2 rounded-lg focus:outline-none transition-colors duration-200 flex items-center justify-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline mr-1 md:mr-0" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                                    </svg>
                                    <span class="md:hidden">Eliminar</span>
                                </button>
                            </td>
                        </tr>
                    </template>
                    
                    <!-- Template para nuevos menores de edad (oculto) -->
                    <template id="minor-template">
                        <tr class="minor-item border-b hover:bg-gray-50 block md:table-row mb-6 md:mb-0">
                            <td class="py-1 px-2 block md:table-cell before:content-['Nombre:_*'] before:font-bold before:text-gray-700 before:block md:before:hidden">
                                <input type="text" name="minors[INDEX][name]" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline uppercase minor-input-name" required>
                            </td>
                            <td class="py-1 px-2 block md:table-cell before:content-['Edad:_*'] before:font-bold before:text-gray-700 before:block md:before:hidden">
                                <input type="number" name="minors[INDEX][age]" min="0" max="17" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                            </td>
                            <td class="py-1 px-2 block md:table-cell before:content-['Género:_*'] before:font-bold before:text-gray-700 before:block md:before:hidden">
                                <select name="minors[INDEX][gender]" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                                    <option value="">Seleccionar...</option>
                                    <option value="niño">Niño</option>
                                    <option value="niña">Niña</option>
                                </select>
                            </td>
                            <td class="py-1 px-2 text-center block md:table-cell">
                                <button type="button" class="remove-minor-btn w-full md:w-auto bg-red-600 hover:bg-red-700 md:bg-transparent md:hover:bg-transparent text-white md:text-red-600 font-medium py-1 px-2 rounded-lg focus:outline-none transition-colors duration-200 flex items-center justify-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 inline mr-1 md:mr-0" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                                    </svg>
                                    <span class="md:hidden">Eliminar</span>
                                </button>
                            </td>
                        </tr>
                    </template>
                    
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

    <script>
        // Función para manejar la visibilidad del campo de bicicletas
        function toggleBicyclesCount() {
            const hasBicycles = document.getElementById('has_bicycles').checked;
            const bicyclesCountContainer = document.getElementById('bicycles_count_container');
            
            if (hasBicycles) {
                bicyclesCountContainer.classList.remove('hidden');
            } else {
                bicyclesCountContainer.classList.add('hidden');
            }
        }

        // Variables para contar propietarios y residentes
        let ownerCount = 0;
        let residentCount = 0;
        let minorCount = 0;
        
        // Función para contar elementos reales en el DOM
        function recountElements() {
            // Contar propietarios reales en el DOM
            const ownerItems = document.querySelectorAll('#owners-container .owner-item');
            ownerCount = ownerItems.length;
            
            // Contar residentes reales en el DOM
            const residentItems = document.querySelectorAll('#residents-container .resident-item');
            residentCount = residentItems.length;
            
            // Contar menores de edad reales en el DOM
            const minorItems = document.querySelectorAll('#minors-container .minor-item');
            minorCount = minorItems.length;
            
            // Actualizar contadores en la UI
            updateCounters();
        }

        // Función para actualizar contadores
        function updateCounters() {
            document.getElementById('owners-counter').textContent = ownerCount;
            document.getElementById('residents-counter').textContent = residentCount;
            document.getElementById('minors-counter').textContent = minorCount;
        }

        // Función para agregar un nuevo propietario
        function addOwner(index = null) {
            const newIndex = index !== null ? index : ownerCount;
            console.log(`Agregando propietario con índice ${newIndex}. Contador actual: ${ownerCount}`);

            // Obtener el template
            const template = document.getElementById('owner-template');
            const clone = document.importNode(template.content, true);

            // Reemplazar el índice placeholder en todos los inputs y selects
            const inputs = clone.querySelectorAll('input, select');
            inputs.forEach(input => {
                const originalName = input.name;
                input.name = input.name.replace('INDEX', newIndex);
                console.log(`Campo renombrado de ${originalName} a ${input.name}`);
                
                // Agregar un atributo data-index para facilitar la depuración
                input.setAttribute('data-index', newIndex);
            });

            // Agregar evento para eliminar propietario
            const removeBtn = clone.querySelector('.remove-owner-btn');
            removeBtn.addEventListener('click', function() {
                const ownerRow = this.closest('.owner-item');
                ownerRow.remove();
                // Usar recountElements para actualizar el contador basado en elementos reales
                recountElements();
                renumberOwners();
            });

            // Agregar el propietario al contenedor
            const container = document.getElementById('owners-container');
            container.appendChild(clone);
            console.log(`Propietario ${newIndex} agregado al DOM. Contenedor tiene ${container.children.length} hijos`);

            // Actualizar contadores basados en elementos reales en el DOM
            recountElements();
            console.log(`Propietario agregado. Nuevo contador: ${ownerCount}`);
            
            // Devolver el índice del propietario agregado para referencia
            return newIndex;
            
            // Si estamos agregando un nuevo propietario manualmente, abrir acordeón y enfocar
            if (index === null) {
                const propietariosBody = document.getElementById('propietarios-body');
                if (propietariosBody.classList.contains('hidden')) {
                    toggleAccordion('propietarios-header');
                }
                
                // Enfocar el campo de nombre del nuevo propietario
                const ownerRows = document.querySelectorAll('#owners-container .owner-item');
                const lastOwnerRow = ownerRows[ownerRows.length - 1];
                const lastOwnerNameInput = lastOwnerRow.querySelector('.owner-input-name');
                lastOwnerNameInput.focus();
            }
        }
        
        // Función para renumerar propietarios
        function renumberOwners() {
            const ownerRows = document.querySelectorAll('#owners-container .owner-item');
            ownerRows.forEach((row, index) => {
                // Renumerar los inputs
                const inputs = row.querySelectorAll('input');
                inputs.forEach(input => {
                    const nameParts = input.name.split('[');
                    if (nameParts.length > 1) {
                        input.name = `${nameParts[0]}[${index}]${input.name.substring(input.name.indexOf(']') + 1)}`;
                    }
                });
            });
        }
        
        // Función para agregar un residente
        function addResident(index = null) {
            const newIndex = index !== null ? index : residentCount;
            console.log(`Agregando residente con índice ${newIndex}. Contador actual: ${residentCount}`);
            
            // Obtener el template
            const template = document.getElementById('resident-template');
            const clone = document.importNode(template.content, true);
            
            // Reemplazar el índice placeholder en todos los inputs y selects
            const inputs = clone.querySelectorAll('input, select');
            inputs.forEach(input => {
                const originalName = input.name;
                input.name = input.name.replace('INDEX', newIndex);
                console.log(`Campo renombrado de ${originalName} a ${input.name}`);
                
                // Agregar un atributo data-index para facilitar la depuración
                input.setAttribute('data-index', newIndex);
            });
            
            // Agregar evento para eliminar residente
            const removeBtn = clone.querySelector('.remove-resident-btn');
            removeBtn.addEventListener('click', function() {
                const residentRow = this.closest('.resident-item');
                residentRow.remove();
                // Usar recountElements para actualizar el contador basado en elementos reales
                recountElements();
                renumberResidents();
            });
            
            // Agregar el residente al contenedor
            const container = document.getElementById('residents-container');
            container.appendChild(clone);
            console.log(`Residente ${newIndex} agregado al DOM. Contenedor tiene ${container.children.length} hijos`);
            
            // Actualizar contadores basados en elementos reales en el DOM
            recountElements();
            console.log(`Residente agregado. Nuevo contador: ${residentCount}`);
            
            // Devolver el índice del residente agregado para referencia
            return newIndex;
            
            // Si estamos agregando un nuevo residente manualmente, abrir acordeón y enfocar
            if (index === null) {
                const residentesBody = document.getElementById('residentes-body');
                if (residentesBody.classList.contains('hidden')) {
                    toggleAccordion('residentes-header');
                }
                
                // Enfocar el campo de nombre del nuevo residente
                const residentRows = document.querySelectorAll('#residents-container .resident-item');
                const lastResidentRow = residentRows[residentRows.length - 1];
                const lastResidentNameInput = lastResidentRow.querySelector('.resident-input-name');
                lastResidentNameInput.focus();
            }
        }
        
        // Función para renumerar residentes
        function renumberResidents() {
            const residentRows = document.querySelectorAll('#residents-container .resident-item');
            residentRows.forEach((row, index) => {
                // Renumerar los inputs y selects
                const inputs = row.querySelectorAll('input, select');
                inputs.forEach(input => {
                    const nameParts = input.name.split('[');
                    if (nameParts.length > 1) {
                        input.name = `${nameParts[0]}[${index}]${input.name.substring(input.name.indexOf(']') + 1)}`;
                    }
                });
            });
        }
        
        // Función para agregar un menor de edad
        function addMinor(index = null) {
            const newIndex = index !== null ? index : minorCount;
            console.log(`Agregando menor con índice ${newIndex}. Contador actual: ${minorCount}`);
            
            // Obtener el template
            const template = document.getElementById('minor-template');
            const clone = document.importNode(template.content, true);
            
            // Reemplazar el índice placeholder en todos los inputs y selects
            const inputs = clone.querySelectorAll('input, select');
            inputs.forEach(input => {
                const originalName = input.name;
                input.name = input.name.replace('INDEX', newIndex);
                console.log(`Campo renombrado de ${originalName} a ${input.name}`);
                
                // Agregar un atributo data-index para facilitar la depuración
                input.setAttribute('data-index', newIndex);
            });
            
            // Agregar evento para eliminar menor
            const removeBtn = clone.querySelector('.remove-minor-btn');
            removeBtn.addEventListener('click', function() {
                const minorRow = this.closest('.minor-item');
                minorRow.remove();
                // Usar recountElements para actualizar el contador basado en elementos reales
                recountElements();
                renumberMinors();
            });
            
            // Agregar el menor al contenedor
            const container = document.getElementById('minors-container');
            container.appendChild(clone);
            console.log(`Menor ${newIndex} agregado al DOM. Contenedor tiene ${container.children.length} hijos`);
            
            // Actualizar contadores basados en elementos reales en el DOM
            recountElements();
            console.log(`Menor agregado. Nuevo contador: ${minorCount}`);
            
            // Devolver el índice del menor agregado para referencia
            return newIndex;
            
            // Si estamos agregando un nuevo menor manualmente, abrir acordeón y enfocar
            if (index === null) {
                const menoresBody = document.getElementById('menores-body');
                if (menoresBody.classList.contains('hidden')) {
                    toggleAccordion('menores-header');
                }
                
                // Enfocar el campo de nombre del nuevo menor
                const minorRows = document.querySelectorAll('#minors-container .minor-item');
                const lastMinorRow = minorRows[minorRows.length - 1];
                const lastMinorNameInput = lastMinorRow.querySelector('.minor-input-name');
                lastMinorNameInput.focus();
            }
        }
        
        // Función para renumerar menores
        function renumberMinors() {
            const minorRows = document.querySelectorAll('#minors-container .minor-item');
            minorRows.forEach((row, index) => {
                // Renumerar los inputs y selects
                const inputs = row.querySelectorAll('input, select');
                inputs.forEach(input => {
                    const nameParts = input.name.split('[');
                    if (nameParts.length > 1) {
                        input.name = `${nameParts[0]}[${index}]${input.name.substring(input.name.indexOf(']') + 1)}`;
                    }
                });
            });
        }
        
        // Función para alternar acordeones
        function toggleAccordion(headerId) {
            const header = document.getElementById(headerId);
            const body = header.nextElementSibling;
            const icon = header.querySelector('.accordion-toggle svg');
            const isOpening = body.classList.contains('hidden');
            
            // Si estamos abriendo este acordeón, cerramos todos los demás primero
            if (isOpening) {
                // Cerrar todos los acordeones
                const allAccordionBodies = document.querySelectorAll('.accordion-body');
                const allAccordionIcons = document.querySelectorAll('.accordion-toggle svg');
                
                allAccordionBodies.forEach(accordionBody => {
                    accordionBody.classList.add('hidden');
                });
                
                allAccordionIcons.forEach(accordionIcon => {
                    accordionIcon.classList.remove('rotate-180');
                });
            }
            
            // Alternar el estado del acordeón actual
            body.classList.toggle('hidden');
            icon.classList.toggle('rotate-180');
        }
        
        // Función para cargar datos existentes
        function loadExistingData() {
            console.log('Iniciando carga de datos existentes');
            
            @if(isset($apartamento) && $apartamento)
                console.log('Apartamento encontrado:', @json($apartamento));
                
                // Verificar si el apartamento tiene propietarios
                @if(isset($apartamento->owners) && count($apartamento->owners) > 0)
                    console.log('Propietarios encontrados en el apartamento:', @json($apartamento->owners));
                    
                    // Cargar propietarios existentes
                    try {
                        // Obtener los datos de propietarios directamente del backend
                        const ownersData = [
                            @foreach($apartamento->owners as $index => $owner)
                                {
                                    id: {{ $owner->id }},
                                    name: "{{ $owner->name }}",
                                    document_number: "{{ $owner->document_number }}",
                                    phone_number: "{{ $owner->phone_number ?? '' }}",
                                    email: "{{ $owner->email ?? '' }}"
                                }@if(!$loop->last),@endif
                            @endforeach
                        ];
                        
                        console.log('Propietarios procesados:', ownersData);
                        
                        if (ownersData && ownersData.length > 0) {
                            console.log('Propietarios a cargar:', ownersData.length);
                            
                            // Limpiar contenedor de propietarios antes de cargar
                            document.getElementById('owners-container').innerHTML = '';
                            
                            ownersData.forEach((owner, index) => {
                                console.log(`Cargando propietario ${index}:`, owner);
                                
                                // Crear un nuevo propietario con el índice correcto y obtener su índice
                                const addedIndex = addOwner(index);
                                console.log(`Propietario añadido con índice ${addedIndex}, buscando elementos para cargar datos...`);
                                
                                // Obtener el propietario recién añadido - usamos data-index para mayor precisión
                                const ownerRows = document.querySelectorAll('#owners-container .owner-item');
                                console.log(`Total de filas de propietarios: ${ownerRows.length}`);
                                
                                // Obtener la última fila añadida
                                const currentOwnerRow = ownerRows[ownerRows.length - 1];
                                if (!currentOwnerRow) {
                                    console.error(`No se pudo encontrar la fila para el propietario ${index}`);
                                    return;
                                }
                                
                                try {
                                    // Imprimir todos los inputs en la fila para depuración
                                    const allInputs = currentOwnerRow.querySelectorAll('input');
                                    console.log(`Encontrados ${allInputs.length} inputs en la fila del propietario:`);
                                    allInputs.forEach(input => {
                                        console.log(`- Input: ${input.name}, tipo: ${input.type}`);
                                    });
                                    
                                    // Llenar los campos - usando selectores más robustos
                                    const nameInput = currentOwnerRow.querySelector('input[name^="owners["][name$="[name]"]');
                                    const documentInput = currentOwnerRow.querySelector('input[name^="owners["][name$="[document]"]');
                                    const phoneInput = currentOwnerRow.querySelector('input[name^="owners["][name$="[phone]"]');
                                    const emailInput = currentOwnerRow.querySelector('input[name^="owners["][name$="[email]"]');
                                    
                                    if (nameInput) {
                                        nameInput.value = owner.name || '';
                                        console.log(`Nombre del propietario ${index} establecido:`, nameInput.value);
                                    } else {
                                        console.error(`No se encontró el campo de nombre para el propietario ${index}`);
                                    }
                                    
                                    if (documentInput) {
                                        documentInput.value = owner.document_number || '';
                                        console.log(`Documento del propietario ${index} establecido:`, documentInput.value);
                                    } else {
                                        console.error(`No se encontró el campo de documento para el propietario ${index}`);
                                    }
                                    
                                    if (phoneInput) {
                                        phoneInput.value = owner.phone_number || '';
                                        console.log(`Teléfono del propietario ${index} establecido:`, phoneInput.value);
                                    } else {
                                        console.error(`No se encontró el campo de teléfono para el propietario ${index}`);
                                    }
                                    
                                    if (emailInput) {
                                        emailInput.value = owner.email || '';
                                        console.log(`Email del propietario ${index} establecido:`, emailInput.value);
                                    } else {
                                        console.error(`No se encontró el campo de email para el propietario ${index}`);
                                    }
                                    
                                    console.log('Propietario cargado correctamente');
                                } catch (e) {
                                    console.error('Error al llenar campos del propietario:', e);
                                }
                            });
                            
                            updateCounters();
                        } else {
                            console.log('No hay propietarios para cargar');
                        }
                    } catch (e) {
                        console.error('Error al cargar propietarios:', e);
                    }
                @else
                    console.log('El apartamento no tiene propietarios');
                @endif
                
                // Verificar si el apartamento tiene residentes
                @if(isset($apartamento->residents) && count($apartamento->residents) > 0)
                    console.log('Residentes encontrados en el apartamento:', @json($apartamento->residents));
                    
                    // Cargar residentes existentes
                    try {
                        // Obtener los datos de residentes directamente del backend
                        const residentsData = [
                            @foreach($apartamento->residents as $index => $resident)
                                {
                                    id: {{ $resident->id }},
                                    name: "{{ $resident->name }}",
                                    document_number: "{{ $resident->document_number }}",
                                    phone_number: "{{ $resident->phone_number ?? '' }}",
                                    relationship_id: {{ $resident->relationship_id ?? 'null' }}
                                }@if(!$loop->last),@endif
                            @endforeach
                        ];
                        
                        console.log('Residentes procesados:', residentsData);
                        
                        if (residentsData && residentsData.length > 0) {
                            console.log('Residentes a cargar:', residentsData.length);
                            
                            // Limpiar contenedor de residentes antes de cargar
                            document.getElementById('residents-container').innerHTML = '';
                            
                            residentsData.forEach((resident, index) => {
                                console.log(`Cargando residente ${index}:`, resident);
                                
                                // Crear un nuevo residente con el índice correcto y obtener su índice
                                const addedIndex = addResident(index);
                                console.log(`Residente añadido con índice ${addedIndex}, buscando elementos para cargar datos...`);
                                
                                // Obtener el residente recién añadido - usamos data-index para mayor precisión
                                const residentRows = document.querySelectorAll('#residents-container .resident-item');
                                console.log(`Total de filas de residentes: ${residentRows.length}`);
                                
                                // Obtener la última fila añadida
                                const currentResidentRow = residentRows[residentRows.length - 1];
                                if (!currentResidentRow) {
                                    console.error(`No se pudo encontrar la fila para el residente ${index}`);
                                    return;
                                }
                                
                                try {
                                    // Imprimir todos los inputs en la fila para depuración
                                    const allInputs = currentResidentRow.querySelectorAll('input, select');
                                    console.log(`Encontrados ${allInputs.length} campos en la fila del residente:`);
                                    allInputs.forEach(input => {
                                        console.log(`- Campo: ${input.name}, tipo: ${input.tagName.toLowerCase()}`);
                                    });
                                    
                                    // Llenar los campos - usando selectores más robustos
                                    const nameInput = currentResidentRow.querySelector('input[name^="residents["][name$="[name]"]');
                                    const documentInput = currentResidentRow.querySelector('input[name^="residents["][name$="[document]"]');
                                    const phoneInput = currentResidentRow.querySelector('input[name^="residents["][name$="[phone]"]');
                                    const parentescoSelect = currentResidentRow.querySelector('select[name^="residents["][name$="[relationship_id]"]');
                                    
                                    if (nameInput) {
                                        nameInput.value = resident.name || '';
                                        console.log(`Nombre del residente ${index} establecido:`, nameInput.value);
                                    } else {
                                        console.error(`No se encontró el campo de nombre para el residente ${index}`);
                                    }
                                    
                                    if (documentInput) {
                                        documentInput.value = resident.document_number || '';
                                        console.log(`Documento del residente ${index} establecido:`, documentInput.value);
                                    } else {
                                        console.error(`No se encontró el campo de documento para el residente ${index}`);
                                    }
                                    
                                    if (phoneInput) {
                                        phoneInput.value = resident.phone_number || '';
                                        console.log(`Teléfono del residente ${index} establecido:`, phoneInput.value);
                                    } else {
                                        console.error(`No se encontró el campo de teléfono para el residente ${index}`);
                                    }
                                    
                                    if (parentescoSelect && resident.relationship_id) {
                                        parentescoSelect.value = resident.relationship_id;
                                        console.log(`Parentesco seleccionado para ${resident.name}:`, resident.relationship_id);
                                    } else if (!parentescoSelect) {
                                        console.error(`No se encontró el campo de parentesco para el residente ${index}`);
                                    }
                                    
                                    console.log('Residente cargado correctamente');
                                } catch (e) {
                                    console.error('Error al llenar campos del residente:', e);
                                }
                            });
                            
                            updateCounters();
                        } else {
                            console.log('No hay residentes para cargar');
                        }
                    } catch (e) {
                        console.error('Error al cargar residentes:', e);
                    }
                @else
                    console.log('El apartamento no tiene residentes');
                @endif
                
                // Verificar si el apartamento tiene menores de edad
                @if(isset($apartamento->minors) && count($apartamento->minors) > 0)
                    console.log('Menores encontrados en el apartamento:', @json($apartamento->minors));
                    
                    // Cargar menores existentes
                    try {
                        // Obtener los datos de menores directamente del backend
                        const minorsData = [
                            @foreach($apartamento->minors as $index => $minor)
                                {
                                    id: {{ $minor->id }},
                                    name: "{{ $minor->name }}",
                                    age: "{{ $minor->age ?? '' }}",
                                    gender: "{{ $minor->gender ?? '' }}"
                                }@if(!$loop->last),@endif
                            @endforeach
                        ];
                        
                        console.log('Menores procesados:', minorsData);
                        
                        if (minorsData && minorsData.length > 0) {
                            console.log('Menores a cargar:', minorsData.length);
                            
                            // Limpiar contenedor de menores antes de cargar
                            document.getElementById('minors-container').innerHTML = '';
                            
                            minorsData.forEach((minor, index) => {
                                console.log(`Cargando menor ${index}:`, minor);
                                
                                // Crear un nuevo menor con el índice correcto y obtener su índice
                                const addedIndex = addMinor(index);
                                console.log(`Menor añadido con índice ${addedIndex}, buscando elementos para cargar datos...`);
                                
                                // Obtener el menor recién añadido
                                const minorRows = document.querySelectorAll('#minors-container .minor-item');
                                console.log(`Total de filas de menores: ${minorRows.length}`);
                                
                                // Obtener la última fila añadida
                                const currentMinorRow = minorRows[minorRows.length - 1];
                                if (!currentMinorRow) {
                                    console.error(`No se pudo encontrar la fila para el menor ${index}`);
                                    return;
                                }
                                
                                try {
                                    // Imprimir todos los inputs en la fila para depuración
                                    const allInputs = currentMinorRow.querySelectorAll('input, select');
                                    console.log(`Encontrados ${allInputs.length} campos en la fila del menor:`);
                                    allInputs.forEach(input => {
                                        console.log(`- Campo: ${input.name}, tipo: ${input.tagName.toLowerCase()}`);
                                    });
                                    
                                    // Llenar los campos - usando selectores más robustos
                                    const nameInput = currentMinorRow.querySelector('input[name^="minors["][name$="[name]"]');
                                    const ageInput = currentMinorRow.querySelector('input[name^="minors["][name$="[age]"]');
                                    const genderSelect = currentMinorRow.querySelector('select[name^="minors["][name$="[gender]"]');
                                    
                                    if (nameInput) {
                                        nameInput.value = minor.name || '';
                                        console.log(`Nombre del menor ${index} establecido:`, nameInput.value);
                                    } else {
                                        console.error(`No se encontró el campo de nombre para el menor ${index}`);
                                    }
                                    
                                    if (ageInput) {
                                        ageInput.value = minor.age || '';
                                        console.log(`Edad del menor ${index} establecida:`, ageInput.value);
                                    } else {
                                        console.error(`No se encontró el campo de edad para el menor ${index}`);
                                    }
                                    
                                    if (genderSelect && minor.gender) {
                                        genderSelect.value = minor.gender;
                                        console.log(`Género seleccionado para ${minor.name}:`, minor.gender);
                                    } else if (!genderSelect) {
                                        console.error(`No se encontró el campo de género para el menor ${index}`);
                                    }
                                    
                                    console.log('Menor cargado correctamente');
                                } catch (e) {
                                    console.error('Error al llenar campos del menor:', e);
                                }
                            });
                            
                            updateCounters();
                        } else {
                            console.log('No hay menores para cargar');
                        }
                    } catch (e) {
                        console.error('Error al cargar menores:', e);
                    }
                @else
                    console.log('El apartamento no tiene menores');
                @endif
            @else
                console.log('No hay apartamento para cargar');
            @endif
            
            // Actualizar contadores basados en elementos reales en el DOM
            console.log('Actualizando contadores finales...');
            recountElements();
            console.log('Contadores finales - Propietarios:', ownerCount, 'Residentes:', residentCount, 'Menores:', minorCount);
        }

        // Inicializar eventos cuando el DOM esté listo
        document.addEventListener('DOMContentLoaded', function() {
            console.log('DOM cargado, inicializando eventos...');
            
            // Inicializar botón para agregar propietario
            document.getElementById('add-owner-btn').addEventListener('click', function() {
                addOwner();
                // Enfocar el primer input del nuevo propietario
                const ownerRows = document.querySelectorAll('#owners-container .owner-item');
                const lastOwnerRow = ownerRows[ownerRows.length - 1];
                const firstInput = lastOwnerRow.querySelector('input');
                if (firstInput) firstInput.focus();
            });
            
            // Inicializar botón para agregar residente
            document.getElementById('add-resident-btn').addEventListener('click', function() {
                addResident();
                // Enfocar el primer input del nuevo residente
                const residentRows = document.querySelectorAll('#residents-container .resident-item');
                const lastResidentRow = residentRows[residentRows.length - 1];
                const firstInput = lastResidentRow.querySelector('input');
                if (firstInput) firstInput.focus();
            });
            
            // Inicializar botón para agregar menor de edad
            document.getElementById('add-minor-btn').addEventListener('click', function() {
                addMinor();
                // Enfocar el primer input del nuevo menor
                const minorRows = document.querySelectorAll('#minors-container .minor-item');
                const lastMinorRow = minorRows[minorRows.length - 1];
                const firstInput = lastMinorRow.querySelector('input');
                if (firstInput) firstInput.focus();
            });
            
            // Agregar eventos a los encabezados de acordeones
            const accordionHeaders = document.querySelectorAll('.accordion-header');
            accordionHeaders.forEach(header => {
                header.addEventListener('click', function() {
                    toggleAccordion(this.id);
                });
            });
            
            console.log('Esperando para cargar datos existentes...');
            
            // Cargar datos existentes si hay un apartamento con un retraso mayor
            // para asegurar que todos los elementos del DOM estén completamente inicializados
            setTimeout(() => {
                console.log('Intentando cargar datos existentes...');
                try {
                    loadExistingData();
                    console.log('Datos existentes cargados correctamente');
                } catch (e) {
                    console.error('Error al cargar datos existentes:', e);
                }
                
                // Verificar que los propietarios se hayan cargado correctamente
                const ownerRows = document.querySelectorAll('#owners-container .owner-item');
                console.log(`Después de cargar datos: ${ownerRows.length} propietarios en el DOM`);
            }, 300); // Retraso mayor para asegurar que el DOM esté completamente listo
            
            // Agregar evento para mostrar/ocultar el campo de bicicletas
            document.getElementById('has_bicycles').addEventListener('change', toggleBicyclesCount);
            
            // Función para mostrar/ocultar el campo de bicicletas
            function toggleBicyclesCount() {
                const hasBicycles = document.getElementById('has_bicycles').checked;
                const bicyclesCountContainer = document.getElementById('bicycles-count-container');
                
                if (hasBicycles) {
                    bicyclesCountContainer.classList.remove('hidden');
                } else {
                    bicyclesCountContainer.classList.add('hidden');
                    document.getElementById('bicycles_count').value = '';
                }
            }
            
            // Inicializar estado del campo de bicicletas
            toggleBicyclesCount();
            
            // Cargar datos existentes con un pequeño retraso para asegurar que el DOM esté completamente listo
            console.log('Programando carga de datos existentes...');
            setTimeout(function() {
                try {
                    console.log('Ejecutando loadExistingData con retraso...');
                    loadExistingData();
                    console.log('Carga de datos completada');
                } catch (error) {
                    console.error('Error al cargar datos existentes:', error);
                }
            }, 300);
        });
    </script>
</body>
</html>
