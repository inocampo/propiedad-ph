/**
 * Funcionalidades Especiales
 * Maneja funcionalidades específicas como bicicletas y transformaciones de texto
 */

window.SpecialFeatures = {
    init: function() {
        window.FormularioUtils.log('Inicializando funcionalidades especiales');
        this.initBicycleToggle();
        this.initTextTransforms();
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
    }
};