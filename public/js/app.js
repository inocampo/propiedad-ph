/**
 * Aplicación Principal del Formulario
 * Coordina la inicialización de todos los módulos
 */

window.FormularioApp = {
    modules: [
        'AccordionManager',
        'OwnersModule', 
        'ResidentsModule',
        'MinorsModule',
        'VehiclesModule',
        'PetsModule',
        'SpecialFeatures',
        'DataLoader',
        'ExitHandler'
    ],
    
    init: function() {
        window.FormularioUtils.log('🚀 Inicializando aplicación del formulario');
        
        try {
            // Verificar dependencias críticas
            this.verifyDependencies();
            
            // Inicializar módulos en orden
            this.initializeModules();
            
            // Configurar eventos globales
            this.setupGlobalEvents();
            
            window.FormularioUtils.log('✅ Aplicación inicializada correctamente');
        } catch (error) {
            window.FormularioUtils.error('❌ Error al inicializar la aplicación', error);
        }
    },
    
    verifyDependencies: function() {
        window.FormularioUtils.log('🔍 Verificando dependencias...');
        
        // Verificar datos globales
        const dependencies = {
            'apartamentoData': window.apartamentoData,
            'breedsData': window.breedsData,
            'brandsData': window.brandsData,
            'colorsData': window.colorsData,
            'relationshipsData': window.relationshipsData
        };
        
        Object.keys(dependencies).forEach(dep => {
            const value = dependencies[dep];
            if (value) {
                if (Array.isArray(value)) {
                    window.FormularioUtils.log(`✅ ${dep}: ${value.length} elementos`);
                } else {
                    window.FormularioUtils.log(`✅ ${dep}: disponible`);
                }
            } else {
                window.FormularioUtils.log(`⚠️ ${dep}: no disponible`);
            }
        });
        
        // Verificar elementos críticos del DOM
        this.verifyDOMElements();
    },
    
    verifyDOMElements: function() {
        const criticalElements = [
            '#add-owner-btn',
            '#add-resident-btn', 
            '#add-minor-btn',
            '#add-vehicle-btn',
            '#add-pet-btn',
            '#owners-container',
            '#residents-container',
            '#minors-container', 
            '#vehicles-container',
            '#pets-container',
            '#owner-template',
            '#resident-template',
            '#minor-template',
            '#vehicle-template',
            '#pet-template'
        ];
        
        criticalElements.forEach(selector => {
            const element = document.querySelector(selector);
            if (element) {
                window.FormularioUtils.log(`✅ Elemento encontrado: ${selector}`);
            } else {
                window.FormularioUtils.error(`❌ Elemento no encontrado: ${selector}`);
            }
        });
    },
    
    initializeModules: function() {
        window.FormularioUtils.log('📦 Inicializando módulos...');
        
        this.modules.forEach(moduleName => {
            try {
                const module = window[moduleName];
                if (module && typeof module.init === 'function') {
                    module.init();
                    window.FormularioUtils.log(`✅ ${moduleName} inicializado`);
                } else {
                    window.FormularioUtils.error(`❌ ${moduleName} no disponible o sin método init`);
                }
            } catch (error) {
                window.FormularioUtils.error(`❌ Error al inicializar ${moduleName}`, error);
            }
        });
    },
    
    setupGlobalEvents: function() {
        window.FormularioUtils.log('🔗 Configurando eventos globales...');
        
        // Escuchar eventos personalizados
        document.addEventListener(window.FormularioCore.events.ITEM_ADDED, (e) => {
            window.FormularioUtils.log(`➕ Elemento agregado: ${e.detail.type}`);
        });
        
        document.addEventListener(window.FormularioCore.events.ITEM_REMOVED, (e) => {
            window.FormularioUtils.log(`➖ Elemento eliminado: ${e.detail.type}`);
        });
        
        document.addEventListener(window.FormularioCore.events.COUNTER_UPDATED, (e) => {
            window.FormularioUtils.log(`🔢 Contador actualizado: ${e.detail.type} = ${e.detail.value}`);
        });
        
        document.addEventListener(window.FormularioCore.events.DATA_LOADED, (e) => {
            window.FormularioUtils.log('📥 Datos cargados completamente');
        });
    },
    
    // Funciones de debugging y testing
    test: function() {
        window.FormularioUtils.log('🧪 Iniciando pruebas...');
        
        // Probar cada módulo
        const testModules = ['OwnersModule', 'ResidentsModule', 'MinorsModule', 'VehiclesModule', 'PetsModule'];
        
        testModules.forEach(moduleName => {
            const module = window[moduleName];
            if (module && typeof module.add === 'function') {
                try {
                    module.add();
                    window.FormularioUtils.log(`✅ Test ${moduleName}: OK`);
                } catch (error) {
                    window.FormularioUtils.error(`❌ Test ${moduleName}: Error`, error);
                }
            }
        });
    },
    
    getStatus: function() {
        const status = {
            modules: {},
            counters: { ...window.FormularioCounters },
            data: {
                apartamento: !!window.apartamentoData,
                breeds: window.breedsData ? window.breedsData.length : 0,
                brands: window.brandsData ? window.brandsData.length : 0,
                colors: window.colorsData ? window.colorsData.length : 0,
                relationships: window.relationshipsData ? window.relationshipsData.length : 0
            }
        };
        
        // Verificar estado de módulos
        this.modules.forEach(moduleName => {
            const module = window[moduleName];
            status.modules[moduleName] = {
                available: !!module,
                hasInit: !!(module && typeof module.init === 'function'),
                hasAdd: !!(module && typeof module.add === 'function')
            };
        });
        
        return status;
    },
    
    // Función para reinicializar completamente
    restart: function() {
        window.FormularioUtils.log('🔄 Reiniciando aplicación...');
        
        // Limpiar contadores
        Object.keys(window.FormularioCounters).forEach(key => {
            if (typeof window.FormularioCounters[key] === 'number') {
                window.FormularioCounters[key] = 0;
            }
        });
        
        // Reinicializar
        this.init();
    }
};

