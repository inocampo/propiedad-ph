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
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                Buscando apartamento...
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
