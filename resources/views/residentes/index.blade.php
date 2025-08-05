<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulario de Residentes - Conjunto Residencial Gualanday</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="flex flex-col items-center justify-center p-6">
        <div class="w-full max-w-md bg-white rounded-lg shadow-lg p-8">
            <div class="text-center mb-8">
                <div class="mb-6">
                    <img src="{{ asset('images/logo.png') }}" 
                         alt="Logo Conjunto Residencial Gualanday" 
                         class="mx-auto h-16 w-auto object-contain">
                </div>  
                <p class="text-gray-600 mt-2">Formulario de registro de residentes</p>
            </div>

            <div class="mb-6">
                <p class="text-gray-700 mb-4">Por favor ingrese el número de su apartamento para comenzar:</p>
                
                @if ($errors->any())
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('residentes.verificar') }}" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label for="number" class="block text-gray-700 text-sm font-bold mb-2">Número de Apartamento:</label>
                        <input type="text" id="number" name="number" 
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                            placeholder="Ej: A101" required>
                        <p class="text-sm text-gray-500 mt-1">Formato: Torre (A-M), Piso (1-5), Número (01-04)</p>
                    </div>
                    
                    <div class="flex items-center justify-center">
                        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                            Continuar
                        </button>
                    </div>
                </form>
            </div>
            
            <div class="text-center text-sm text-gray-500">
                <p>Si tiene problemas para acceder, por favor contacte a la administración.</p>
            </div>
        </div>
    </div>
</body>
</html>
