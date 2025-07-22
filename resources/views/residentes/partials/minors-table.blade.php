<!-- Tabla de menores de edad -->
<div class="table-responsive overflow-x-auto">
    <table class="min-w-full bg-white">
        <thead class="hidden md:table-header-group">
            <tr class="bg-gray-100 text-gray-600 uppercase text-sm">
                <th class="py-3 px-5 text-left">Nombre</th>
                <th class="py-3 px-3 text-left">Edad</th>
                <th class="py-3 px-4 text-left">Género</th>
                <th class="py-3 px-2 text-center w-[5%]">
                    <span class="sr-only">Acciones</span>
                </th>
            </tr>
        </thead>
        <tbody id="minors-container" class="block md:table-row-group">
            <!-- Los menores se agregarán aquí dinámicamente -->
        </tbody>
        <tfoot>
            <tr>
                <td colspan="4" class="py-3 px-4">
                    <button type="button" 
                            id="add-minor-btn" 
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
                        <span>Agregar Menor de Edad</span>
                    </button>
                </td>
            </tr>
        </tfoot>
    </table>
</div>

<p class="text-sm text-gray-500 mt-2">
    <strong>Nota:</strong> Registre aquí únicamente menores de 18 años. 
    Los campos marcados con <span class="text-red-600">*</span> son obligatorios. 
    Si no hay menores, haz clic en el botón para añadir el primero.
</p>