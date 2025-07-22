<!-- Información del Residente Principal -->
<div class="mb-6">
    <h3 class="text-lg font-semibold text-gray-800 mb-3 pb-2 border-b">Información del Residente Principal</h3>
    
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
            <label for="resident_name" class="block text-gray-700 text-sm font-bold mb-2 required-field">Nombre Completo</label>
            <input type="text" 
                   id="resident_name" 
                   name="resident_name" 
                   class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline uppercase"
                   value="{{ $apartamento ? $apartamento->resident_name : old('resident_name') }}" 
                   required>
        </div>
        
        <div>
            <label for="resident_document" class="block text-gray-700 text-sm font-bold mb-2 required-field">Cédula</label>
            <input type="text" 
                   id="resident_document" 
                   name="resident_document" 
                   class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                   value="{{ $apartamento ? $apartamento->resident_document : old('resident_document') }}" 
                   required>
        </div>
        
        <div>
            <label for="resident_phone" class="block text-gray-700 text-sm font-bold mb-2 required-field">Celular</label>
            <input type="tel" 
                   id="resident_phone" 
                   name="resident_phone" 
                   class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                   value="{{ $apartamento ? $apartamento->resident_phone : old('resident_phone') }}" 
                   required>
        </div>
        
        <div>
            <label for="resident_email" class="block text-gray-700 text-sm font-bold mb-2 required-field">Email</label>
            <input type="email" 
                   id="resident_email" 
                   name="resident_email" 
                   class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline lowercase"
                   value="{{ $apartamento ? $apartamento->resident_email : old('resident_email') }}" 
                   required>
        </div>
    </div>
</div>

<!-- Información Adicional -->
<div>
    <h3 class="text-lg font-semibold text-gray-800 mb-3 pb-2 border-b">Información Adicional</h3>
    
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
        <div class="flex items-center">
            <input type="checkbox" 
                   id="received_manual" 
                   name="received_manual" 
                   value="1" 
                   class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500"
                   {{ $apartamento && $apartamento->received_manual ? 'checked' : '' }}>
            <label for="received_manual" class="ml-2 text-sm font-medium text-gray-700">¿Recibió el manual de convivencia?</label>
        </div>
        
        <div class="flex items-center">
            <input type="checkbox" 
                   id="has_bicycles" 
                   name="has_bicycles" 
                   value="1" 
                   class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500"
                   {{ $apartamento && $apartamento->has_bicycles ? 'checked' : '' }}>
            <label for="has_bicycles" class="ml-2 text-sm font-medium text-gray-700">¿Tienen bicicletas?</label>
        </div>
        
        <div id="bicycles_count_container" class="{{ ($apartamento && $apartamento->has_bicycles) ? '' : 'hidden' }}">
            <label for="bicycles_count" class="block text-gray-700 text-sm font-bold mb-2">Cantidad de bicicletas:</label>
            <input type="number" 
                   id="bicycles_count" 
                   name="bicycles_count" 
                   min="1" 
                   class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                   value="{{ $apartamento ? $apartamento->bicycles_count : old('bicycles_count', 1) }}">
        </div>
    </div>
    
    <div>
        <label for="observations" class="block text-gray-700 text-sm font-bold mb-2">Observaciones:</label>
        <textarea id="observations" 
                  name="observations" 
                  rows="3" 
                  class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">{{ $apartamento ? $apartamento->observations : old('observations') }}</textarea>
    </div>
</div>