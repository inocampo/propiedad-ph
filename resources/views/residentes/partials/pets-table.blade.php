<!-- Tabla de mascotas -->
<div class="table-responsive overflow-x-auto">
    <table class="min-w-full bg-white">
        <thead class="hidden md:table-header-group">
            <tr class="bg-gray-100 text-gray-600 uppercase text-sm">
                <th class="py-3 px-4 text-left w-[30%]">Nombre</th>
                <th class="py-3 px-4 text-left w-[20%]">Tipo</th>
                <th class="py-3 px-4 text-left w-[40%]">Raza</th>
                <th class="py-3 px-2 text-center w-[10%]">
                    <span class="sr-only">Acciones</span>
                </th>
            </tr>
        </thead>
        <tbody id="pets-container" class="block md:table-row-group">
            <!-- Las mascotas se agregarán aquí dinámicamente -->
        </tbody>
        <tfoot>
            <tr>
                <td colspan="4" class="py-3 px-4">
                    <button type="button" 
                            id="add-pet-btn" 
                            class="flex items-center justify-center w-full md:w-auto bg-blue-500 hover:bg-blue-600 text-white font-medium py-2 px-4 rounded-lg transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                        <svg xmlns="http://www.w3.org/2000/svg" 
                             class="h-5 w-5 mr-2" 
                             viewBox="0 0 20 20" 
                             fill="currentColor"
                             aria-hidden="true">
                            <path fill-rule="evenodd" 
                                  d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" 
                                  clip-rule="evenodd" />
                        </svg>
                        <span>Agregar Mascota</span>
                    </button>
                </td>
            </tr>
        </tfoot>
    </table>
</div>

<p class="text-sm text-gray-500 mt-2">
    <strong>Nota:</strong> Registre aquí todas las mascotas que viven en el apartamento. 
    Los campos marcados con <span class="text-red-600">*</span> son obligatorios. 
    Si no hay mascotas, haz clic en el botón para añadir la primera.
</p>