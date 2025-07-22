/**
 * Módulo de Residentes
 * Maneja toda la funcionalidad relacionada con residentes
 */

window.ResidentsModule = {
    config: {
        containerSelector: '#residents-container',
        buttonSelector: '#add-resident-btn',
        templateSelector: '#resident-template',
        counterSelector: '#residents-counter',
        itemClass: 'resident-item',
        accordionId: 'residentes-header',
        focusSelector: '.resident-input-name'
    },
    
    init: function() {
        window.FormularioUtils.log('Inicializando módulo de residentes');
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
            window.FormularioUtils.log('Evento de agregar residente configurado');
        }
    },
    
    add: function(index = null) {
        const newIndex = index !== null ? index : this.getNextIndex();
        window.FormularioUtils.log(`Agregando residente con índice ${newIndex}`);
        
        const template = window.FormularioUtils.querySelector(this.config.templateSelector);
        const container = window.FormularioUtils.querySelector(this.config.containerSelector);
        
        if (!template || !container) {
            window.FormularioUtils.error('Template o contenedor de residentes no encontrado');
            return null;
        }
        
        try {
            const clone = document.importNode(template.content, true);
            this.updateFieldIndexes(clone, newIndex);
            this.populateRelationshipOptions(clone);
            this.attachRemoveListener(clone);
            
            container.appendChild(clone);
            this.updateCounter();
            
            if (index === null) {
                window.AccordionManager.open(this.config.accordionId);
                this.focusLastInput();
            }
            
            window.FormularioUtils.dispatchEvent(window.FormularioCore.events.ITEM_ADDED, {
                type: 'resident',
                index: newIndex
            });
            
            return newIndex;
        } catch (error) {
            window.FormularioUtils.error('Error al agregar residente', error);
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
                type: 'resident'
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
    
    populateRelationshipOptions: function(clone) {
        const relationshipSelect = clone.querySelector('select[name*="[relationship_id]"]');
        if (!relationshipSelect) return;
        
        // Limpiar opciones existentes
        relationshipSelect.innerHTML = '<option value="">Seleccionar parentesco...</option>';
        
        // Agregar relaciones desde los datos globales
        if (window.relationshipsData && Array.isArray(window.relationshipsData)) {
            window.relationshipsData.forEach(relationship => {
                const option = document.createElement('option');
                option.value = relationship.id;
                option.textContent = relationship.name;
                relationshipSelect.appendChild(option);
            });
            window.FormularioUtils.log(`Agregadas ${window.relationshipsData.length} opciones de parentesco`);
        } else {
            window.FormularioUtils.error('Datos de parentesco no disponibles');
        }
    },
    
    attachRemoveListener: function(clone) {
        const removeBtn = clone.querySelector('.remove-resident-btn');
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
                    input.name = `residents[${index}]${input.name.substring(input.name.indexOf(']') + 1)}`;
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
        window.FormularioCounters.update('residents', count);
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
    
    loadData: function(residentsData) {
        if (!residentsData || residentsData.length === 0) {
            window.FormularioUtils.log('No hay residentes para cargar');
            return;
        }
        
        window.FormularioUtils.log(`Cargando ${residentsData.length} residentes`);
        const container = window.FormularioUtils.querySelector(this.config.containerSelector);
        if (!container) return;
        
        container.innerHTML = '';
        
        residentsData.forEach((resident, index) => {
            this.add(index);
            this.fillData(index, resident);
        });
    },
    
    fillData: function(index, residentData) {
        const container = window.FormularioUtils.querySelector(this.config.containerSelector);
        if (!container) return;
        
        const rows = container.querySelectorAll(`.${this.config.itemClass}`);
        const row = rows[index];
        if (!row) return;
        
        this.fillField(row, 'input[name$="[name]"]', residentData.name);
        this.fillField(row, 'input[name$="[document]"]', residentData.document_number);
        this.fillField(row, 'input[name$="[phone]"]', residentData.phone_number);
        this.fillField(row, 'select[name$="[relationship_id]"]', residentData.relationship_id);
    },
    
    fillField: function(row, selector, value) {
        const field = row.querySelector(selector);
        if (field && value !== null && value !== undefined) {
            field.value = value;
        }
    }
};