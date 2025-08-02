<!-- Template para nuevos residentes (oculto) -->
<template id="resident-template">
    <tr class="resident-item repeater-row border-b hover:bg-gray-50 block md:table-row mb-6 md:mb-0">
        <td class="repeater-cell py-1 block md:table-cell before:content-['Nombre_Completo:'] before:font-bold before:text-gray-700 before:block md:before:hidden">
            <input type="text" 
                   name="residents[INDEX][name]" 
                   class="repeater-field shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline uppercase resident-input-name bg-yellow-50 focus:bg-white" 
                   placeholder="Nombre completo"
                   required 
                   aria-label="Nombre completo">
        </td>
        <td class="repeater-cell py-1 block md:table-cell before:content-['Cédula:'] before:font-bold before:text-gray-700 before:block md:before:hidden">
            <input type="text" 
                   name="residents[INDEX][document]" 
                   class="repeater-field shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline bg-yellow-50 focus:bg-white" 
                   placeholder="Número de cédula"
                   required 
                   aria-label="Número de cédula">
        </td>
        <td class="repeater-cell py-1 block md:table-cell before:content-['Celular:'] before:font-bold before:text-gray-700 before:block md:before:hidden">
            <input type="tel" 
                   name="residents[INDEX][phone]" 
                   class="repeater-field shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline bg-yellow-50 focus:bg-white" 
                   placeholder="Número de celular"
                   required
                   aria-label="Número de celular">
        </td>
        <td class="repeater-cell py-1 block md:table-cell before:content-['Parentesco:'] before:font-bold before:text-gray-700 before:block md:before:hidden">
            <select name="residents[INDEX][relationship_id]" 
                    class="repeater-field shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline bg-yellow-50 focus:bg-white"
                    aria-label="Parentesco o relación"
                    required>
                <option value="">Seleccionar...</option>
                @if(isset($relationships))
                    @foreach($relationships as $relationship)
                        <option value="{{ $relationship->id }}">{{ $relationship->name }}</option>
                    @endforeach
                @endif
            </select>
        </td>
        <td class="repeater-cell py-1 block md:table-cell text-center before:content-['Citófono:'] before:font-bold before:text-gray-700 before:block md:before:hidden">
            <input type="hidden" name="residents[INDEX][phone_for_intercom]" value="0">
            <input type="checkbox" 
                   name="residents[INDEX][phone_for_intercom]" 
                   value="1"
                   class="repeater-field h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500"
                   aria-label="Autorizar celular para citofonía">
        </td>
        <td class="repeater-cell py-1 text-center block md:table-cell">
            <button type="button" 
                    class="remove-btn remove-resident-btn w-full md:w-auto bg-red-600 hover:bg-red-700 md:bg-transparent md:hover:bg-transparent text-white md:text-red-600 font-medium py-1 px-2 rounded-lg focus:outline-none transition-colors duration-200 flex items-center justify-center"
                    aria-label="Eliminar residente">
                <svg xmlns="http://www.w3.org/2000/svg" 
                     class="h-4 w-4 inline mr-1 md:mr-0" 
                     viewBox="0 0 20 20" 
                     fill="currentColor"
                     aria-hidden="true">
                    <path fill-rule="evenodd" 
                          d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" 
                          clip-rule="evenodd" />
                </svg>
                <span class="md:hidden">Eliminar</span>
            </button>
        </td>
    </tr>
</template>