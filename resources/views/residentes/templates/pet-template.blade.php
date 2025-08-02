<!-- Template para nuevas mascotas (oculto) -->
<template id="pet-template">
    <tr class="pet-item repeater-row border-b hover:bg-gray-50 block md:table-row mb-6 md:mb-0">
        <td class="repeater-cell py-1 block md:table-cell before:content-['Nombre:'] before:font-bold before:text-gray-700 before:block md:before:hidden">
            <input type="text" 
                   name="pets[INDEX][name]" 
                   class="repeater-field shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline uppercase pet-input-name bg-yellow-50 focus:bg-white" 
                   placeholder="Nombre de la mascota"
                   required 
                   aria-label="Nombre de la mascota">
        </td>
        <td class="repeater-cell py-1 block md:table-cell before:content-['Tipo:'] before:font-bold before:text-gray-700 before:block md:before:hidden">
            <select name="pets[INDEX][type]" 
                    class="repeater-field shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline pet-input-type bg-yellow-50 focus:bg-white" 
                    required
                    aria-label="Tipo de mascota">
                <option value="">Seleccionar tipo...</option>
                <option value="perro">Perro</option>
                <option value="gato">Gato</option>
            </select>
        </td>
        <td class="repeater-cell py-1 block md:table-cell before:content-['Raza:'] before:font-bold before:text-gray-700 before:block md:before:hidden">
            <select name="pets[INDEX][breed_id]" 
                    class="repeater-field shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline bg-yellow-50 focus:bg-white"
                    required
                    aria-label="Raza de la mascota">
                <option value="" disabled selected>Seleccione una raza</option>
                @foreach($breeds as $breed)
                    <option value="{{ $breed->id }}">{{ $breed->name }}</option>
                @endforeach
            </select>
        </td>
        <td class="repeater-cell py-1 text-center block md:table-cell">
            <button type="button" 
                    class="remove-btn remove-pet-btn w-full md:w-auto bg-red-600 hover:bg-red-700 md:bg-transparent md:hover:bg-transparent text-white md:text-red-600 font-medium py-1 px-2 rounded-lg focus:outline-none transition-colors duration-200 flex items-center justify-center"
                    aria-label="Eliminar mascota">
                <svg xmlns="http://www.w3.org/2000/svg" 
                     class="h-5 w-5 inline mr-1 md:mr-0" 
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