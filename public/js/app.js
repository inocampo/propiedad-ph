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
        
        // Crear menú móvil inferior
        setTimeout(() => {
            this.createMobileMenu();
        }, 600);
    },

    createMobileMenu: function() {
        this.createFloatingButtons(); // Mantener el nombre del método para compatibilidad
    },

    createFloatingButtons: function() {
        if (!this.isMobile) return;
        
        // Buscar los botones originales
        const originalButtons = document.querySelectorAll('button[name="action"], #btn-exit-no-save');
        if (originalButtons.length === 0) return;
        
        // Ocultar los botones originales en móvil
        originalButtons.forEach(btn => {
            btn.style.display = 'none';
        });
        
        // Crear barra de navegación inferior estilo app móvil
        const bottomNavContainer = document.createElement('div');
        bottomNavContainer.className = 'mobile-bottom-nav';
        bottomNavContainer.innerHTML = `
            <div class="bottom-nav-content">
                <button type="button" class="nav-btn nav-btn-primary" data-action="save_continue">
                    <div class="nav-btn-content">
                        <svg xmlns="http://www.w3.org/2000/svg" class="nav-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4" />
                        </svg>
                        <span class="nav-label">Guardar</span>
                    </div>
                </button>
                
                <button type="button" class="nav-btn nav-btn-success" data-action="save_exit">
                    <div class="nav-btn-content">
                        <svg xmlns="http://www.w3.org/2000/svg" class="nav-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span class="nav-label">Finalizar</span>
                    </div>
                </button>
                
                <button type="button" class="nav-btn nav-btn-danger" data-action="exit_no_save">
                    <div class="nav-btn-content">
                        <svg xmlns="http://www.w3.org/2000/svg" class="nav-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                        <span class="nav-label">Salir</span>
                    </div>
                </button>
            </div>
        `;
        
        // Añadir estilos CSS
        this.addMobileBottomNavStyles();
        
        // Insertar en el body
        document.body.appendChild(bottomNavContainer);
        
        // Añadir padding al body para compensar la barra fija
        document.body.style.paddingBottom = '90px';
        
        // Configurar eventos
        this.setupBottomNavEvents(bottomNavContainer);
        
        window.FormularioUtils.log('📱 Barra de navegación móvil estilo app creada');
    },
    
    // NUEVA función para estilos de barra inferior
    addMobileBottomNavStyles: function() {
        if (document.querySelector('#mobile-bottom-nav-styles')) {
            return; // Ya existen los estilos
        }
        
        const style = document.createElement('style');
        style.id = 'mobile-bottom-nav-styles';
        style.innerHTML = `
            @media (max-width: 768px) {
                /* Contenedor principal de la barra inferior */
                .mobile-bottom-nav {
                    position: fixed;
                    bottom: 0;
                    left: 0;
                    right: 0;
                    background: rgba(255, 255, 255, 0.95);
                    backdrop-filter: blur(20px);
                    -webkit-backdrop-filter: blur(20px);
                    border-top: 1px solid rgba(0, 0, 0, 0.1);
                    z-index: 1000;
                    padding: env(safe-area-inset-bottom, 0px) 0 0 0;
                    box-shadow: 0 -8px 32px rgba(0, 0, 0, 0.1);
                    animation: slideUpNav 0.4s cubic-bezier(0.4, 0, 0.2, 1);
                }
                
                /* Contenido de la barra */
                .bottom-nav-content {
                    display: flex;
                    justify-content: space-around;
                    align-items: center;
                    padding: 8px 16px 12px 16px;
                    max-width: 100%;
                    margin: 0 auto;
                }
                
                /* Botones de navegación */
                .nav-btn {
                    flex: 1;
                    max-width: 120px;
                    background: transparent;
                    border: none;
                    padding: 8px 4px;
                    border-radius: 12px;
                    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
                    cursor: pointer;
                    touch-action: manipulation;
                    user-select: none;
                    -webkit-user-select: none;
                    position: relative;
                    overflow: hidden;
                }
                
                /* Contenido del botón */
                .nav-btn-content {
                    display: flex;
                    flex-direction: column;
                    align-items: center;
                    justify-content: center;
                    gap: 4px;
                    position: relative;
                    z-index: 2;
                }
                
                /* Iconos */
                .nav-icon {
                    width: 24px;
                    height: 24px;
                    transition: all 0.3s ease;
                    stroke-width: 2.5;
                }
                
                /* Etiquetas */
                .nav-label {
                    font-size: 11px;
                    font-weight: 600;
                    line-height: 1;
                    transition: all 0.3s ease;
                    text-transform: uppercase;
                    letter-spacing: 0.5px;
                }
                
                /* Colores para cada tipo de botón */
                .nav-btn-primary {
                    color: #3b82f6;
                }
                
                .nav-btn-primary:active,
                .nav-btn-primary:hover {
                    background: rgba(59, 130, 246, 0.15);
                    color: #1d4ed8;
                    transform: scale(0.95);
                }
                
                .nav-btn-success {
                    color: #10b981;
                }
                
                .nav-btn-success:active,
                .nav-btn-success:hover {
                    background: rgba(16, 185, 129, 0.15);
                    color: #059669;
                    transform: scale(0.95);
                }
                
                .nav-btn-danger {
                    color: #ef4444;
                }
                
                .nav-btn-danger:active,
                .nav-btn-danger:hover {
                    background: rgba(239, 68, 68, 0.15);
                    color: #dc2626;
                    transform: scale(0.95);
                }
                
                /* Efecto ripple */
                .nav-btn::before {
                    content: '';
                    position: absolute;
                    top: 50%;
                    left: 50%;
                    width: 0;
                    height: 0;
                    background: currentColor;
                    border-radius: 50%;
                    opacity: 0.3;
                    transform: translate(-50%, -50%);
                    transition: width 0.4s, height 0.4s;
                    pointer-events: none;
                }
                
                .nav-btn:active::before {
                    width: 80px;
                    height: 80px;
                }
                
                /* Estados de carga */
                .nav-btn.loading {
                    pointer-events: none;
                    opacity: 0.7;
                }
                
                .nav-btn.loading .nav-icon {
                    animation: spin 1s linear infinite;
                }
                
                .nav-btn.loading .nav-label {
                    opacity: 0.6;
                }
                
                @keyframes spin {
                    from { transform: rotate(0deg); }
                    to { transform: rotate(360deg); }
                }
                
                /* Animación de entrada */
                @keyframes slideUpNav {
                    from {
                        opacity: 0;
                        transform: translateY(100%);
                    }
                    to {
                        opacity: 1;
                        transform: translateY(0);
                    }
                }
                
                /* Vibración al tocar (opcional) */
                .nav-btn:active {
                    animation: tapVibration 0.1s ease;
                }
                
                @keyframes tapVibration {
                    0%, 100% { transform: scale(1); }
                    50% { transform: scale(0.95); }
                }
                
                /* Soporte para notch/safe area */
                @supports (padding: max(0px)) {
                    .mobile-bottom-nav {
                        padding-bottom: max(12px, env(safe-area-inset-bottom));
                    }
                }
                
                /* Ajustes para pantallas muy pequeñas */
                @media (max-width: 320px) {
                    .nav-icon {
                        width: 20px;
                        height: 20px;
                    }
                    
                    .nav-label {
                        font-size: 10px;
                    }
                    
                    .bottom-nav-content {
                        padding: 6px 8px 10px 8px;
                    }
                }
                
                /* Modo landscape en móviles */
                @media (max-height: 500px) and (orientation: landscape) {
                    .bottom-nav-content {
                        padding: 4px 16px 6px 16px;
                    }
                    
                    .nav-btn {
                        padding: 6px 4px;
                    }
                    
                    .nav-icon {
                        width: 20px;
                        height: 20px;
                    }
                    
                    .nav-label {
                        font-size: 10px;
                    }
                }
            }
            
            /* Ocultar en desktop */
            @media (min-width: 769px) {
                .mobile-bottom-nav {
                    display: none !important;
                }
                
                /* Remover padding del body en desktop */
                body {
                    padding-bottom: 0 !important;
                }
            }
            
            /* Ocultar botones originales en móvil */
            @media (max-width: 768px) {
                button[name="action"],
                #btn-exit-no-save,
                .flex.flex-col.md\\:flex-row.items-center.justify-center.gap-4.mt-8 {
                    display: none !important;
                }
            }
        `;
        
        document.head.appendChild(style);
    },
    
    // NUEVA función para manejar eventos de la barra inferior
    setupBottomNavEvents: function(container) {
        const buttons = container.querySelectorAll('.nav-btn');
        
        buttons.forEach(btn => {
            btn.addEventListener('click', (e) => {
                e.preventDefault();
                e.stopPropagation();
                
                const action = btn.getAttribute('data-action');
                this.handleBottomNavClick(action, btn);
            });
            
            // Vibración táctil en dispositivos compatibles
            btn.addEventListener('touchstart', () => {
                if ('vibrate' in navigator) {
                    navigator.vibrate(10);
                }
            });
            
            // Efecto visual adicional en touchstart
            btn.addEventListener('touchstart', () => {
                btn.style.transform = 'scale(0.95)';
            });
            
            btn.addEventListener('touchend', () => {
                setTimeout(() => {
                    btn.style.transform = '';
                }, 100);
            });
        });
    },
    
    // NUEVA función para manejar clicks de la barra inferior
    handleBottomNavClick: function(action, button) {
        window.FormularioUtils.log(`📱 Click en barra inferior: ${action}`);
        
        // Cerrar modales si están abiertos
        const activeModals = document.querySelectorAll('.mobile-search-modal');
        activeModals.forEach(modal => {
            if (modal.parentNode) {
                modal.remove();
            }
        });
        document.body.style.overflow = '';
        
        // Manejar acción de salir sin guardar
        if (action === 'exit_no_save') {
            if (window.ExitHandler && typeof window.ExitHandler.handleExitClick === 'function') {
                window.ExitHandler.handleExitClick();
            } else {
                window.location.href = '/residentes';
            }
            return;
        }
        
        // Mostrar estado de carga para acciones de guardado
        this.showBottomNavLoading(button, action);
        
        // Buscar el formulario y hacer submit
        const form = document.querySelector('form[method="POST"]');
        if (form) {
            let actionInput = form.querySelector('input[name="action"]');
            if (!actionInput) {
                actionInput = document.createElement('input');
                actionInput.type = 'hidden';
                actionInput.name = 'action';
                form.appendChild(actionInput);
            }
            actionInput.value = action;
            
            if (action === 'save_continue') {
                this.saveFormState();
            }
            
            if (window.ExitHandler && typeof window.ExitHandler.markAsSaved === 'function') {
                window.ExitHandler.markAsSaved();
            }
            
            setTimeout(() => {
                form.submit();
            }, 300);
        }
    },
    
    // NUEVA función para mostrar estado de carga en barra inferior
    showBottomNavLoading: function(button, action) {
        button.classList.add('loading');
        button.style.pointerEvents = 'none';
        
        const label = button.querySelector('.nav-label');
        
        // Cambiar texto según la acción
        if (label) {
            switch(action) {
                case 'save_continue':
                    label.textContent = 'Guardando...';
                    break;
                case 'save_exit':
                    label.textContent = 'Finalizando...';
                    break;
                case 'exit_no_save':
                    label.textContent = 'Saliendo...';
                    break;
                default:
                    label.textContent = 'Procesando...';
            }
        }
    },

    saveFormState: function() {
        // Guardar acordeones abiertos
        const openAccordions = [];
        document.querySelectorAll('.accordion-body:not(.hidden)').forEach(body => {
            const header = body.previousElementSibling;
            if (header && header.id) {
                openAccordions.push(header.id);
            }
        });
        
        sessionStorage.setItem('openAccordions', JSON.stringify(openAccordions));
        sessionStorage.setItem('scrollPosition', window.pageYOffset);
        
        window.FormularioUtils.log('📱 Estado del formulario guardado');
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
        // Mejorar feedback táctil de forma segura (excluyendo botones flotantes)
        const style = document.createElement('style');
        style.innerHTML = `
            @media screen and (max-width: 768px) {
                button:not(.floating-btn), .result-item, .dropdown-item, .search-trigger-btn {
                    min-height: 44px;
                    min-width: 44px;
                    touch-action: manipulation;
                }
                
                button:not(.floating-btn):active, .result-item:active, .search-trigger-btn:active {
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
                
                /* Asegurar que los botones flotantes no se vean afectados */
                .floating-btn {
                    min-height: auto !important;
                    min-width: auto !important;
                }
            }
        `;
        document.head.appendChild(style);
        
        window.FormularioUtils.log('📱 Experiencia táctil mejorada (excluyendo botones flotantes)');
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