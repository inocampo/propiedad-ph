/**
 * Funcionalidades Especiales
 * Maneja funcionalidades específicas como bicicletas y transformaciones de texto
 */

window.SpecialFeatures = {
    init: function() {
        window.FormularioUtils.log('Inicializando funcionalidades especiales');
        this.initBicycleToggle();
        this.initTextTransforms();
        this.initSaveButtons();
    },
    
    initBicycleToggle: function() {
        const hasBicycles = window.FormularioUtils.querySelector('#has_bicycles');
        if (hasBicycles) {
            hasBicycles.addEventListener('change', this.toggleBicyclesCount);
            // Inicializar estado
            this.toggleBicyclesCount();
            window.FormularioUtils.log('Toggle de bicicletas inicializado');
        }
    },
    
    toggleBicyclesCount: function() {
        const hasBicycles = window.FormularioUtils.querySelector('#has_bicycles');
        const container = window.FormularioUtils.querySelector('#bicycles_count_container');
        const input = window.FormularioUtils.querySelector('#bicycles_count');
        
        if (hasBicycles && container) {
            if (hasBicycles.checked) {
                container.classList.remove('hidden');
                if (input && !input.value) {
                    input.value = '1'; // Valor por defecto
                }
            } else {
                container.classList.add('hidden');
                if (input) input.value = '';
            }
        }
    },
    
    initTextTransforms: function() {
        // Auto uppercase para nombres
        document.addEventListener('input', function(e) {
            if (e.target.classList.contains('uppercase')) {
                e.target.value = e.target.value.toUpperCase();
            } else if (e.target.classList.contains('lowercase')) {
                e.target.value = e.target.value.toLowerCase();
            }
        });
        
        window.FormularioUtils.log('Transformaciones de texto inicializadas');
    },
    
    initSaveButtons: function() {
        const form = document.querySelector('form');
        const btnContinue = document.getElementById('btn-save-continue');
        const btnExit = document.getElementById('btn-save-exit');

        if (!form || !btnContinue || !btnExit) {
            window.FormularioUtils.log('No se encontraron los botones de guardado o el formulario.', 'error');
            return;
        }

        const buttons = [btnContinue, btnExit];

        buttons.forEach(button => {
            button.addEventListener('click', function(e) {
                // Prevenir el envío automático para manejar el estado de carga
                e.preventDefault();

                // Deshabilitar ambos botones y mostrar estado de carga
                buttons.forEach(btn => {
                    btn.disabled = true;
                    btn.innerHTML = `
                        <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Guardando...
                    `;
                });

                // Añadir el campo 'action' al formulario
                let actionInput = form.querySelector('input[name="action"]');
                if (!actionInput) {
                    actionInput = document.createElement('input');
                    actionInput.type = 'hidden';
                    actionInput.name = 'action';
                    form.appendChild(actionInput);
                }
                actionInput.value = this.value;

                // Enviar el formulario
                form.submit();
            });
        });

        window.FormularioUtils.log('Manejadores de botones de guardado inicializados');
    }
};