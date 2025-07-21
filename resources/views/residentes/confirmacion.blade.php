<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmación - Conjunto Residencial Gualanday</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="min-h-screen flex flex-col items-center justify-center">
        <div class="w-full max-w-md bg-white rounded-lg shadow-lg p-8">
            <div class="text-center mb-8">
                <svg class="mx-auto h-16 w-16 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
                <h1 class="text-3xl font-bold text-gray-800 mt-4">¡Información Guardada!</h1>
                <p class="text-gray-600 mt-2">Sus datos han sido registrados correctamente.</p>
            </div>

            <div class="mb-6 text-center">
                <p class="text-gray-700 mb-4">Gracias por actualizar la información de su apartamento.</p>
                <p class="text-gray-700">La administración del Conjunto Residencial Gualanday revisará su información.</p>
            </div>
            
            <div class="flex items-center justify-center">
                <a href="{{ route('residentes.index') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                    Volver al Inicio
                </a>
            </div>
        </div>
    </div>
</body>
</html>
