<?php
// Leer el archivo original
$content = file_get_contents('formulario.blade.php');

// 1. Añadir la columna de parentesco en el encabezado de la tabla de residentes
$headerPattern = '<th class="py-3 px-4 text-left">Email</th>
                                                <th class="py-3 px-4 text-center">Acción</th>';
$headerReplacement = '<th class="py-3 px-4 text-left">Email</th>
                                                <th class="py-3 px-4 text-left">Parentesco</th>
                                                <th class="py-3 px-4 text-center">Acción</th>';
$content = str_replace($headerPattern, $headerReplacement, $content);

// 2. Modificar el colspan en el footer de la tabla de residentes
$footerPattern = '<td colspan="5" class="py-3 px-4">';
$footerReplacement = '<td colspan="6" class="py-3 px-4">';
$content = str_replace($footerPattern, $footerReplacement, $content);

// 3. Añadir el campo select de parentesco en el template para nuevos residentes
$templatePattern = '<td class="py-2 px-4">
                                <input type="email" name="residents[INDEX][email]" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline lowercase">
                            </td>
                            <td class="py-2 px-4 text-center">';
$templateReplacement = '<td class="py-2 px-4">
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
                            <td class="py-2 px-4 text-center">';
$content = str_replace($templatePattern, $templateReplacement, $content);

// 4. Actualizar el JavaScript para cargar el valor seleccionado para residentes existentes
$jsPattern = "lastResident.querySelector('input[name^=\"residents[\"][name$=\"[email]\"]').value = \"{{ \$resident->email ?? '' }}\";";
$jsReplacement = "lastResident.querySelector('input[name^=\"residents[\"][name$=\"[email]\"]').value = \"{{ \$resident->email ?? '' }}\";
                    
                    // Seleccionar el parentesco correcto si existe
                    const parentescoSelect = lastResident.querySelector('select[name^=\"residents[\"][name$=\"[parentesco]\"]');
                    if (parentescoSelect) {
                        parentescoSelect.value = \"{{ \$resident->relationship_id ?? '' }}\";
                    }";
$content = str_replace($jsPattern, $jsReplacement, $content);

// Guardar el archivo modificado
file_put_contents('formulario_updated.blade.php', $content);

echo "Archivo actualizado correctamente.";
?>