/**
 * Manejo de guardado dual - VERSIÓN CORREGIDA
 * Mejora la experiencia de usuario durante el guardado
 */
window.SaveHandler = {
    init: function() {
        window.FormularioUtils.log('Inicializando manejador de guardado');
        this.attachSaveEvents();
    },
    
    attachSaveEvents: function() {
        const form = document.querySelector('form[method="POST"]');
        if (!form) {
            window.FormularioUtils.log('No se encontró el formulario principal');
            return;
        }
        
        const saveButtons = form.querySelectorAll('button[type="submit"][name="action"]');
        window.FormularioUtils.log(`Encontrados ${saveButtons.length} botones de guardado`);
        
        saveButtons.forEach(button => {
            button.addEventListener('click', (e) => {
                const action = button.value;
                window.FormularioUtils.log(`Click en botón con acción: ${action}`);
                this.handleSaveAction(e, action, button, form);
            });
        });
    },
    
    handleSaveAction: function(event, action, button, form) {
        window.FormularioUtils.log(`Procesando acción de guardado: ${action}`);
        
        // Prevenir múltiples envíos
        if (button.disabled) {
            event.preventDefault();
            window.FormularioUtils.log('Botón ya deshabilitado, previniendo envío duplicado');
            return;
        }
        
        // Validar formulario antes de enviar
        if (!this.validateForm(form)) {
            event.preventDefault();
            window.FormularioUtils.log('Validación de formulario falló');
            this.resetButtonState(button, action);
            return;
        }
        
        // Mostrar estado de carga
        this.showLoadingState(button, action);
        
        // Guardar estado en sessionStorage para mantener posición después del guardado
        if (action === 'save_continue') {
            this.saveFormState();
        }
        
        if (window.ExitHandler && typeof window.ExitHandler.markAsSaved === 'function') {
            window.ExitHandler.markAsSaved();
        }
        window.FormularioUtils.log(`Enviando formulario con acción: ${action}`);
        // El formulario se enviará normalmente después de esto
    },
    
    showLoadingState: function(button, action) {
        const originalText = button.textContent;
        button.disabled = true;
        button.style.opacity = '0.7';
        button.style.cursor = 'not-allowed';
        
        // Cambiar texto según la acción
        const loadingText = action === 'save_continue' 
            ? 'Guardando...' 
            : 'Guardando y Finalizando...';
        
        button.innerHTML = `
            <svg class="animate-spin h-5 w-5 mr-2 inline" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            ${loadingText}
        `;
        
        // Guardar texto original para restaurar en caso de error
        button.setAttribute('data-original-text', originalText);
        
        window.FormularioUtils.log(`Estado de carga mostrado para: ${action}`);
    },
    
    resetButtonState: function(button, action) {
        button.disabled = false;
        button.style.opacity = '1';
        button.style.cursor = 'pointer';
        
        const originalText = button.getAttribute('data-original-text');
        if (originalText) {
            button.textContent = originalText;
        }
        
        window.FormularioUtils.log(`Estado del botón restaurado para: ${action}`);
    },
    
    validateForm: function(form) {
        // Validación básica - campos requeridos
        const requiredFields = form.querySelectorAll('input[required], select[required]');
        let isValid = true;
        
        // Remover clases de error previas
        form.querySelectorAll('.border-red-500').forEach(field => {
            field.classList.remove('border-red-500');
        });
        
        requiredFields.forEach(field => {
            if (!field.value.trim()) {
                field.classList.add('border-red-500');
                isValid = false;
                
                // Remover la clase de error después de que el usuario empiece a escribir
                field.addEventListener('input', () => {
                    field.classList.remove('border-red-500');
                }, { once: true });
            }
        });
        
        if (!isValid) {
            this.showValidationError();
            // Scroll al primer campo con error
            const firstError = form.querySelector('.border-red-500');
            if (firstError) {
                firstError.scrollIntoView({ 
                    behavior: 'smooth', 
                    block: 'center' 
                });
                setTimeout(() => firstError.focus(), 500);
            }
        }
        
        window.FormularioUtils.log(`Validación de formulario: ${isValid ? 'exitosa' : 'fallida'}`);
        return isValid;
    },
    
    showValidationError: function() {
        // Mostrar toast de error
        this.showToast('Por favor completa todos los campos requeridos', 'error');
    },
    
    saveFormState: function() {
        // Guardar estado del acordeón abierto y posición de scroll
        const openAccordions = [];
        document.querySelectorAll('.accordion-body:not(.hidden)').forEach(body => {
            const header = body.previousElementSibling;
            if (header && header.id) {
                openAccordions.push(header.id);
            }
        });
        
        sessionStorage.setItem('openAccordions', JSON.stringify(openAccordions));
        sessionStorage.setItem('scrollPosition', window.pageYOffset);
        
        window.FormularioUtils.log('Estado del formulario guardado para restauración');
    },
    
    restoreFormState: function() {
        // Restaurar acordeones abiertos
        const openAccordions = JSON.parse(sessionStorage.getItem('openAccordions') || '[]');
        openAccordions.forEach(headerId => {
            if (window.AccordionManager) {
                window.AccordionManager.open(headerId);
            }
        });
        
        // Restaurar posición de scroll
        const scrollPosition = sessionStorage.getItem('scrollPosition');
        if (scrollPosition) {
            setTimeout(() => {
                window.scrollTo(0, parseInt(scrollPosition));
            }, 100);
        }
        
        // Limpiar estado guardado
        sessionStorage.removeItem('openAccordions');
        sessionStorage.removeItem('scrollPosition');
        
        window.FormularioUtils.log('Estado del formulario restaurado');
    },
    
    showToast: function(message, type = 'info') {
        // Crear toast dinámicamente
        const toast = document.createElement('div');
        toast.className = `fixed top-4 right-4 z-50 flex items-center w-full max-w-md p-4 text-gray-500 bg-white rounded-lg shadow-lg border-l-4 ${
            type === 'error' ? 'border-red-500' : 'border-blue-500'
        }`;
        
        toast.innerHTML = `
            <div class="inline-flex items-center justify-center flex-shrink-0 w-8 h-8 ${
                type === 'error' ? 'text-red-500 bg-red-100' : 'text-blue-500 bg-blue-100'
            } rounded-lg">
                ${type === 'error' 
                    ? '<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>'
                    : '<svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path></svg>'
                }
            </div>
            <div class="ml-3 text-sm font-medium text-gray-900">
                ${message}
            </div>
            <button type="button" class="ml-auto -mx-1.5 -my-1.5 bg-white text-gray-400 hover:text-gray-900 rounded-lg focus:ring-2 focus:ring-gray-300 p-1.5 hover:bg-gray-100 inline-flex items-center justify-center h-8 w-8" onclick="this.parentElement.remove()">
                <span class="sr-only">Cerrar</span>
                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        `;
        
        document.body.appendChild(toast);
        
        // Auto-remover después de 5 segundos
        setTimeout(() => {
            if (toast.parentElement) {
                toast.style.opacity = '0';
                toast.style.transform = 'translateX(100%)';
                toast.style.transition = 'all 0.3s ease-out';
                setTimeout(() => toast.remove(), 300);
            }
        }, 5000);
    }
};

// Funciones globales para debugging
window.testFormulario = function() {
    if (window.FormularioApp) {
        window.FormularioApp.test();
    }
};

window.statusFormulario = function() {
    if (window.FormularioApp) {
        const status = window.FormularioApp.getStatus();
        console.table(status.modules);
        console.log('Contadores:', status.counters);
        console.log('Datos:', status.data);
        return status;
    }
};

window.restartFormulario = function() {
    if (window.FormularioApp) {
        window.FormularioApp.restart();
    }
};

// Inicialización automática cuando el DOM esté listo
document.addEventListener('DOMContentLoaded', function() {
    // Pequeño retraso para asegurar que todos los scripts estén cargados
    setTimeout(() => {
        if (window.FormularioApp) {
            window.FormularioApp.init();
        } else {
            console.error('FormularioApp no está disponible');
        }
    }, 100);
});

// Restaurar estado del formulario al cargar la página si viene de "guardar y continuar"
document.addEventListener('DOMContentLoaded', function() {
    if (sessionStorage.getItem('openAccordions')) {
        setTimeout(() => {
            if (window.SaveHandler) {
                window.SaveHandler.restoreFormState();
            }
        }, 500);
    }
});