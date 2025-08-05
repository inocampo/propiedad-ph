/**
 * Cargador de Datos
 * Maneja la carga de datos existentes desde el backend
 */

window.DataLoader = {
    init: function() {
        window.FormularioUtils.log('Inicializando cargador de datos');
        
        // Cargar datos con retraso para asegurar que todos los módulos estén listos
        setTimeout(() => {
            this.loadAll();
        }, window.FormularioCore.loadDelay);
    },
    
    loadAll: function() {
        window.FormularioUtils.log('Iniciando carga de datos existentes');
        
        if (!window.apartamentoData) {
            window.FormularioUtils.log('No hay datos del apartamento para cargar');
            return;
        }
        
        const apartamento = window.apartamentoData;
        window.FormularioUtils.log('Apartamento encontrado', apartamento);
        
        try {
            // Cargar datos en cada módulo
            this.loadOwnersData(apartamento.owners || []);
            this.loadResidentsData(apartamento.residents || []);
            this.loadMinorsData(apartamento.minors || []);
            this.loadVehiclesData(apartamento.vehicles || []);
            this.loadPetsData(apartamento.pets || []);
            
            // Actualizar todos los contadores
            window.FormularioCounters.updateAll();
            
            window.FormularioUtils.log('Carga de datos completada');
            window.FormularioUtils.dispatchEvent(window.FormularioCore.events.DATA_LOADED, {
                apartamento: apartamento
            });
        } catch (error) {
            window.FormularioUtils.error('Error al cargar datos existentes', error);
        }

        try {
            // Cargar datos en cada módulo
            this.loadOwnersData(apartamento.owners || []);
            this.loadResidentsData(apartamento.residents || []);
            this.loadMinorsData(apartamento.minors || []);
            this.loadVehiclesData(apartamento.vehicles || []);
            this.loadPetsData(apartamento.pets || []);
            
            // Cargar estado del checkbox received_manual
            this.loadReceivedManualState(apartamento);
            
            // Actualizar todos los contadores
            window.FormularioCounters.updateAll();
            
            window.FormularioUtils.log('Carga de datos completada');
            window.FormularioUtils.dispatchEvent(window.FormularioCore.events.DATA_LOADED, {
                apartamento: apartamento
            });
        } catch (error) {
            window.FormularioUtils.error('Error al cargar datos existentes', error);
        }
    },
    
    loadOwnersData: function(ownersData) {
        if (window.OwnersModule && typeof window.OwnersModule.loadData === 'function') {
            window.OwnersModule.loadData(ownersData);
        } else {
            window.FormularioUtils.error('Módulo de propietarios no disponible');
        }
    },
    
    loadResidentsData: function(residentsData) {
        if (window.ResidentsModule && typeof window.ResidentsModule.loadData === 'function') {
            window.ResidentsModule.loadData(residentsData);
        } else {
            window.FormularioUtils.error('Módulo de residentes no disponible');
        }
    },
    
    loadMinorsData: function(minorsData) {
        if (window.MinorsModule && typeof window.MinorsModule.loadData === 'function') {
            window.MinorsModule.loadData(minorsData);
        } else {
            window.FormularioUtils.error('Módulo de menores no disponible');
        }
    },
    
    loadVehiclesData: function(vehiclesData) {
        if (window.VehiclesModule && typeof window.VehiclesModule.loadData === 'function') {
            window.VehiclesModule.loadData(vehiclesData);
        } else {
            window.FormularioUtils.error('Módulo de vehículos no disponible');
        }
    },
    
    loadPetsData: function(petsData) {
        if (window.PetsModule && typeof window.PetsModule.loadData === 'function') {
            window.PetsModule.loadData(petsData);
        } else {
            window.FormularioUtils.error('Módulo de mascotas no disponible');
        }
    }
};