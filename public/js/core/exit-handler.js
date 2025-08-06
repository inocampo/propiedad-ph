/**
 * Exit Handler - Manejador de "Salir sin Guardar"
 * public/js/core/exit-handler.js
 * 
 * Funcionalidad para permitir al usuario salir del formulario sin guardar
 * con confirmación previa y manejo de cambios no guardados.
 */

window.ExitHandler = {
    // Estado interno
    intentionalExit: false,
    hasUnsavedChanges: false,
    formElement: null,
    
    /**
     * Inicialización del módulo
     */
    init: function() {
        window.FormularioUtils.log('🚪 Inicializando ExitHandler');
        
        this.formElement = document.querySelector('form[method="POST"]');
        if (!this.formElement) {
            window.FormularioUtils.error('No se encontró el formulario principal');
            return;
        }
        
        // Esperar a que el DOM esté completamente cargado
        setTimeout(() => {
            this.createExitButton();
            this.attachExitEvents();
            this.setupUnsavedChangesWarning();
            this.setupFormChangeDetection();
            
            window.FormularioUtils.log('✅ ExitHandler inicializado correctamente');
        }, 100);
    },

    /**
     * Crear el botón "Salir sin Guardar" dinámicamente
     */
    createExitButton: function() {
        // Verificar si el botón ya existe
        if (document.getElementById('btn-exit-no-save')) {
            window.FormularioUtils.log('⚠️ Botón "Salir sin guardar" ya existe');
            return;
        }

        // Buscar el contenedor de botones - probamos diferentes selectores
        let buttonContainer = document.querySelector('.flex.flex-col.md\\:flex-row.items-center.justify-center.gap-4.mt-8');
        
        if (!buttonContainer) {
            // Buscar contenedor alternativo
            buttonContainer = document.querySelector('div.flex.flex-col');
            if (buttonContainer && !buttonContainer.textContent.includes('Guardar')) {
                buttonContainer = null;
            }
        }
        
        if (!buttonContainer) {
            // Buscar el formulario y agregar el contenedor
            const form = document.querySelector('form[method="POST"]');
            if (form) {
                buttonContainer = document.createElement('div');
                buttonContainer.className = 'flex flex-col md:flex-row items-center justify-center gap-4 mt-8';
                
                // Buscar los botones existentes
                const saveButtons = form.querySelectorAll('button[type="submit"]');
                if (saveButtons.length > 0) {
                    const lastButton = saveButtons[saveButtons.length - 1];
                    const parentDiv = lastButton.closest('div.flex');
                    if (parentDiv) {
                        buttonContainer = parentDiv;
                    }
                }
            }
        }

        if (!buttonContainer) {
            window.FormularioUtils.error('No se encontró el contenedor de botones para agregar el botón de salida');
            return;
        }

        // Crear el botón de salir sin guardar
        const exitButton = document.createElement('button');
        exitButton.type = 'button';
        exitButton.id = 'btn-exit-no-save';
        exitButton.className = 'w-full md:w-auto bg-red-500 hover:bg-red-600 text-white font-bold py-3 px-6 rounded-lg focus:outline-none focus:shadow-outline transition-colors duration-200 flex items-center justify-center border-2 border-transparent hover:border-red-700';
        
        exitButton.innerHTML = `
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
            </svg>
            Salir sin Guardar
        `;

        // Insertar el botón al final del contenedor
        buttonContainer.appendChild(exitButton);

        // Actualizar el texto de ayuda
        this.updateHelpText();

        window.FormularioUtils.log('✅ Botón "Salir sin guardar" creado exitosamente');
        return exitButton;
    },

    /**
     * Actualizar el texto de ayuda para incluir el nuevo botón
     */
    updateHelpText: function() {
        const helpContainer = document.querySelector('.text-center.mt-4');
        if (helpContainer) {
            const helpParagraph = helpContainer.querySelector('p');
            if (helpParagraph) {
                helpParagraph.innerHTML = `
                    <strong>Guardar y Continuar:</strong> Guarda los cambios y permite seguir editando.<br>
                    <strong>Guardar y Finalizar:</strong> Guarda los cambios y regresa al inicio.<br>
                    <strong>Salir sin Guardar:</strong> Sale del formulario sin guardar los cambios realizados.
                `;
            }
        }
    },

    /**
     * Configurar eventos del botón de salida
     */
    attachExitEvents: function() {
        // Usar event delegation para manejar el botón dinámico
        document.addEventListener('click', (e) => {
            if (e.target && e.target.id === 'btn-exit-no-save') {
                e.preventDefault();
                e.stopPropagation();
                this.handleExitClick();
                return false;
            }
            
            // También manejar clicks en elementos internos del botón
            const exitButton = e.target.closest('#btn-exit-no-save');
            if (exitButton) {
                e.preventDefault();
                e.stopPropagation();
                this.handleExitClick();
                return false;
            }
        });

        window.FormularioUtils.log('✅ Eventos del botón de salida configurados con delegation');
    },

    /**
     * Manejar el clic en el botón de salir
     */
    handleExitClick: function() {
        window.FormularioUtils.log('🖱️ Usuario hizo clic en "Salir sin guardar"');
        
        // Debug: verificar estado
        console.log('Estado de cambios:', this.hasUnsavedChanges);
        
        // Si no hay cambios, salir directamente
        if (!this.hasUnsavedChanges) {
            window.FormularioUtils.log('📤 No hay cambios, saliendo directamente');
            this.exitDirectly();
            return;
        }
        
        // Mostrar confirmación si hay cambios
        window.FormularioUtils.log('⚠️ Hay cambios, mostrando confirmación');
        this.showExitConfirmation();
    },

    /**
     * Salir directamente sin confirmación
     */
    exitDirectly: function() {
        window.FormularioUtils.log('📤 Saliendo directamente (sin cambios)');
        this.performExit();
    },

    /**
     * Mostrar modal de confirmación
     */
    showExitConfirmation: function() {
        // Verificar que no existe ya un modal
        if (document.getElementById('exit-confirmation-modal')) {
            window.FormularioUtils.log('⚠️ Modal ya existe, no creando duplicado');
            return;
        }

        window.FormularioUtils.log('🗨️ Creando modal de confirmación');
        const modal = this.createConfirmationModal();
        document.body.appendChild(modal);

        // Animación de entrada
        requestAnimationFrame(() => {
            modal.classList.remove('opacity-0');
            modal.querySelector('.modal-content').classList.remove('scale-95');
            modal.querySelector('.modal-content').classList.add('scale-100');
        });

        // Focus en el botón de cancelar para mejor accesibilidad
        setTimeout(() => {
            const cancelButton = modal.querySelector('#cancel-exit-btn');
            if (cancelButton) {
                cancelButton.focus();
                window.FormularioUtils.log('👆 Focus establecido en botón cancelar');
            }
        }, 100);
    },

    /**
     * Crear el modal de confirmación
     */
    createConfirmationModal: function() {
        const modal = document.createElement('div');
        modal.id = 'exit-confirmation-modal';
        modal.className = 'fixed inset-0 bg-gray-600 bg-opacity-75 overflow-y-auto h-full w-full z-50 flex items-center justify-center opacity-0 transition-opacity duration-300';
        
        modal.innerHTML = `
            <div class="modal-content relative bg-white rounded-lg shadow-xl transform scale-95 transition-transform duration-300 max-w-md w-full mx-4">
                <!-- Header -->
                <div class="flex items-center justify-between p-6 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                        <span class="text-yellow-500 mr-2">⚠️</span>
                        Confirmar Salida
                    </h3>
                </div>
                
                <!-- Body -->
                <div class="p-6">
                    <div class="text-center">
                        <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-red-100 mb-4">
                            <svg class="h-8 w-8 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.732-.833-2.5 0L3.314 16.5c-.77.833.192 2.5 1.732 2.5z" />
                            </svg>
                        </div>
                        <h4 class="text-lg font-medium text-gray-900 mb-3">
                            ¿Estás seguro de que deseas salir?
                        </h4>
                        <p class="text-gray-600 text-base mb-3">
                            Has realizado cambios en el formulario que no han sido guardados.
                        </p>
                        <div class="bg-red-50 border border-red-200 rounded-lg p-3">
                            <p class="text-red-700 text-sm font-medium">
                                ⚠️ Todos los cambios no guardados se perderán permanentemente.
                            </p>
                        </div>
                    </div>
                </div>
                
                <!-- Footer -->
                <div class="flex flex-col sm:flex-row gap-3 p-6 border-t border-gray-200 justify-end">
                    <button id="cancel-exit-btn" type="button" 
                            class="w-full sm:w-auto bg-gray-100 hover:bg-gray-200 text-gray-800 font-medium py-2 px-4 rounded-lg focus:outline-none focus:ring-2 focus:ring-gray-300 transition-colors duration-200 flex items-center justify-center">
                        <span class="mr-1">↩️</span>
                        Continuar Editando
                    </button>
                    <button id="confirm-exit-btn" type="button" 
                            class="w-full sm:w-auto bg-red-500 hover:bg-red-600 text-white font-medium py-2 px-4 rounded-lg focus:outline-none focus:ring-2 focus:ring-red-400 transition-colors duration-200 flex items-center justify-center">
                        <span class="mr-1">🚪</span>
                        Salir sin Guardar
                    </button>
                </div>
            </div>
        `;

        // Configurar eventos del modal
        this.attachModalEvents(modal);
        
        return modal;
    },

    /**
     * Configurar eventos del modal
     */
    attachModalEvents: function(modal) {
        const cancelButton = modal.querySelector('#cancel-exit-btn');
        const confirmButton = modal.querySelector('#confirm-exit-btn');

        // Evento de cancelar
        if (cancelButton) {
            cancelButton.addEventListener('click', (e) => {
                e.preventDefault();
                window.FormularioUtils.log('❌ Usuario canceló la salida');
                this.closeModal(modal);
            });
        }

        // Evento de confirmar
        if (confirmButton) {
            confirmButton.addEventListener('click', (e) => {
                e.preventDefault();
                window.FormularioUtils.log('✅ Usuario confirmó la salida');
                this.confirmExit(modal);
            });
        }

        // Cerrar con click fuera del modal
        modal.addEventListener('click', (e) => {
            if (e.target === modal) {
                window.FormularioUtils.log('❌ Cerrando modal por click fuera');
                this.closeModal(modal);
            }
        });

        // Cerrar con tecla Escape
        const escapeHandler = (e) => {
            if (e.key === 'Escape') {
                window.FormularioUtils.log('❌ Cerrando modal con Escape');
                this.closeModal(modal);
                document.removeEventListener('keydown', escapeHandler);
            }
        };
        document.addEventListener('keydown', escapeHandler);

        window.FormularioUtils.log('✅ Eventos del modal configurados');
    },

    /**
     * Cerrar el modal con animación
     */
    closeModal: function(modal) {
        modal.classList.add('opacity-0');
        modal.querySelector('.modal-content').classList.remove('scale-100');
        modal.querySelector('.modal-content').classList.add('scale-95');

        setTimeout(() => {
            if (modal.parentNode) {
                modal.parentNode.removeChild(modal);
                window.FormularioUtils.log('🗑️ Modal removido del DOM');
            }
        }, 300);

        window.FormularioUtils.log('❌ Modal de confirmación cerrado');
    },

    /**
     * Confirmar la salida
     */
    confirmExit: function(modal) {
        window.FormularioUtils.log('✅ Usuario confirmó salir sin guardar');
        
        // Cerrar el modal primero
        this.closeModal(modal);
        
        // Mostrar mensaje de salida
        this.showExitingMessage();
        
        // Realizar la salida después de un breve delay
        setTimeout(() => {
            this.performExit();
        }, 1500);
    },

    /**
     * Mostrar mensaje de salida
     */
    showExitingMessage: function() {
        const loadingOverlay = document.createElement('div');
        loadingOverlay.id = 'exit-loading-overlay';
        loadingOverlay.className = 'fixed inset-0 bg-gray-800 bg-opacity-75 flex items-center justify-center z-50';
        
        loadingOverlay.innerHTML = `
            <div class="bg-white rounded-lg shadow-xl p-8 text-center max-w-sm mx-4">
                <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-red-500 mx-auto mb-4"></div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Saliendo del formulario...</h3>
                <p class="text-gray-600">Regresando al inicio</p>
            </div>
        `;

        document.body.appendChild(loadingOverlay);
        window.FormularioUtils.log('⏳ Mostrando mensaje de salida');
    },

    /**
     * Realizar la salida efectiva
     */
    performExit: function() {
        // Marcar como salida intencional
        this.intentionalExit = true;
        
        // Limpiar datos de sesión
        this.clearSessionData();
        
        window.FormularioUtils.log('🚪 Redirigiendo a /residentes');
        
        // Redirigir al inicio
        window.location.href = '/residentes';
    },

    /**
     * Limpiar datos de sesión
     */
    clearSessionData: function() {
        const keysToRemove = [
            'unsavedChanges',
            'openAccordions', 
            'scrollPosition',
            'formState'
        ];
        
        keysToRemove.forEach(key => {
            sessionStorage.removeItem(key);
        });
        
        window.FormularioUtils.log('🧹 Datos de sesión limpiados');
    },

    /**
     * Configurar detección de cambios en el formulario
     */
    setupFormChangeDetection: function() {
        if (!this.formElement) return;

        // Escuchar cambios en inputs
        this.formElement.addEventListener('input', (e) => {
            this.markAsChanged();
        });

        // Escuchar cambios en selects
        this.formElement.addEventListener('change', (e) => {
            this.markAsChanged();
        });

        // Marcar como guardado cuando se envía el formulario normalmente
        this.formElement.addEventListener('submit', () => {
            this.intentionalExit = true;
            this.hasUnsavedChanges = false;
        });

        window.FormularioUtils.log('✅ Detección de cambios configurada');
    },

    /**
     * Marcar formulario como modificado
     */
    markAsChanged: function() {
        if (!this.hasUnsavedChanges) {
            this.hasUnsavedChanges = true;
            sessionStorage.setItem('unsavedChanges', 'true');
            window.FormularioUtils.log('📝 Formulario marcado como modificado');
        }
    },

    /**
     * Configurar advertencia antes de cerrar pestaña/ventana
     */
    setupUnsavedChangesWarning: function() {
        window.addEventListener('beforeunload', (e) => {
            if (this.hasUnsavedChanges && !this.intentionalExit) {
                e.preventDefault();
                e.returnValue = '¿Estás seguro de que quieres salir? Los cambios no guardados se perderán.';
                return e.returnValue;
            }
        });

        window.FormularioUtils.log('✅ Advertencia de cierre de ventana configurada');
    },

    /**
     * Método público para integración con otros módulos
     */
    hasChanges: function() {
        return this.hasUnsavedChanges;
    },

    /**
     * Método público para marcar como guardado (para otros módulos)
     */
    markAsSaved: function() {
        this.hasUnsavedChanges = false;
        sessionStorage.removeItem('unsavedChanges');
        window.FormularioUtils.log('💾 Formulario marcado como guardado');
    },

    /**
     * Método de debug para probar el modal
     */
    testModal: function() {
        this.hasUnsavedChanges = true;
        this.showExitConfirmation();
    },

    /**
     * Método de debug para forzar cambios
     */
    simulateChanges: function() {
        this.markAsChanged();
        window.FormularioUtils.log('🧪 Cambios simulados para testing');
    }
};