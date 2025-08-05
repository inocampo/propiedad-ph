<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulario de Residentes - Conjunto Residencial Gualanday</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="min-h-screen py-10">
        <div class="container mx-auto px-4">
            <div class="max-w-4xl mx-auto bg-white rounded-lg shadow-lg p-8">
                <div class="text-center mb-8">
                    <h1 class="text-3xl font-bold text-gray-800">Registro de Residentes</h1>
                    <h2 class="text-gray-600 mt-2">Apartamento: {{ $apartamento ? $apartamento->number : request()->route('number') }}</h2>
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
                    
                    <!-- Sección de Información Principal -->
                    <div class="mb-8">
                        <h2 class="text-xl font-semibold text-gray-800 mb-4 pb-2 border-b">Información del Residente Principal</h2>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="resident_name" class="block text-gray-700 text-sm font-bold mb-2">Nombre Completo:</label>
                                <input type="text" id="resident_name" name="resident_name" 
                                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline uppercase"
                                    value="{{ $apartamento ? $apartamento->resident_name : old('resident_name') }}" required>
                            </div>
                            
                            <div>
                                <label for="resident_document" class="block text-gray-700 text-sm font-bold mb-2">Cédula:</label>
                                <input type="text" id="resident_document" name="resident_document" 
                                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                    value="{{ $apartamento ? $apartamento->resident_document : old('resident_document') }}" required>
                            </div>
                            
                            <div>
                                <label for="resident_phone" class="block text-gray-700 text-sm font-bold mb-2">Celular:</label>
                                <input type="tel" id="resident_phone" name="resident_phone" 
                                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                    value="{{ $apartamento ? $apartamento->resident_phone : old('resident_phone') }}" required>
                            </div>
                            
                            <div>
                                <label for="resident_email" class="block text-gray-700 text-sm font-bold mb-2">Email:</label>
                                <input type="email" id="resident_email" name="resident_email" 
                                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline lowercase"
                                    value="{{ $apartamento ? $apartamento->resident_email : old('resident_email') }}" required>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Sección de Información Adicional -->
                    <div class="mb-8">
                        <h2 class="text-xl font-semibold text-gray-800 mb-4 pb-2 border-b">Información Adicional</h2>
                        
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
                    
                    <!-- Secciones con Acordeones -->
                    <div class="mb-8">
                        <!-- Acordeón de Propietarios -->
                        <div class="accordion-section mb-4 border rounded-lg overflow-hidden bg-white shadow-sm">
                            <div class="accordion-header cursor-pointer bg-blue-50 px-4 py-3 flex justify-between items-center" id="propietarios-header">
                                <div class="flex items-center">
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
                                        <thead>
                                            <tr class="bg-gray-100 text-gray-600 uppercase text-sm">
                                                <th class="py-3 px-4 text-left">Nombre Completo</th>
                                                <th class="py-3 px-4 text-left">Cédula</th>
                                                <th class="py-3 px-4 text-left">Celular</th>
                                                <th class="py-3 px-4 text-left">Email</th>
                                                <th class="py-3 px-4 text-left">Parentesco</th>
                                                <th class="py-3 px-4 text-center">Acción</th>
                                            </tr>
                                        </thead>
                                        <tbody id="owners-container">
                                            <!-- Los propietarios se agregarán aquí dinámicamente -->
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <td colspan="6" class="py-3 px-4">
                                                    <button type="button" id="add-owner-btn" class="flex items-center justify-center w-full md:w-auto bg-blue-500 hover:bg-blue-600 text-white font-medium py-2 px-4 rounded-lg transition-colors">
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
                                        <thead>
                                            <tr class="bg-gray-100 text-gray-600 uppercase text-sm">
                                                <th class="py-3 px-4 text-left">Nombre Completo</th>
                                                <th class="py-3 px-4 text-left">Cédula</th>
                                                <th class="py-3 px-4 text-left">Celular</th>
                                                <th class="py-3 px-4 text-left">Email</th>
                                                <th class="py-3 px-4 text-left">Parentesco</th>
                                                <th class="py-3 px-4 text-center">Acción</th>
                                            </tr>
                                        </thead>
                                        <tbody id="residents-container">
                                            <!-- Los residentes se agregarán aquí dinámicamente -->
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <td colspan="6" class="py-3 px-4">
                                                    <button type="button" id="add-resident-btn" class="flex items-center justify-center w-full md:w-auto bg-blue-500 hover:bg-blue-600 text-white font-medium py-2 px-4 rounded-lg transition-colors">
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
                    </div>
                    
                    <!-- Template para nuevos propietarios (oculto) -->
                    <template id="owner-template">
                        <tr class="owner-item border-b hover:bg-gray-50">
                            <td class="py-2 px-4">
                                <input type="text" name="owners[INDEX][name]" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline uppercase owner-input-name" required>
                            </td>
                            <td class="py-2 px-4">
                                <input type="text" name="owners[INDEX][document]" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                            </td>
                            <td class="py-2 px-4">
                                <input type="tel" name="owners[INDEX][phone]" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                            </td>
                            <td class="py-2 px-4">
                                <input type="email" name="owners[INDEX][email]" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline lowercase">
                            </td>
                            <td class="py-2 px-4 text-center">
                                <button type="button" class="remove-owner-btn text-red-500 hover:text-red-700 focus:outline-none">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                                    </svg>
                                </button>
                            </td>
                        </tr>
                    </template>
                    
                    <!-- Template para nuevos residentes (oculto) -->
                    <template id="resident-template">
                        <tr class="resident-item border-b hover:bg-gray-50">
                            <td class="py-2 px-4">
                                <input type="text" name="residents[INDEX][name]" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline uppercase resident-input-name" required>
                            </td>
                            <td class="py-2 px-4">
                                <input type="text" name="residents[INDEX][document]" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                            </td>
                            <td class="py-2 px-4">
                                <input type="tel" name="residents[INDEX][phone]" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                            </td>
                            <td class="py-2 px-4">
                                <input type="email" name="residents[INDEX][email]" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline lowercase">
                            </td>
                            <td class="py-2 px-4">
                                <select name="residents[INDEX][parentesco]" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                    <option value="">Seleccionar...</option>
                                    @foreach($relationships as $relationship)
                                        <option value="{{ $relationship->id }}">{{ $relationship->name }}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td class="py-2 px-4 text-center">
                                <button type="button" class="remove-resident-btn text-red-500 hover:text-red-700 focus:outline-none">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                                    </svg>
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

        // Función para actualizar contadores
        function updateCounters() {
            document.getElementById('owners-counter').textContent = ownerCount;
            document.getElementById('residents-counter').textContent = residentCount;
        }

        // Función para agregar un nuevo propietario
        function addOwner() {
            // Incrementar contador
            ownerCount++;
            
            // Obtener el template
            const template = document.getElementById('owner-template');
            const clone = document.importNode(template.content, true);
            
            // Reemplazar el índice placeholder
            const inputs = clone.querySelectorAll('input');
            inputs.forEach(input => {
                input.name = input.name.replace('INDEX', ownerCount);
            });
            
            // Agregar evento para eliminar propietario
            const removeBtn = clone.querySelector('.remove-owner-btn');
            removeBtn.addEventListener('click', function() {
                const ownerRow = this.closest('.owner-item');
                ownerRow.remove();
                ownerCount--;
                document.getElementById('owners-counter').textContent = ownerCount;
                renumberOwners();
            });
            
            // Agregar el propietario al contenedor
            document.getElementById('owners-container').appendChild(clone);
            
            // Incrementar el contador
            ownerCount++;
            document.getElementById('owners-counter').textContent = ownerCount;
            
            // Abrir el acordeón de propietarios si está cerrado
            const propietariosBody = document.getElementById('propietarios-body');
            if (propietariosBody.classList.contains('hidden')) {
                toggleAccordion('propietarios-header');
            }
            
            // Enfocar el campo de nombre
            const ownerRows = document.querySelectorAll('#owners-container .owner-item');
            const lastOwnerRow = ownerRows[ownerRows.length - 1];
            const lastOwnerNameInput = lastOwnerRow.querySelector('.owner-input-name');
            lastOwnerNameInput.focus();
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
        function addResident() {
            // Obtener el template
            const template = document.getElementById('resident-template');
            const clone = document.importNode(template.content, true);
            
            // Reemplazar el índice placeholder
            const inputs = clone.querySelectorAll('input');
            inputs.forEach(input => {
                input.name = input.name.replace('INDEX', residentCount);
            });
            
            // Agregar evento para eliminar residente
            const removeBtn = clone.querySelector('.remove-resident-btn');
            removeBtn.addEventListener('click', function() {
                const residentRow = this.closest('.resident-item');
                residentRow.remove();
                residentCount--;
                document.getElementById('residents-counter').textContent = residentCount;
                renumberResidents();
            });
            
            // Agregar el residente al contenedor
            document.getElementById('residents-container').appendChild(clone);
            
            // Incrementar el contador
            residentCount++;
            document.getElementById('residents-counter').textContent = residentCount;
            
            // Abrir el acordeón de residentes si está cerrado
            const residentesBody = document.getElementById('residentes-body');
            if (residentesBody.classList.contains('hidden')) {
                toggleAccordion('residentes-header');
            }
            
            // Enfocar el campo de nombre
            const residentRows = document.querySelectorAll('#residents-container .resident-item');
            const lastResidentRow = residentRows[residentRows.length - 1];
            const lastResidentNameInput = lastResidentRow.querySelector('.resident-input-name');
            lastResidentNameInput.focus();
        }
        
        // Función para renumerar residentes
        function renumberResidents() {
            const residentRows = document.querySelectorAll('#residents-container .resident-item');
            residentRows.forEach((row, index) => {
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
        
        // Función para alternar acordeones
        function toggleAccordion(headerId) {
            const header = document.getElementById(headerId);
            const body = header.nextElementSibling;
            const icon = header.querySelector('.accordion-toggle svg');
            
            body.classList.toggle('hidden');
            icon.classList.toggle('rotate-180');
        }
        
        // Función para cargar datos existentes
        function loadExistingData() {
            @if(isset($apartamento) && $apartamento)
                // Cargar propietarios existentes
                @foreach($apartamento->owners as $index => $owner)
                    // Crear un nuevo propietario
                    addOwner();
                    
                    // Obtener el último propietario añadido
                    const ownerRows = document.querySelectorAll('#owners-container .owner-item');
                    const lastOwner = ownerRows[ownerRows.length - 1];
                    
                    // Llenar los campos
                    lastOwner.querySelector('input[name^="owners["][name$="[name]"]').value = "{{ $owner->name }}";
                    lastOwner.querySelector('input[name^="owners["][name$="[document]"]').value = "{{ $owner->document_number }}";
                    lastOwner.querySelector('input[name^="owners["][name$="[phone]"]').value = "{{ $owner->phone_number ?? '' }}";
                    lastOwner.querySelector('input[name^="owners["][name$="[email]"]').value = "{{ $owner->email ?? '' }}";
                @endforeach
                
                // Cargar residentes existentes
                @foreach($apartamento->residents as $index => $resident)
                    // Crear un nuevo residente
                    addResident();
                    
                    // Obtener el último residente añadido
                    const residentRows = document.querySelectorAll('#residents-container .resident-item');
                    const lastResident = residentRows[residentRows.length - 1];
                    
                    // Llenar los campos
                    lastResident.querySelector('input[name^="residents["][name$="[name]"]').value = "{{ $resident->name }}";
                    lastResident.querySelector('input[name^="residents["][name$="[document]"]').value = "{{ $resident->document_number }}";
                    lastResident.querySelector('input[name^="residents["][name$="[phone]"]').value = "{{ $resident->phone_number ?? '' }}";
                    lastResident.querySelector('input[name^="residents["][name$="[email]"]').value = "{{ $resident->email ?? '' }}";
                    
                    // Seleccionar el parentesco correcto si existe
                    const parentescoSelect = lastResident.querySelector('select[name^="residents["][name$="[parentesco]"]');
                    if (parentescoSelect) {
                        parentescoSelect.value = "{{ $resident->relationship_id ?? '' }}";
                    }
                @endforeach
            @endif
        }

        // Inicializar eventos cuando el DOM esté listo
        document.addEventListener('DOMContentLoaded', function() {
            // Agregar evento al botón de agregar propietario
            document.getElementById('add-owner-btn').addEventListener('click', addOwner);
            
            // Agregar evento al botón de agregar residente
            document.getElementById('add-resident-btn').addEventListener('click', addResident);
            
            // Agregar eventos a los encabezados de acordeones
            const accordionHeaders = document.querySelectorAll('.accordion-header');
            accordionHeaders.forEach(header => {
                header.addEventListener('click', function() {
                    toggleAccordion(this.id);
                });
            });
            
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
            
            // Si hay datos existentes, cargarlos
            loadExistingData();
        });
    </script>
</body>
</html>
