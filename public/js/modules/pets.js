/**
 * Módulo de Mascotas
 * Maneja toda la funcionalidad relacionada con mascotas
 */

window.PetsModule = {
    config: {
        containerSelector: '#pets-container',
        buttonSelector: '#add-pet-btn',
        templateSelector: '#pet-template',
        counterSelector: '#pets-counter',
        itemClass: 'pet-item',
        accordionId: 'mascotas-header',
        focusSelector: '.pet-input-name'
    },
    
    init: function() {
        window.FormularioUtils.log('Inicializando módulo de mascotas');
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
            window.FormularioUtils.log('Evento de agregar mascota configurado');
        } else {
            window.FormularioUtils.error('Botón de agregar mascota no encontrado');
        }
    },
    
    add: function(index = null) {
        const newIndex = index !== null ? index : this.getNextIndex();
        window.FormularioUtils.log(`Agregando mascota con índice ${newIndex}`);
        
        const template = window.FormularioUtils.querySelector(this.config.templateSelector);
        const container = window.FormularioUtils.querySelector(this.config.containerSelector);
        
        if (!template || !container) {
            window.FormularioUtils.error('Template o contenedor de mascotas no encontrado');
            return null;
        }
        
        try {
            const clone = document.importNode(template.content, true);
            this.updateFieldIndexes(clone, newIndex);
            this.populateBreedOptions(clone);
            this.attachRemoveListener(clone);
            
            container.appendChild(clone);
            this.updateCounter();
            
            if (index === null) {
                window.AccordionManager.open(this.config.accordionId);
                this.focusLastInput();
            }
            
            window.FormularioUtils.dispatchEvent(window.FormularioCore.events.ITEM_ADDED, {
                type: 'pet',
                index: newIndex
            });
            
            window.FormularioUtils.log('Mascota agregada exitosamente');
            return newIndex;
        } catch (error) {
            window.FormularioUtils.error('Error al agregar mascota', error);
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
                type: 'pet'
            });
            
            window.FormularioUtils.log('Mascota eliminada');
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
    
    populateBreedOptions: function(clone) {
        const breedSelect = clone.querySelector('select[name*="[breed_id]"]');
        if (!breedSelect) return;
        
        // Limpiar opciones existentes
        breedSelect.innerHTML = '<option value="">Seleccionar raza...</option>';
        
        // Agregar razas desde los datos globales
        if (window.breedsData && Array.isArray(window.breedsData)) {
            window.breedsData.forEach(breed => {
                const option = document.createElement('option');
                option.value = breed.id;
                option.textContent = breed.name;
                breedSelect.appendChild(option);
            });
            window.FormularioUtils.log(`Agregadas ${window.breedsData.length} opciones de razas`);
        } else {
            window.FormularioUtils.error('Datos de razas no disponibles');
        }
        
        // Agregar opción "Otro"
        const otherOption = document.createElement('option');
        otherOption.value = 'otro';
        otherOption.textContent = 'Otro';
        breedSelect.appendChild(otherOption);
    },
    
    attachRemoveListener: function(clone) {
        const removeBtn = clone.querySelector('.remove-pet-btn');
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
                    input.name = `pets[${index}]${input.name.substring(input.name.indexOf(']') + 1)}`;
                    input.setAttribute('data-index', index);
                }
            });
        });
        window.FormularioUtils.log(`Renumeradas ${rows.length} mascotas`);
    },
    
    getNextIndex: function() {
        const container = window.FormularioUtils.querySelector(this.config.containerSelector);
        if (!container) return 0;
        return container.querySelectorAll(`.${this.config.itemClass}`).length;
    },
    
    updateCounter: function() {
        const count = this.getNextIndex();
        window.FormularioCounters.update('pets', count);
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
    
    loadData: function(petsData) {
        if (!petsData || petsData.length === 0) {
            window.FormularioUtils.log('No hay mascotas para cargar');
            return;
        }
        
        window.FormularioUtils.log(`Cargando ${petsData.length} mascotas`);
        const container = window.FormularioUtils.querySelector(this.config.containerSelector);
        if (!container) return;
        
        container.innerHTML = '';
        
        petsData.forEach((pet, index) => {
            this.add(index);
            this.fillData(index, pet);
        });
    },
    
    fillData: function(index, petData) {
        const container = window.FormularioUtils.querySelector(this.config.containerSelector);
        if (!container) return;
        
        const rows = container.querySelectorAll(`.${this.config.itemClass}`);
        const row = rows[index];
        if (!row) return;
        
        this.fillField(row, 'input[name$="[name]"]', petData.name);
        this.fillField(row, 'select[name$="[type]"]', petData.type);
        this.fillField(row, 'select[name$="[breed_id]"]', petData.breed_id);
    },
    
    fillField: function(row, selector, value) {
        const field = row.querySelector(selector);
        if (field && value !== null && value !== undefined) {
            field.value = value;
        }
    },
    
    // Función para debugging y testing
    test: function() {
        window.FormularioUtils.log('🧪 Probando funcionalidad de mascotas');
        this.add();
    },
    
    // Función para obtener datos actuales
    getData: function() {
        const container = window.FormularioUtils.querySelector(this.config.containerSelector);
        const pets = [];
        
        if (container) {
            const petRows = container.querySelectorAll(`.${this.config.itemClass}`);
            petRows.forEach(row => {
                const name = row.querySelector('input[name*="[name]"]')?.value || '';
                const type = row.querySelector('select[name*="[type]"]')?.value || '';
                const breed = row.querySelector('select[name*="[breed_id]"]')?.value || '';
                
                pets.push({ name, type, breed });
            });
        }
        
        return pets;
    }
};