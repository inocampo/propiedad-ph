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
