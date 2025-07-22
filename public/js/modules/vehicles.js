/**
 * Módulo de Vehículos
 * Maneja toda la funcionalidad relacionada con vehículos
 */

window.VehiclesModule = {
    config: {
        containerSelector: '#vehicles-container',
        buttonSelector: '#add-vehicle-btn',
        templateSelector: '#vehicle-template',
        counterSelector: '#vehicles-counter',
        itemClass: 'vehicle-item',
        accordionId: 'vehiculos-header',
        focusSelector: '.vehicle-input-type'
    },
    
    init: function() {
        window.FormularioUtils.log('Inicializando módulo de vehículos');
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
            window.FormularioUtils.log('Evento de agregar vehículo configurado');
        }
    },
    
    add: function(index = null) {
        const newIndex = index !== null ? index : this.getNextIndex();
        window.FormularioUtils.log(`Agregando vehículo con índice ${newIndex}`);
        
        const template = window.FormularioUtils.querySelector(this.config.templateSelector);
        const container = window.FormularioUtils.querySelector(this.config.containerSelector);
        
        if (!template || !container) {
            window.FormularioUtils.error('Template o contenedor de vehículos no encontrado');
            return null;
        }
        
        try {
            const clone = document.importNode(template.content, true);
            this.updateFieldIndexes(clone, newIndex);
            this.populateVehicleOptions(clone);
            this.attachRemoveListener(clone);
            
            container.appendChild(clone);
            this.updateCounter();
            
            if (index === null) {
                window.AccordionManager.open(this.config.accordionId);
                this.focusLastInput();
            }
            
            window.FormularioUtils.dispatchEvent(window.FormularioCore.events.ITEM_ADDED, {
                type: 'vehicle',
                index: newIndex
            });
            
            return newIndex;
        } catch (error) {
            window.FormularioUtils.error('Error al agregar vehículo', error);
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
                type: 'vehicle'
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
    
    populateVehicleOptions: function(clone) {
        this.populateBrandOptions(clone);
        this.populateColorOptions(clone);
    },
    
    populateBrandOptions: function(clone) {
        const brandSelect = clone.querySelector('select[name*="[brand_id]"]');
        if (!brandSelect) return;
        
        // Limpiar opciones existentes
        brandSelect.innerHTML = '<option value="">Seleccione marca...</option>';
        
        // Agregar marcas desde los datos globales
        if (window.brandsData && Array.isArray(window.brandsData)) {
            window.brandsData.forEach(brand => {
                const option = document.createElement('option');
                option.value = brand.id;
                option.textContent = brand.name;
                brandSelect.appendChild(option);
            });
            window.FormularioUtils.log(`Agregadas ${window.brandsData.length} opciones de marcas`);
        } else {
            window.FormularioUtils.error('Datos de marcas no disponibles');
        }
        
        // Agregar opción "Otro"
        const otherOption = document.createElement('option');
        otherOption.value = 'otro';
        otherOption.textContent = 'Otro';
        brandSelect.appendChild(otherOption);
    },
    
    populateColorOptions: function(clone) {
        const colorSelect = clone.querySelector('select[name*="[color_id]"]');
        if (!colorSelect) return;
        
        // Limpiar opciones existentes
        colorSelect.innerHTML = '<option value="">Seleccione color...</option>';
        
        // Agregar colores desde los datos globales
        if (window.colorsData && Array.isArray(window.colorsData)) {
            window.colorsData.forEach(color => {
                const option = document.createElement('option');
                option.value = color.id;
                option.textContent = color.name;
                colorSelect.appendChild(option);
            });
            window.FormularioUtils.log(`Agregadas ${window.colorsData.length} opciones de colores`);
        } else {
            window.FormularioUtils.error('Datos de colores no disponibles');
        }
        
        // Agregar opción "Otro"
        const otherOption = document.createElement('option');
        otherOption.value = 'otro';
        otherOption.textContent = 'Otro';
        colorSelect.appendChild(otherOption);
    },
    
    attachRemoveListener: function(clone) {
        const removeBtn = clone.querySelector('.remove-vehicle-btn');
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
                    input.name = `vehicles[${index}]${input.name.substring(input.name.indexOf(']') + 1)}`;
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
        window.FormularioCounters.update('vehicles', count);
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
    
    loadData: function(vehiclesData) {
        if (!vehiclesData || vehiclesData.length === 0) {
            window.FormularioUtils.log('No hay vehículos para cargar');
            return;
        }
        
        window.FormularioUtils.log(`Cargando ${vehiclesData.length} vehículos`);
        const container = window.FormularioUtils.querySelector(this.config.containerSelector);
        if (!container) return;
        
        container.innerHTML = '';
        
        vehiclesData.forEach((vehicle, index) => {
            this.add(index);
            this.fillData(index, vehicle);
        });
    },
    
    fillData: function(index, vehicleData) {
        const container = window.FormularioUtils.querySelector(this.config.containerSelector);
        if (!container) return;
        
        const rows = container.querySelectorAll(`.${this.config.itemClass}`);
        const row = rows[index];
        if (!row) return;
        
        this.fillField(row, 'select[name$="[type]"]', vehicleData.type);
        this.fillField(row, 'input[name$="[license_plate]"]', vehicleData.license_plate);
        this.fillField(row, 'select[name$="[brand_id]"]', vehicleData.brand_id);
        this.fillField(row, 'select[name$="[color_id]"]', vehicleData.color_id);
    },
    
    fillField: function(row, selector, value) {
        const field = row.querySelector(selector);
        if (field && value !== null && value !== undefined) {
            field.value = value;
        }
    }
};