<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verificación de Código - Conjunto Residencial Gualanday</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="min-h-screen flex flex-col items-center justify-center">
        <div class="w-full max-w-md bg-white rounded-lg shadow-lg p-8">
            <div class="text-center mb-8">
                <h1 class="text-3xl font-bold text-gray-800">Verificación de Código</h1>
                <p class="text-gray-600 mt-2">Apartamento: {{ $apartamento->number }}</p>
            </div>

            <div class="mb-6">
                @if(isset($emailEnviado) && $emailEnviado)
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                        <p class="font-bold">Se ha enviado un código de verificación al correo electrónico registrado:</p>
                        <p class="font-medium text-blue-600">{{ substr($apartamento->resident_email, 0, 3) }}*****{{ substr($apartamento->resident_email, strpos($apartamento->resident_email, '@')) }}</p>
                    </div>
                @elseif(isset($emailEnviado) && !$emailEnviado)
                    <div class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded mb-4">
                        <p class="font-bold">No se pudo enviar el email al correo registrado:</p>
                        <p class="font-medium text-blue-600">{{ substr($apartamento->resident_email, 0, 3) }}*****{{ substr($apartamento->resident_email, strpos($apartamento->resident_email, '@')) }}</p>
                        <p class="mt-2">Por favor, use el código mostrado abajo o contacte a la administración.</p>
                    </div>
                @else
                    <p class="text-gray-700 mb-4">Se ha enviado un código de verificación al correo electrónico registrado:</p>
                    <p class="font-medium text-blue-600">{{ substr($apartamento->resident_email, 0, 3) }}*****{{ substr($apartamento->resident_email, strpos($apartamento->resident_email, '@')) }}</p>
                @endif
                
                @if (isset($codigo))
                <div class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded mb-4 mt-4">
                    <p class="font-bold">Código para pruebas: {{ $codigo }}</p>
                    <p class="text-sm">Nota: En producción, este código solo se enviará por email.</p>
                </div>
                @endif

                @if ($errors->any())
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('residentes.verificar-codigo') }}" method="POST" class="mt-6">
                    @csrf
                    <div class="mb-4">
                        <label for="codigo" class="block text-gray-700 text-sm font-bold mb-2">Ingrese el código de verificación:</label>
                        <input type="text" id="codigo" name="codigo" 
                            class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                            placeholder="Código de 6 caracteres" required>
                    </div>
                    
                    <div class="flex items-center justify-center">
                        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                            Verificar Código
                        </button>
                    </div>
                </form>
            </div>
            
            <div class="text-center text-sm text-gray-500">
                <p>Si no recibió el código, por favor contacte a la administración.</p>
            </div>
        </div>
    </div>
</body>
</html>
