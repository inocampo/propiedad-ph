/**
 * Aplicación Principal del Formulario - VERSIÓN SEGURA
 * Mantiene toda la funcionalidad original y añade optimizaciones móviles
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
        'ExitHandler',
        'LightweightSearchSelect'
    ],
    
    isMobile: /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent),
    
    init: function() {
        window.FormularioUtils.log(`🚀 Inicializando aplicación del formulario ${this.isMobile ? '(MÓVIL)' : '(DESKTOP)'}`);
        
        try {
            // Verificar dependencias críticas
            this.verifyDependencies();
            
            // Configurar optimizaciones móviles ANTES de inicializar módulos
            if (this.isMobile) {
                this.setupMobileOptimizations();
            }
            
            // Inicializar módulos en orden
            this.initializeModules();
            
            // Configurar eventos globales
            this.setupGlobalEvents();
            
            // Configurar eventos específicos para móviles
            if (this.isMobile) {
                this.setupMobileEvents();
            }
            
            window.FormularioUtils.log('✅ Aplicación inicializada correctamente');
        } catch (error) {
            window.FormularioUtils.error('❌ Error al inicializar la aplicación', error);
        }
    },

    setupMobileOptimizations: function() {
        window.FormularioUtils.log('📱 Configurando optimizaciones móviles');
        
        // Prevenir zoom en inputs (método más seguro)
        setTimeout(() => {
            this.preventInputZoom();
        }, 100);
        
        // Optimizar viewport para móviles
        this.optimizeViewport();
        
        // Mejorar el touch experience
        setTimeout(() => {
            this.enhanceTouchExperience();
        }, 200);
    },

    preventInputZoom: function() {
        // Método más seguro que no interfiere con la carga de datos
        const style = document.createElement('style');
        style.innerHTML = `
            @media screen and (max-width: 768px) {
                input, select, textarea {
                    font-size: 16px !important;
                    transform: translateZ(0);
                }
                
                .search-input, .mobile-search-input {
                    font-size: 16px !important;
                }
            }
        `;
        document.head.appendChild(style);
        
        window.FormularioUtils.log('📱 Prevención de zoom aplicada via CSS');
    },

    optimizeViewport: function() {
        let viewportMeta = document.querySelector('meta[name="viewport"]');
        
        if (!viewportMeta) {
            viewportMeta = document.createElement('meta');
            viewportMeta.name = 'viewport';
            document.head.appendChild(viewportMeta);
        }
        
        viewportMeta.content = 'width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, viewport-fit=cover';
        
        window.FormularioUtils.log('📱 Viewport optimizado');
    },

    enhanceTouchExperience: function() {
        // Mejorar feedback táctil de forma segura
        const style = document.createElement('style');
        style.innerHTML = `
            @media screen and (max-width: 768px) {
                button, .result-item, .dropdown-item, .search-trigger-btn {
                    min-height: 44px;
                    min-width: 44px;
                    touch-action: manipulation;
                }
                
                button:active, .result-item:active, .search-trigger-btn:active {
                    opacity: 0.8;
                    transform: scale(0.98);
                }
                
                .remove-btn {
                    min-height: 44px !important;
                    min-width: 44px !important;
                    display: flex !important;
                    align-items: center !important;
                    justify-content: center !important;
                }
            }
        `;
        document.head.appendChild(style);
        
        window.FormularioUtils.log('📱 Experiencia táctil mejorada');
    },

    setupMobileEvents: function() {
        // Solo eventos esenciales que no interfieren con la funcionalidad
        if (window.visualViewport) {
            window.visualViewport.addEventListener('resize', () => {
                const activeModal = document.querySelector('.mobile-search-modal.modal-visible');
                if (activeModal) {
                    const modalContent = activeModal.querySelector('.modal-content');
                    if (modalContent) {
                        const currentHeight = window.visualViewport.height;
                        modalContent.style.maxHeight = `${currentHeight - 40}px`;
                    }
                }
            });
        }
        
        // Mejorar orientación
        window.addEventListener('orientationchange', () => {
            setTimeout(() => {
                const activeModal = document.querySelector('.mobile-search-modal.modal-visible');
                if (activeModal) {
                    const modalContent = activeModal.querySelector('.modal-content');
                    if (modalContent) {
                        modalContent.style.maxHeight = 'calc(100vh - 120px)';
                    }
                }
            }, 500);
        });
        
        window.FormularioUtils.log('📱 Eventos móviles básicos configurados');
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
                    // Para el buscador, usar initWithCSS si está disponible
                    if (moduleName === 'LightweightSearchSelect' && typeof module.initWithCSS === 'function') {
                        module.initWithCSS();
                        window.FormularioUtils.log(`✅ ${moduleName} inicializado con CSS móvil`);
                    } else {
                        module.init();
                        window.FormularioUtils.log(`✅ ${moduleName} inicializado`);
                    }
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
            },
            device: {
                isMobile: this.isMobile,
                userAgent: navigator.userAgent,
                viewport: {
                    width: window.innerWidth,
                    height: window.innerHeight
                }
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
        
        // Cerrar modales móviles si están abiertos
        if (this.isMobile) {
            const activeModals = document.querySelectorAll('.mobile-search-modal');
            activeModals.forEach(modal => {
                if (modal.parentNode) {
                    modal.remove();
                }
            });
            document.body.style.overflow = '';
        }
        
        // Reinicializar
        this.init();
    }
};

/**
 * Manejo de guardado dual - VERSIÓN SIMPLIFICADA Y SEGURA
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
        
        // Cerrar modales móviles antes de guardar
        if (window.FormularioApp.isMobile) {
            const activeModals = document.querySelectorAll('.mobile-search-modal');
            activeModals.forEach(modal => {
                if (modal.parentNode) {
                    modal.remove();
                }
            });
            document.body.style.overflow = '';
        }
        
        // Mostrar estado de carga
        this.showLoadingState(button, action);
        
        // Guardar estado para "continuar editando"
        if (action === 'save_continue') {
            this.saveFormState();
        }
        
        // Marcar como guardado para el ExitHandler
        if (window.ExitHandler && typeof window.ExitHandler.markAsSaved === 'function') {
            window.ExitHandler.markAsSaved();
        }
        
        window.FormularioUtils.log(`Enviando formulario con acción: ${action}`);
        // El formulario se enviará normalmente
    },
    
    showLoadingState: function(button, action) {
        const originalText = button.innerHTML;
        button.disabled = true;
        button.style.opacity = '0.7';
        button.style.cursor = 'not-allowed';
        
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
        
        button.setAttribute('data-original-text', originalText);
        
        window.FormularioUtils.log(`Estado de carga mostrado para: ${action}`);
    },
    
    saveFormState: function() {
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
        const openAccordions = JSON.parse(sessionStorage.getItem('openAccordions') || '[]');
        openAccordions.forEach(headerId => {
            if (window.AccordionManager) {
                window.AccordionManager.open(headerId);
            }
        });
        
        const scrollPosition = sessionStorage.getItem('scrollPosition');
        if (scrollPosition) {
            setTimeout(() => {
                window.scrollTo(0, parseInt(scrollPosition));
            }, 100);
        }
        
        sessionStorage.removeItem('openAccordions');
        sessionStorage.removeItem('scrollPosition');
        
        window.FormularioUtils.log('Estado del formulario restaurado');
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
        console.log('Dispositivo:', status.device);
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
    // Asegurar que SaveHandler esté disponible
    if (window.SaveHandler && typeof window.SaveHandler.init === 'function') {
        window.SaveHandler.init();
    }
    
    // Inicializar la aplicación principal
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