/**
 * Utilidades Base del Formulario
 * Funciones y configuraciones compartidas por todos los módulos
 */

// Configuración global
window.FormularioCore = {
    debug: true,
    loadDelay: 300,
    focusDelay: 100,
    
    // Selectores base
    selectors: {
        form: 'form[method="POST"]',
        submitButton: 'button[type="submit"]',
        accordionHeaders: '.accordion-header',
        accordionBodies: '.accordion-body',
        accordionToggle: '.accordion-toggle svg'
    },
    
    // Eventos personalizados
    events: {
        ITEM_ADDED: 'formulario:item:added',
        ITEM_REMOVED: 'formulario:item:removed',
        COUNTER_UPDATED: 'formulario:counter:updated',
        DATA_LOADED: 'formulario:data:loaded',
        ACCORDION_TOGGLED: 'formulario:accordion:toggled'
    }
};

// Utilidades compartidas
window.FormularioUtils = {
    log: function(message, data = null) {
        if (window.FormularioCore.debug) {
            console.log(`[Formulario] ${message}`, data || '');
        }
    },
    
    error: function(message, error = null) {
        console.error(`[Formulario Error] ${message}`, error || '');
    },
    
    querySelector: function(selector) {
        const element = document.querySelector(selector);
        if (!element) {
            this.error(`Elemento no encontrado: ${selector}`);
            return null;
        }
        return element;
    },
    
    querySelectorAll: function(selector) {
        return document.querySelectorAll(selector);
    },
    
    dispatchEvent: function(eventName, detail = {}) {
        document.dispatchEvent(new CustomEvent(eventName, {
            detail: {
                ...detail,
                timestamp: Date.now()
            }
        }));
    },
    
    focusElement: function(element, delay = window.FormularioCore.focusDelay) {
        if (element) {
            setTimeout(() => {
                element.focus();
                if (element.select && typeof element.select === 'function') {
                    element.select();
                }
            }, delay);
        }
    }
};

// Estado global de contadores
window.FormularioCounters = {
    owners: 0,
    residents: 0,
    minors: 0,
    vehicles: 0,
    pets: 0,
    
    update: function(type, value) {
        if (this.hasOwnProperty(type)) {
            this[type] = value;
            this.updateUI(type);
            window.FormularioUtils.dispatchEvent(window.FormularioCore.events.COUNTER_UPDATED, {
                type: type,
                value: value
            });
        }
    },
    
    updateUI: function(type) {
        const counterElement = document.querySelector(`#${type}-counter`);
        if (counterElement) {
            counterElement.textContent = this[type];
        }
    },
    
    updateAll: function() {
        Object.keys(this).forEach(type => {
            if (typeof this[type] === 'number') {
                const items = document.querySelectorAll(`.${type.slice(0, -1)}-item`); // Remove 's' from type
                this[type] = items.length;
                this.updateUI(type);
            }
        });
    }
};

// Manejo de acordeones (independiente)
window.AccordionManager = {
    init: function() {
        window.FormularioUtils.log('Inicializando acordeones');
        const headers = window.FormularioUtils.querySelectorAll(window.FormularioCore.selectors.accordionHeaders);
        
        headers.forEach(header => {
            header.addEventListener('click', () => {
                this.toggle(header.id);
            });
        });
    },
    
    toggle: function(headerId) {
        const header = window.FormularioUtils.querySelector(`#${headerId}`);
        if (!header) return;
        
        const body = header.nextElementSibling;
        const icon = header.querySelector(window.FormularioCore.selectors.accordionToggle);
        const isOpening = body.classList.contains('hidden');
        
        // Si estamos abriendo este acordeón, cerramos todos los demás
        if (isOpening) {
            this.closeAll();
        }
        
        // Alternar el estado del acordeón actual
        body.classList.toggle('hidden');
        if (icon) {
            icon.classList.toggle('rotate-180');
        }
        
        window.FormularioUtils.log(`Acordeón ${headerId} ${isOpening ? 'abierto' : 'cerrado'}`);
        window.FormularioUtils.dispatchEvent(window.FormularioCore.events.ACCORDION_TOGGLED, {
            accordionId: headerId,
            isOpen: isOpening
        });
    },
    
    closeAll: function() {
        const allBodies = window.FormularioUtils.querySelectorAll(window.FormularioCore.selectors.accordionBodies);
        const allIcons = window.FormularioUtils.querySelectorAll(window.FormularioCore.selectors.accordionToggle);
        
        allBodies.forEach(body => body.classList.add('hidden'));
        allIcons.forEach(icon => icon.classList.remove('rotate-180'));
    },
    
    open: function(headerId) {
        const header = window.FormularioUtils.querySelector(`#${headerId}`);
        if (!header) return;
        
        const body = header.nextElementSibling;
        const icon = header.querySelector(window.FormularioCore.selectors.accordionToggle);
        
        body.classList.remove('hidden');
        if (icon) {
            icon.classList.add('rotate-180');
        }
    }
};

// Funciones globales para compatibilidad hacia atrás
window.toggleAccordion = function(headerId) {
    if (window.AccordionManager) {
        window.AccordionManager.toggle(headerId);
    }
};

window.toggleBicyclesCount = function() {
    const hasBicycles = window.FormularioUtils.querySelector('#has_bicycles');
    const container = window.FormularioUtils.querySelector('#bicycles_count_container');
    const input = window.FormularioUtils.querySelector('#bicycles_count');
    
    if (hasBicycles && container) {
        if (hasBicycles.checked) {
            container.classList.remove('hidden');
        } else {
            container.classList.add('hidden');
            if (input) input.value = '';
        }
    }
};