<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verificación de Código - Conjunto Residencial Gualanday</title>
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

<script>
// Usar exactamente la misma lógica que app.js
document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('form');
    const submitButton = form.querySelector('button[type="submit"]');
    
    if (form && submitButton) {
        form.addEventListener('submit', function(e) {
            // Función IDÉNTICA a showLoadingState en app.js
            const originalText = submitButton.textContent;
            submitButton.disabled = true;
            submitButton.style.opacity = '0.7';
            submitButton.style.cursor = 'not-allowed';
            
            submitButton.innerHTML = `
                <svg class="animate-spin h-5 w-5 mr-2 inline" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 714 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                Verificando código...
            `;
            
            submitButton.setAttribute('data-original-text', originalText);
            
            // Reset automático después de 30 segundos (por seguridad)
            setTimeout(() => {
                submitButton.disabled = false;
                submitButton.style.opacity = '1';
                submitButton.style.cursor = 'pointer';
                submitButton.textContent = originalText;
            }, 30000);
        });
    }
});
</script>
</body>
</html>
