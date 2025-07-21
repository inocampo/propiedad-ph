<?php
// Leer el archivo original
$content = file_get_contents('formulario.blade.php');

// 1. Eliminar la columna Email del encabezado de la tabla de residentes
$headerPattern = '<th class="py-3 px-4 text-left">Celular</th>
                                                <th class="py-3 px-4 text-left">Email</th>
                                                <th class="py-3 px-4 text-left">Parentesco</th>';
$headerReplacement = '<th class="py-3 px-4 text-left">Celular</th>
                                                <th class="py-3 px-4 text-left">Parentesco</th>';
$content = str_replace($headerPattern, $headerReplacement, $content);

// 2. Eliminar el campo de email del template para nuevos residentes
$templatePattern = '<td class="py-2 px-4">
                                <input type="tel" name="residents[INDEX][phone]" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                            </td>
                            <td class="py-2 px-4">
                                <input type="email" name="residents[INDEX][email]" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline lowercase">
                            </td>';
$templateReplacement = '<td class="py-2 px-4">
                                <input type="tel" name="residents[INDEX][phone]" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                            </td>';
$content = str_replace($templatePattern, $templateReplacement, $content);

// 3. Actualizar el JavaScript que carga los residentes existentes para que no intente cargar el email
$jsPattern = "lastResident.querySelector('input[name^=\"residents[\"][name$=\"[phone]\"]').value = \"{{ \$resident->phone_number ?? '' }}\";
                    lastResident.querySelector('input[name^=\"residents[\"][name$=\"[email]\"]').value = \"{{ \$resident->email ?? '' }}\";";
$jsReplacement = "lastResident.querySelector('input[name^=\"residents[\"][name$=\"[phone]\"]').value = \"{{ \$resident->phone_number ?? '' }}\";";
$content = str_replace($jsPattern, $jsReplacement, $content);

// Guardar el archivo modificado
file_put_contents('formulario_modified.blade.php', $content);

echo "Archivo actualizado correctamente. Se ha eliminado el campo de email del repeater de residentes.";
?>
