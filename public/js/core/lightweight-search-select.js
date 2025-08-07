/**
 * Lightweight Search Select - VERSIÓN OPTIMIZADA PARA MÓVILES
 * public/js/core/lightweight-search-select.js
 * 
 * Búsqueda optimizada que funciona perfectamente en dispositivos móviles
 */

window.LightweightSearchSelect = {
    instances: new Map(),
    isMobile: /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent),
    
    init: function() {
        window.FormularioUtils.log(`🔍 Inicializando LightweightSearchSelect ${this.isMobile ? '(MODO MÓVIL)' : '(MODO DESKTOP)'}`);
        
        setTimeout(() => {
            this.initializeExistingSelects();
            this.setupDynamicHandling();
        }, 200);
        
        window.FormularioUtils.log('✅ LightweightSearchSelect inicializado');
    },

    initializeExistingSelects: function() {
        const selects = document.querySelectorAll(
            'select[name*="relationship"], select[name*="parentesco"], ' +
            'select[name*="brand"], select[name*="color"], select[name*="breed"]'
        );
        
        selects.forEach(select => this.enhanceSelect(select));
        
        window.FormularioUtils.log(`🔍 ${selects.length} selects mejorados para ${this.isMobile ? 'móvil' : 'desktop'}`);
    },

    setupDynamicHandling: function() {
        const observer = new MutationObserver((mutations) => {
            mutations.forEach((mutation) => {
                if (mutation.type === 'childList') {
                    mutation.addedNodes.forEach((node) => {
                        if (node.nodeType === Node.ELEMENT_NODE) {
                            const selects = node.querySelectorAll ? 
                                node.querySelectorAll(
                                    'select[name*="relationship"], select[name*="parentesco"], ' +
                                    'select[name*="brand"], select[name*="color"], select[name*="breed"]'
                                ) : [];
                            
                            selects.forEach(select => {
                                setTimeout(() => this.enhanceSelect(select), 100);
                            });
                        }
                    });
                }
            });
        });

        ['#residents-container', '#vehicles-container', '#pets-container'].forEach(containerId => {
            const container = document.querySelector(containerId);
            if (container) {
                observer.observe(container, { childList: true, subtree: true });
            }
        });
    },

    enhanceSelect: function(selectElement) {
        if (!selectElement || this.instances.has(selectElement)) {
            return;
        }

        console.log('🔧 Mejorando select para móvil:', selectElement.name);

        // En móviles, usamos un enfoque diferente
        if (this.isMobile) {
            this.enhanceSelectForMobile(selectElement);
        } else {
            this.enhanceSelectForDesktop(selectElement);
        }
    },

    enhanceSelectForMobile: function(selectElement) {
        // Crear botón de búsqueda
        const searchButton = document.createElement('button');
        searchButton.type = 'button';
        searchButton.className = 'search-trigger-btn';
        searchButton.innerHTML = `
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
            <span class="ml-1 text-xs">Buscar</span>
        `;
        
        // Estilos del botón
        searchButton.style.cssText = `
            position: absolute;
            top: 0;
            right: 0;
            height: 100%;
            padding: 0 0.5rem;
            background: #3b82f6;
            color: white;
            border: none;
            border-radius: 0 0.375rem 0.375rem 0;
            font-size: 0.75rem;
            display: flex;
            align-items: center;
            z-index: 2;
            touch-action: manipulation;
        `;

        // Crear wrapper
        const wrapper = document.createElement('div');
        wrapper.className = 'search-select-wrapper mobile-search';
        wrapper.style.cssText = 'position: relative; display: flex;';
        
        // Ajustar el select para dar espacio al botón
        selectElement.style.paddingRight = '4.5rem';
        
        // Insertar elementos
        selectElement.parentNode.insertBefore(wrapper, selectElement);
        wrapper.appendChild(selectElement);
        wrapper.appendChild(searchButton);
        
        // Capturar opciones
        setTimeout(() => {
            const originalOptions = this.captureOriginalOptions(selectElement);
            
            // Guardar instancia
            this.instances.set(selectElement, {
                wrapper,
                searchButton,
                originalOptions,
                isSearchActive: false,
                isMobile: true
            });
            
            // Configurar eventos para móvil
            this.setupMobileEvents(selectElement);
            
            console.log(`✅ Select móvil ${selectElement.name} mejorado con ${originalOptions.length} opciones`);
        }, 50);
    },

    enhanceSelectForDesktop: function(selectElement) {
        // Crear wrapper con clase específica
        const wrapper = document.createElement('div');
        wrapper.className = 'search-select-wrapper desktop-search';
        wrapper.style.cssText = 'position: relative; display: inline-block; width: 100%;';
        
        const searchInput = document.createElement('input');
        searchInput.type = 'text';
        searchInput.className = selectElement.className + ' search-input';
        searchInput.placeholder = this.getPlaceholder(selectElement);
        searchInput.style.display = 'none';
        searchInput.style.fontSize = '16px'; // Prevenir zoom en iOS
        
        // Añadir indicador SOLO después de crear el wrapper
        selectElement.parentNode.insertBefore(wrapper, selectElement);
        wrapper.appendChild(selectElement);
        wrapper.appendChild(searchInput);
        
        // Añadir indicador después de que esté en el wrapper
        this.addSearchIndicator(selectElement);
        
        setTimeout(() => {
            const originalOptions = this.captureOriginalOptions(selectElement);
            
            this.instances.set(selectElement, {
                wrapper,
                searchInput,
                originalOptions,
                isSearchActive: false,
                isMobile: false
            });
            
            this.setupDesktopEvents(selectElement);
            
            console.log(`✅ Select desktop ${selectElement.name} mejorado con ${originalOptions.length} opciones`);
        }, 50);
    },

    setupMobileEvents: function(selectElement) {
        const instance = this.instances.get(selectElement);
        if (!instance) return;
        
        const { searchButton } = instance;
        
        // Tap en el botón de búsqueda
        searchButton.addEventListener('touchstart', (e) => {
            e.preventDefault();
            e.stopPropagation();
            this.openMobileSearchModal(selectElement);
        });
        
        // Fallback para click
        searchButton.addEventListener('click', (e) => {
            e.preventDefault();
            e.stopPropagation();
            this.openMobileSearchModal(selectElement);
        });
        
        // Prevenir que el select normal se abra cuando tocamos el botón
        selectElement.addEventListener('touchstart', (e) => {
            const rect = selectElement.getBoundingClientRect();
            const touchX = e.touches[0].clientX - rect.left;
            
            if (touchX > rect.width - 80) { // Área del botón
                e.preventDefault();
                e.stopPropagation();
                return false;
            }
        });
    },

    setupDesktopEvents: function(selectElement) {
        const instance = this.instances.get(selectElement);
        if (!instance) return;
        
        const { searchInput, wrapper } = instance;
        
        // Doble click para activar búsqueda
        selectElement.addEventListener('dblclick', (e) => {
            e.preventDefault();
            this.activateDesktopSearch(selectElement);
        });
        
        // Click en el área del indicador (ajustado para la nueva posición)
        selectElement.addEventListener('click', (e) => {
            const rect = selectElement.getBoundingClientRect();
            const clickX = e.clientX - rect.left;
            
            // Área más pequeña, solo en los últimos 30px
            if (clickX > rect.width - 30) {
                e.preventDefault();
                e.stopPropagation();
                this.activateDesktopSearch(selectElement);
                return false;
            }
        });
        
        // Eventos del input
        searchInput.addEventListener('input', (e) => {
            this.performDesktopSearch(selectElement, e.target.value);
        });
        
        searchInput.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                e.preventDefault();
                this.deactivateDesktopSearch(selectElement);
            } else if (e.key === 'Enter') {
                e.preventDefault();
                this.selectFirstDesktopMatch(selectElement);
            }
        });
        
        searchInput.addEventListener('blur', () => {
            setTimeout(() => {
                this.deactivateDesktopSearch(selectElement);
            }, 200);
        });
        
        // Tooltip mejorado en hover
        let tooltipTimeout;
        wrapper.addEventListener('mouseenter', () => {
            if (!instance.isSearchActive) {
                tooltipTimeout = setTimeout(() => {
                    this.showDesktopTooltip(wrapper, 'Doble click o click en 🔍 para buscar');
                }, 800); // Delay de 800ms
            }
        });
        
        wrapper.addEventListener('mouseleave', () => {
            if (tooltipTimeout) {
                clearTimeout(tooltipTimeout);
            }
            this.hideDesktopTooltip(wrapper);
        });
    },

    showDesktopTooltip: function(wrapper, text) {
        // Remover tooltip anterior si existe
        this.hideDesktopTooltip(wrapper);
        
        const tooltip = document.createElement('div');
        tooltip.className = 'desktop-search-tooltip';
        tooltip.textContent = text;
        tooltip.style.cssText = `
            position: absolute;
            bottom: calc(100% + 0.5rem);
            left: 50%;
            transform: translateX(-50%);
            background: #1f2937;
            color: white;
            padding: 0.5rem 0.75rem;
            border-radius: 0.375rem;
            font-size: 0.75rem;
            white-space: nowrap;
            z-index: 1010;
            font-weight: 500;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            opacity: 0;
            transition: opacity 0.2s ease, transform 0.2s ease;
            pointer-events: none;
        `;
        
        // Crear la flecha
        const arrow = document.createElement('div');
        arrow.style.cssText = `
            position: absolute;
            top: 100%;
            left: 50%;
            transform: translateX(-50%);
            border: 4px solid transparent;
            border-top-color: #1f2937;
        `;
        tooltip.appendChild(arrow);
        
        wrapper.appendChild(tooltip);
        
        // Animar entrada
        requestAnimationFrame(() => {
            tooltip.style.opacity = '1';
            tooltip.style.transform = 'translateX(-50%) translateY(-2px)';
        });
    },

    hideDesktopTooltip: function(wrapper) {
        const tooltip = wrapper.querySelector('.desktop-search-tooltip');
        if (tooltip) {
            tooltip.style.opacity = '0';
            tooltip.style.transform = 'translateX(-50%) translateY(2px)';
            setTimeout(() => {
                if (tooltip.parentNode) {
                    tooltip.remove();
                }
            }, 200);
        }
    },

    openMobileSearchModal: function(selectElement) {
        const instance = this.instances.get(selectElement);
        if (!instance) return;
        
        console.log('📱 Abriendo modal de búsqueda móvil');
        
        // Crear overlay modal
        const modal = this.createMobileModal(selectElement);
        document.body.appendChild(modal);
        
        // Animar entrada
        requestAnimationFrame(() => {
            modal.classList.add('modal-visible');
            const searchInput = modal.querySelector('.mobile-search-input');
            if (searchInput) {
                // Delay para asegurar que el teclado se muestre correctamente
                setTimeout(() => {
                    searchInput.focus();
                }, 100);
            }
        });
        
        // Prevenir scroll del body
        document.body.style.overflow = 'hidden';
        
        // Guardar referencia del modal
        instance.modal = modal;
    },

    createMobileModal: function(selectElement) {
        const instance = this.instances.get(selectElement);
        if (!instance) return;
        
        const { originalOptions } = instance;
        
        const modal = document.createElement('div');
        modal.className = 'mobile-search-modal';
        modal.style.cssText = `
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5);
            z-index: 1000;
            display: flex;
            flex-direction: column;
            opacity: 0;
            transition: opacity 0.3s ease;
        `;
        
        const modalContent = document.createElement('div');
        modalContent.className = 'modal-content';
        modalContent.style.cssText = `
            background: white;
            margin: 20px;
            margin-top: 60px;
            border-radius: 0.5rem;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            max-height: calc(100vh - 120px);
            transform: translateY(-20px);
            transition: transform 0.3s ease;
        `;
        
        // Header del modal
        const header = document.createElement('div');
        header.className = 'modal-header';
        header.style.cssText = `
            padding: 1rem;
            border-bottom: 1px solid #e5e7eb;
            display: flex;
            align-items: center;
            background: #f9fafb;
        `;
        
        header.innerHTML = `
            <h3 style="margin: 0; font-size: 1.1rem; font-weight: 600; color: #374151; flex: 1;">
                ${this.getModalTitle(selectElement)}
            </h3>
            <button class="close-modal-btn" style="
                padding: 0.5rem;
                background: none;
                border: none;
                color: #6b7280;
                font-size: 1.5rem;
                cursor: pointer;
                touch-action: manipulation;
            ">×</button>
        `;
        
        // Input de búsqueda
        const searchContainer = document.createElement('div');
        searchContainer.style.cssText = 'padding: 1rem; border-bottom: 1px solid #e5e7eb;';
        
        const searchInput = document.createElement('input');
        searchInput.className = 'mobile-search-input';
        searchInput.type = 'text';
        searchInput.placeholder = this.getPlaceholder(selectElement);
        searchInput.style.cssText = `
            width: 100%;
            padding: 0.75rem;
            border: 2px solid #d1d5db;
            border-radius: 0.5rem;
            font-size: 16px;
            outline: none;
            transition: border-color 0.2s;
        `;
        
        searchInput.addEventListener('focus', () => {
            searchInput.style.borderColor = '#3b82f6';
        });
        
        searchInput.addEventListener('blur', () => {
            searchInput.style.borderColor = '#d1d5db';
        });
        
        searchContainer.appendChild(searchInput);
        
        // Lista de resultados
        const resultsList = document.createElement('div');
        resultsList.className = 'results-list';
        resultsList.style.cssText = `
            flex: 1;
            overflow-y: auto;
            -webkit-overflow-scrolling: touch;
        `;
        
        // Poblar con todas las opciones inicialmente
        this.populateMobileResults(resultsList, originalOptions, selectElement);
        
        // Ensamblar modal
        modalContent.appendChild(header);
        modalContent.appendChild(searchContainer);
        modalContent.appendChild(resultsList);
        modal.appendChild(modalContent);
        
        // Eventos del modal
        this.setupMobileModalEvents(modal, selectElement, searchInput, resultsList);
        
        return modal;
    },

    populateMobileResults: function(container, options, selectElement) {
        container.innerHTML = '';
        
        if (options.length === 0) {
            const noResults = document.createElement('div');
            noResults.style.cssText = `
                padding: 2rem;
                text-align: center;
                color: #6b7280;
                font-style: italic;
            `;
            noResults.textContent = 'No hay opciones disponibles';
            container.appendChild(noResults);
            return;
        }
        
        options.forEach((option, index) => {
            const item = document.createElement('div');
            item.className = 'result-item';
            item.style.cssText = `
                padding: 1rem;
                border-bottom: 1px solid #f3f4f6;
                cursor: pointer;
                touch-action: manipulation;
                display: flex;
                align-items: center;
                justify-content: space-between;
                transition: background-color 0.2s;
                font-size: 1rem;
                line-height: 1.5;
            `;
            
            if (option.selected) {
                item.style.backgroundColor = '#eff6ff';
                item.style.color = '#1d4ed8';
                item.innerHTML = `
                    <span>${option.text}</span>
                    <span style="color: #3b82f6;">✓</span>
                `;
            } else {
                item.textContent = option.text;
            }
            
            // Eventos táctiles
            item.addEventListener('touchstart', () => {
                item.style.backgroundColor = '#f3f4f6';
            });
            
            item.addEventListener('touchend', (e) => {
                e.preventDefault();
                this.selectMobileOption(selectElement, option);
                this.closeMobileModal(selectElement);
            });
            
            // Fallback para click
            item.addEventListener('click', (e) => {
                e.preventDefault();
                this.selectMobileOption(selectElement, option);
                this.closeMobileModal(selectElement);
            });
            
            container.appendChild(item);
        });
    },

    setupMobileModalEvents: function(modal, selectElement, searchInput, resultsList) {
        const instance = this.instances.get(selectElement);
        
        // Cerrar modal
        const closeBtn = modal.querySelector('.close-modal-btn');
        closeBtn.addEventListener('click', (e) => {
            e.preventDefault();
            this.closeMobileModal(selectElement);
        });
        
        // Cerrar con tap fuera del contenido
        modal.addEventListener('click', (e) => {
            if (e.target === modal) {
                this.closeMobileModal(selectElement);
            }
        });
        
        // Búsqueda en tiempo real
        searchInput.addEventListener('input', (e) => {
            this.performMobileSearch(selectElement, e.target.value, resultsList);
        });
        
        // Manejar la clase visible
        setTimeout(() => {
            modal.classList.add('modal-visible');
        }, 10);
    },

    performMobileSearch: function(selectElement, query, resultsList) {
        const instance = this.instances.get(selectElement);
        if (!instance) return;
        
        const { originalOptions } = instance;
        const searchTerm = query.toLowerCase().trim();
        
        console.log(`🔍📱 Búsqueda móvil: "${searchTerm}"`);
        
        if (searchTerm === '') {
            this.populateMobileResults(resultsList, originalOptions, selectElement);
        } else {
            const filteredOptions = originalOptions.filter(option => 
                option.text.toLowerCase().includes(searchTerm)
            );
            
            console.log(`✅📱 ${filteredOptions.length} resultados encontrados`);
            
            if (filteredOptions.length === 0) {
                resultsList.innerHTML = `
                    <div style="padding: 2rem; text-align: center; color: #ef4444; font-style: italic;">
                        No se encontraron resultados para "${query}"
                    </div>
                `;
            } else {
                this.populateMobileResults(resultsList, filteredOptions, selectElement);
            }
        }
    },

    selectMobileOption: function(selectElement, option) {
        console.log(`✅📱 Seleccionando opción móvil: ${option.text}`);
        
        // Actualizar el select
        selectElement.value = option.value;
        
        // Disparar evento change
        const changeEvent = new Event('change', { bubbles: true });
        selectElement.dispatchEvent(changeEvent);
        
        // Feedback visual
        this.showMobileFeedback(`Seleccionado: ${option.text}`);
    },

    showMobileFeedback: function(message) {
        const toast = document.createElement('div');
        toast.style.cssText = `
            position: fixed;
            top: 20px;
            left: 50%;
            transform: translateX(-50%);
            background: #10b981;
            color: white;
            padding: 0.75rem 1.5rem;
            border-radius: 0.5rem;
            font-size: 0.875rem;
            z-index: 1001;
            opacity: 0;
            transition: opacity 0.3s ease;
        `;
        toast.textContent = message;
        
        document.body.appendChild(toast);
        
        requestAnimationFrame(() => {
            toast.style.opacity = '1';
        });
        
        setTimeout(() => {
            toast.style.opacity = '0';
            setTimeout(() => {
                if (toast.parentNode) {
                    toast.remove();
                }
            }, 300);
        }, 2000);
    },

    closeMobileModal: function(selectElement) {
        const instance = this.instances.get(selectElement);
        if (!instance || !instance.modal) return;
        
        console.log('📱 Cerrando modal de búsqueda móvil');
        
        const modal = instance.modal;
        
        // Animar salida
        modal.classList.remove('modal-visible');
        modal.style.opacity = '0';
        
        setTimeout(() => {
            if (modal.parentNode) {
                modal.remove();
            }
            // Restaurar scroll del body
            document.body.style.overflow = '';
            instance.modal = null;
        }, 300);
    },

    // Métodos para desktop (simplificados del código anterior)
    activateDesktopSearch: function(selectElement) {
        const instance = this.instances.get(selectElement);
        if (!instance || instance.isSearchActive) return;
        
        const { searchInput, wrapper } = instance;
        
        selectElement.style.display = 'none';
        searchInput.style.display = 'block';
        searchInput.focus();
        searchInput.value = '';
        
        instance.isSearchActive = true;
        wrapper.classList.add('search-active');
        
        // Manejar el overflow de la tabla contenedora
        this.handleTableOverflow(selectElement, true);
        
        this.createDesktopDropdown(selectElement);
    },

    deactivateDesktopSearch: function(selectElement) {
        const instance = this.instances.get(selectElement);
        if (!instance || !instance.isSearchActive) return;
        
        const { searchInput, wrapper } = instance;
        
        selectElement.style.display = 'block';
        searchInput.style.display = 'none';
        
        instance.isSearchActive = false;
        wrapper.classList.remove('search-active');
        
        // Restaurar el overflow de la tabla contenedora
        this.handleTableOverflow(selectElement, false);
        
        this.removeDesktopDropdown(wrapper);
    },

    handleTableOverflow: function(selectElement, activate) {
        // Encontrar la tabla contenedora
        const tableContainer = selectElement.closest('.table-responsive');
        if (tableContainer) {
            if (activate) {
                tableContainer.classList.add('search-active-container');
                tableContainer.style.overflow = 'visible';
            } else {
                tableContainer.classList.remove('search-active-container');
                // Restaurar el overflow después de un delay para permitir que el dropdown se cierre
                setTimeout(() => {
                    if (!tableContainer.querySelector('.search-active')) {
                        tableContainer.style.overflow = '';
                    }
                }, 200);
            }
        }
        
        // También manejar el accordion si está dentro de uno
        const accordionBody = selectElement.closest('.accordion-body');
        if (accordionBody) {
            if (activate) {
                accordionBody.style.overflow = 'visible';
                accordionBody.style.zIndex = '1000';
            } else {
                setTimeout(() => {
                    if (!accordionBody.querySelector('.search-active')) {
                        accordionBody.style.overflow = '';
                        accordionBody.style.zIndex = '';
                    }
                }, 200);
            }
        }
    },

    createDesktopDropdown: function(selectElement) {
        const instance = this.instances.get(selectElement);
        if (!instance) return;
        
        const { wrapper, originalOptions } = instance;
        
        const dropdown = document.createElement('div');
        dropdown.className = 'search-dropdown';
        dropdown.style.cssText = `
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            background: white;
            border: 1px solid #d1d5db;
            border-radius: 0.375rem;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
            max-height: 200px;
            overflow-y: auto;
            z-index: 50;
            margin-top: 0.25rem;
        `;
        
        this.populateDesktopDropdown(dropdown, originalOptions, selectElement);
        
        wrapper.appendChild(dropdown);
        instance.dropdown = dropdown;
    },

    populateDesktopDropdown: function(dropdown, options, selectElement) {
        dropdown.innerHTML = '';
        
        options.forEach(option => {
            const item = document.createElement('div');
            item.className = 'dropdown-item';
            item.textContent = option.text;
            item.style.cssText = `
                padding: 0.5rem 0.75rem;
                cursor: pointer;
                border-bottom: 1px solid #f3f4f6;
                transition: background-color 0.15s;
            `;
            
            if (option.selected) {
                item.style.backgroundColor = '#eff6ff';
                item.style.color = '#1d4ed8';
            }
            
            item.addEventListener('mouseenter', () => {
                item.style.backgroundColor = '#f3f4f6';
            });
            
            item.addEventListener('mouseleave', () => {
                if (!option.selected) {
                    item.style.backgroundColor = '';
                }
            });
            
            item.addEventListener('click', () => {
                this.selectDesktopOption(selectElement, option);
                this.deactivateDesktopSearch(selectElement);
            });
            
            dropdown.appendChild(item);
        });
    },

    performDesktopSearch: function(selectElement, query) {
        const instance = this.instances.get(selectElement);
        if (!instance) return;
        
        const { originalOptions, dropdown } = instance;
        const searchTerm = query.toLowerCase().trim();
        
        if (searchTerm === '') {
            this.populateDesktopDropdown(dropdown, originalOptions, selectElement);
        } else {
            const filteredOptions = originalOptions.filter(option => 
                option.text.toLowerCase().includes(searchTerm)
            );
            
            if (filteredOptions.length === 0) {
                dropdown.innerHTML = `
                    <div style="padding: 0.5rem 0.75rem; color: #ef4444; font-style: italic;">
                        No se encontraron resultados para "${query}"
                    </div>
                `;
            } else {
                this.populateDesktopDropdown(dropdown, filteredOptions, selectElement);
            }
        }
    },

    selectFirstDesktopMatch: function(selectElement) {
        const instance = this.instances.get(selectElement);
        if (!instance) return;
        
        const firstItem = instance.dropdown.querySelector('.dropdown-item:not(.no-results)');
        if (firstItem) {
            const optionText = firstItem.textContent;
            const option = instance.originalOptions.find(opt => opt.text === optionText);
            
            if (option) {
                this.selectDesktopOption(selectElement, option);
                this.deactivateDesktopSearch(selectElement);
            }
        }
    },

    selectDesktopOption: function(selectElement, option) {
        selectElement.value = option.value;
        const changeEvent = new Event('change', { bubbles: true });
        selectElement.dispatchEvent(changeEvent);
    },

    removeDesktopDropdown: function(wrapper) {
        const dropdown = wrapper.querySelector('.search-dropdown');
        if (dropdown) {
            dropdown.remove();
        }
    },

    // Métodos comunes
    captureOriginalOptions: function(selectElement) {
        const options = [];
        
        Array.from(selectElement.options).forEach(option => {
            if (option.value !== '' && option.textContent.trim() !== '') {
                options.push({
                    value: option.value,
                    text: option.textContent.trim(),
                    selected: option.selected,
                    disabled: option.disabled
                });
            }
        });
        
        return options;
    },

    addSearchIndicator: function(selectElement) {
        const indicator = document.createElement('span');
        indicator.innerHTML = '🔍';
        indicator.className = 'search-indicator';
        indicator.title = 'Doble click o click aquí para buscar'; // Tooltip nativo como respaldo
        indicator.style.cssText = `
            position: absolute;
            right: 2rem;
            top: 50%;
            transform: translateY(-50%);
            pointer-events: none;
            font-size: 0.875rem;
            color: #6b7280;
            z-index: 1;
            cursor: pointer;
        `;
        
        const wrapper = selectElement.parentNode;
        if (wrapper) {
            wrapper.style.position = 'relative';
            wrapper.appendChild(indicator);
        }
    },

    getPlaceholder: function(selectElement) {
        const name = selectElement.name || '';
        
        if (name.includes('relationship') || name.includes('parentesco')) {
            return 'Buscar parentesco...';
        }
        if (name.includes('brand')) {
            return 'Buscar marca...';
        }
        if (name.includes('color')) {
            return 'Buscar color...';
        }
        if (name.includes('breed')) {
            return 'Buscar raza...';
        }
        
        return 'Buscar...';
    },

    getModalTitle: function(selectElement) {
        const name = selectElement.name || '';
        
        if (name.includes('relationship') || name.includes('parentesco')) {
            return 'Buscar Parentesco';
        }
        if (name.includes('brand')) {
            return 'Buscar Marca';
        }
        if (name.includes('color')) {
            return 'Buscar Color';
        }
        if (name.includes('breed')) {
            return 'Buscar Raza';
        }
        
        return 'Buscar';
    },

    // CSS dinámico para móviles
    injectMobileCSS: function() {
        if (document.querySelector('#mobile-search-styles')) {
            return; // Ya existe
        }
        
        const style = document.createElement('style');
        style.id = 'mobile-search-styles';
        style.textContent = `
            .mobile-search-modal.modal-visible {
                opacity: 1;
            }
            
            .mobile-search-modal.modal-visible .modal-content {
                transform: translateY(0);
            }
            
            .search-trigger-btn:active {
                background: #2563eb !important;
            }
            
            .result-item:active {
                background-color: #e5e7eb !important;
            }
            
            @media (max-width: 768px) {
                .search-select-wrapper.mobile-search select {
                    padding-right: 4.5rem !important;
                }
            }
        `;
        
        document.head.appendChild(style);
    },

    // Inicialización con CSS
    initWithCSS: function() {
        if (this.isMobile) {
            this.injectMobileCSS();
        }
        this.init();
    }
};

// CSS adicional para el estilo modal visible
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', () => {
        window.LightweightSearchSelect.injectMobileCSS();
    });
} else {
    window.LightweightSearchSelect.injectMobileCSS();
}