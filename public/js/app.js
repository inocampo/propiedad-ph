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
        'DataLoader'
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