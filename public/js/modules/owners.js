/**
 * Módulo de Propietarios
 * Maneja toda la funcionalidad relacionada con propietarios
 */

window.OwnersModule = {
    config: {
        containerSelector: '#owners-container',
        buttonSelector: '#add-owner-btn',
        templateSelector: '#owner-template',
        counterSelector: '#owners-counter',
        itemClass: 'owner-item',
        accordionId: 'propietarios-header',
        focusSelector: '.owner-input-name'
    },
    
    init: function() {
        window.FormularioUtils.log('Inicializando módulo de propietarios');
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
            window.FormularioUtils.log('Evento de agregar propietario configurado');
        }
    },
    
    add: function(index = null) {
        const newIndex = index !== null ? index : this.getNextIndex();
        window.FormularioUtils.log(`Agregando propietario con índice ${newIndex}`);
        
        const template = window.FormularioUtils.querySelector(this.config.templateSelector);
        const container = window.FormularioUtils.querySelector(this.config.containerSelector);
        
        if (!template || !container) {
            window.FormularioUtils.error('Template o contenedor de propietarios no encontrado');
            return null;
        }
        
        try {
            const clone = document.importNode(template.content, true);
            this.updateFieldIndexes(clone, newIndex);
            this.attachRemoveListener(clone);
            
            container.appendChild(clone);
            this.updateCounter();
            
            // Si es manual, abrir acordeón y enfocar
            if (index === null) {
                window.AccordionManager.open(this.config.accordionId);
                this.focusLastInput();
            }
            
            window.FormularioUtils.dispatchEvent(window.FormularioCore.events.ITEM_ADDED, {
                type: 'owner',
                index: newIndex
            });
            
            return newIndex;
        } catch (error) {
            window.FormularioUtils.error('Error al agregar propietario', error);
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
                type: 'owner'
            });
        }
    },
    
    updateFieldIndexes: function(clone, index) {
        const inputs = clone.querySelectorAll('input, select');
        inputs.forEach(input => {
            const originalName = input.name;
            input.name = input.name.replace('INDEX', index);
            input.setAttribute('data-index', index);
            window.FormularioUtils.log(`Campo renombrado: ${originalName} → ${input.name}`);
        });
    },
    
    attachRemoveListener: function(clone) {
        const removeBtn = clone.querySelector('.remove-owner-btn');
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
                    input.name = `owners[${index}]${input.name.substring(input.name.indexOf(']') + 1)}`;
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
        window.FormularioCounters.update('owners', count);
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
    
    loadData: function(ownersData) {
        if (!ownersData || ownersData.length === 0) {
            window.FormularioUtils.log('No hay propietarios para cargar');
            return;
        }
        
        window.FormularioUtils.log(`Cargando ${ownersData.length} propietarios`);
        const container = window.FormularioUtils.querySelector(this.config.containerSelector);
        if (!container) return;
        
        container.innerHTML = '';
        
        ownersData.forEach((owner, index) => {
            this.add(index);
            this.fillData(index, owner);
        });
    },
    
    fillData: function(index, ownerData) {
        const container = window.FormularioUtils.querySelector(this.config.containerSelector);
        if (!container) return;
        
        const rows = container.querySelectorAll(`.${this.config.itemClass}`);
        const row = rows[index];
        if (!row) return;
        
        this.fillField(row, 'input[name$="[name]"]', ownerData.name);
        this.fillField(row, 'input[name$="[document]"]', ownerData.document_number);
        this.fillField(row, 'input[name$="[phone]"]', ownerData.phone_number);
        this.fillField(row, 'input[name$="[email]"]', ownerData.email);
    },
    
    fillField: function(row, selector, value) {
        const field = row.querySelector(selector);
        if (field && value !== null && value !== undefined) {
            field.value = value;
        }
    }
};