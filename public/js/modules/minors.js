/**
 * Módulo de Menores de Edad
 * Maneja toda la funcionalidad relacionada con menores de edad
 */

window.MinorsModule = {
    config: {
        containerSelector: '#minors-container',
        buttonSelector: '#add-minor-btn',
        templateSelector: '#minor-template',
        counterSelector: '#minors-counter',
        itemClass: 'minor-item',
        accordionId: 'menores-header',
        focusSelector: '.minor-input-name'
    },
    
    init: function() {
        window.FormularioUtils.log('Inicializando módulo de menores');
        this.attachEvents();
        this.updateCounter();
    },
    
    attachEvents: function() {
        const addButton = window.FormularioUtils.querySelector(this.config.buttonSelector);
        if (addButton) {
            addButton.addEventListener('click', (e) => {
                e.preventDefault();
                this.add();
            });
            window.FormularioUtils.log('Evento de agregar menor configurado');
        }
    },
    
    add: function(index = null) {
        const newIndex = index !== null ? index : this.getNextIndex();
        window.FormularioUtils.log(`Agregando menor con índice ${newIndex}`);
        
        const template = window.FormularioUtils.querySelector(this.config.templateSelector);
        const container = window.FormularioUtils.querySelector(this.config.containerSelector);
        
        if (!template || !container) {
            window.FormularioUtils.error('Template o contenedor de menores no encontrado');
            return null;
        }
        
        try {
            const clone = document.importNode(template.content, true);
            this.updateFieldIndexes(clone, newIndex);
            this.attachRemoveListener(clone);
            
            container.appendChild(clone);
            this.updateCounter();
            
            if (index === null) {
                window.AccordionManager.open(this.config.accordionId);
                this.focusLastInput();
            }
            
            window.FormularioUtils.dispatchEvent(window.FormularioCore.events.ITEM_ADDED, {
                type: 'minor',
                index: newIndex
            });
            
            return newIndex;
        } catch (error) {
            window.FormularioUtils.error('Error al agregar menor', error);
            return null;
        }
    },
    
    remove: function(element) {
        const row = element.closest(`.${this.config.itemClass}`);
        if (row) {
            row.remove();
            this.updateCounter();
            this.renumberItems();
            
            window.FormularioUtils.dispatchEvent(window.FormularioCore.events.ITEM_REMOVED, {
                type: 'minor'
            });
        }
    },
    
    updateFieldIndexes: function(clone, index) {
        const inputs = clone.querySelectorAll('input, select');
        inputs.forEach(input => {
            const originalName = input.name;
            input.name = input.name.replace('INDEX', index);
            input.setAttribute('data-index', index);
        });
    },
    
    attachRemoveListener: function(clone) {
        const removeBtn = clone.querySelector('.remove-minor-btn');
        if (removeBtn) {
            removeBtn.addEventListener('click', (e) => {
                e.preventDefault();
                this.remove(e.target);
            });
        }
    },
    
    renumberItems: function() {
        const container = window.FormularioUtils.querySelector(this.config.containerSelector);
        if (!container) return;
        
        const rows = container.querySelectorAll(`.${this.config.itemClass}`);
        rows.forEach((row, index) => {
            const inputs = row.querySelectorAll('input, select');
            inputs.forEach(input => {
                const nameParts = input.name.split('[');
                if (nameParts.length > 1) {
                    input.name = `minors[${index}]${input.name.substring(input.name.indexOf(']') + 1)}`;
                    input.setAttribute('data-index', index);
                }
            });
        });
    },
    
    getNextIndex: function() {
        const container = window.FormularioUtils.querySelector(this.config.containerSelector);
        if (!container) return 0;
        return container.querySelectorAll(`.${this.config.itemClass}`).length;
    },
    
    updateCounter: function() {
        const count = this.getNextIndex();
        window.FormularioCounters.update('minors', count);
    },
    
    focusLastInput: function() {
        const container = window.FormularioUtils.querySelector(this.config.containerSelector);
        if (!container) return;
        
        const rows = container.querySelectorAll(`.${this.config.itemClass}`);
        if (rows.length > 0) {
            const lastRow = rows[rows.length - 1];
            const input = lastRow.querySelector(this.config.focusSelector);
            window.FormularioUtils.focusElement(input);
        }
    },
    
    loadData: function(minorsData) {
        if (!minorsData || minorsData.length === 0) {
            window.FormularioUtils.log('No hay menores para cargar');
            return;
        }
        
        window.FormularioUtils.log(`Cargando ${minorsData.length} menores`);
        const container = window.FormularioUtils.querySelector(this.config.containerSelector);
        if (!container) return;
        
        container.innerHTML = '';
        
        minorsData.forEach((minor, index) => {
            this.add(index);
            this.fillData(index, minor);
        });
    },
    
    fillData: function(index, minorData) {
        const container = window.FormularioUtils.querySelector(this.config.containerSelector);
        if (!container) return;
        
        const rows = container.querySelectorAll(`.${this.config.itemClass}`);
        const row = rows[index];
        if (!row) return;
        
        this.fillField(row, 'input[name$="[name]"]', minorData.name);
        this.fillField(row, 'input[name$="[age]"]', minorData.age);
        this.fillField(row, 'select[name$="[gender]"]', minorData.gender);
    },
    
    fillField: function(row, selector, value) {
        const field = row.querySelector(selector);
        if (field && value !== null && value !== undefined) {
            field.value = value;
        }
    }
};