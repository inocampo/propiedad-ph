// Variables globales para contadores
let ownerCount = 0;
let residentCount = 0;
let minorCount = 0;
let vehicleCount = 0;
let petCount = 0;

// Función para manejar la visibilidad del campo de bicicletas
function toggleBicyclesCount() {
    const hasBicycles = document.getElementById('has_bicycles').checked;
    const bicyclesCountContainer = document.getElementById('bicycles_count_container');
    
    if (hasBicycles) {
        bicyclesCountContainer.classList.remove('hidden');
    } else {
        bicyclesCountContainer.classList.add('hidden');
    }
}

// Función para contar elementos reales en el DOM
function recountElements() {
    const ownerItems = document.querySelectorAll('#owners-container .owner-item');
    ownerCount = ownerItems.length;
    
    const residentItems = document.querySelectorAll('#residents-container .resident-item');
    residentCount = residentItems.length;
    
    const minorItems = document.querySelectorAll('#minors-container .minor-item');
    minorCount = minorItems.length;
    
    const vehicleItems = document.querySelectorAll('#vehicles-container .vehicle-item');
    vehicleCount = vehicleItems.length;
    
    const petItems = document.querySelectorAll('#pets-container .pet-item');
    petCount = petItems.length;
    
    updateCounters();
}

// Función para actualizar contadores
function updateCounters() {
    document.getElementById('owners-counter').textContent = ownerCount;
    document.getElementById('residents-counter').textContent = residentCount;
    document.getElementById('minors-counter').textContent = minorCount;
    document.getElementById('vehicles-counter').textContent = vehicleCount;
    document.getElementById('pets-counter').textContent = petCount;
}

// Función para agregar un nuevo propietario
function addOwner(index = null) {
    const newIndex = index !== null ? index : ownerCount;
    console.log(`Agregando propietario con índice ${newIndex}. Contador actual: ${ownerCount}`);

    const template = document.getElementById('owner-template');
    const clone = document.importNode(template.content, true);

    const inputs = clone.querySelectorAll('input, select');
    inputs.forEach(input => {
        const originalName = input.name;
        input.name = input.name.replace('INDEX', newIndex);
        console.log(`Campo renombrado de ${originalName} a ${input.name}`);
        input.setAttribute('data-index', newIndex);
    });

    const removeBtn = clone.querySelector('.remove-owner-btn');
    removeBtn.addEventListener('click', function() {
        const ownerRow = this.closest('.owner-item');
        ownerRow.remove();
        recountElements();
        renumberOwners();
    });

    const container = document.getElementById('owners-container');
    container.appendChild(clone);
    console.log(`Propietario ${newIndex} agregado al DOM. Contenedor tiene ${container.children.length} hijos`);

    recountElements();
    console.log(`Propietario agregado. Nuevo contador: ${ownerCount}`);
    
    if (index === null) {
        const propietariosBody = document.getElementById('propietarios-body');
        if (propietariosBody.classList.contains('hidden')) {
            toggleAccordion('propietarios-header');
        }
        
        const ownerRows = document.querySelectorAll('#owners-container .owner-item');
        const lastOwnerRow = ownerRows[ownerRows.length - 1];
        const lastOwnerNameInput = lastOwnerRow.querySelector('.owner-input-name');
        lastOwnerNameInput.focus();
    }
    
    return newIndex;
}

// Función para renumerar propietarios
function renumberOwners() {
    const ownerRows = document.querySelectorAll('#owners-container .owner-item');
    ownerRows.forEach((row, index) => {
        const inputs = row.querySelectorAll('input');
        inputs.forEach(input => {
            const nameParts = input.name.split('[');
            if (nameParts.length > 1) {
                input.name = `${nameParts[0]}[${index}]${input.name.substring(input.name.indexOf(']') + 1)}`;
            }
        });
    });
}

// Función para agregar un residente
function addResident(index = null) {
    const newIndex = index !== null ? index : residentCount;
    console.log(`Agregando residente con índice ${newIndex}. Contador actual: ${residentCount}`);
    
    const template = document.getElementById('resident-template');
    const clone = document.importNode(template.content, true);
    
    const inputs = clone.querySelectorAll('input, select');
    inputs.forEach(input => {
        const originalName = input.name;
        input.name = input.name.replace('INDEX', newIndex);
        console.log(`Campo renombrado de ${originalName} a ${input.name}`);
        input.setAttribute('data-index', newIndex);
    });
    
    const removeBtn = clone.querySelector('.remove-resident-btn');
    removeBtn.addEventListener('click', function() {
        const residentRow = this.closest('.resident-item');
        residentRow.remove();
        recountElements();
        renumberResidents();
    });
    
    const container = document.getElementById('residents-container');
    container.appendChild(clone);
    console.log(`Residente ${newIndex} agregado al DOM. Contenedor tiene ${container.children.length} hijos`);
    
    recountElements();
    console.log(`Residente agregado. Nuevo contador: ${residentCount}`);
    
    if (index === null) {
        const residentesBody = document.getElementById('residentes-body');
        if (residentesBody.classList.contains('hidden')) {
            toggleAccordion('residentes-header');
        }
        
        const residentRows = document.querySelectorAll('#residents-container .resident-item');
        const lastResidentRow = residentRows[residentRows.length - 1];
        const lastResidentNameInput = lastResidentRow.querySelector('.resident-input-name');
        lastResidentNameInput.focus();
    }
    
    return newIndex;
}

// Función para renumerar residentes
function renumberResidents() {
    const residentRows = document.querySelectorAll('#residents-container .resident-item');
    residentRows.forEach((row, index) => {
        const inputs = row.querySelectorAll('input, select');
        inputs.forEach(input => {
            const nameParts = input.name.split('[');
            if (nameParts.length > 1) {
                input.name = `${nameParts[0]}[${index}]${input.name.substring(input.name.indexOf(']') + 1)}`;
            }
        });
    });
}

// Función para agregar un menor de edad
function addMinor(index = null) {
    const newIndex = index !== null ? index : minorCount;
    console.log(`Agregando menor con índice ${newIndex}. Contador actual: ${minorCount}`);
    
    const template = document.getElementById('minor-template');
    const clone = document.importNode(template.content, true);
    
    const inputs = clone.querySelectorAll('input, select');
    inputs.forEach(input => {
        const originalName = input.name;
        input.name = input.name.replace('INDEX', newIndex);
        console.log(`Campo renombrado de ${originalName} a ${input.name}`);
        input.setAttribute('data-index', newIndex);
    });
    
    const removeBtn = clone.querySelector('.remove-minor-btn');
    removeBtn.addEventListener('click', function() {
        const minorRow = this.closest('.minor-item');
        minorRow.remove();
        recountElements();
        renumberMinors();
    });
    
    const container = document.getElementById('minors-container');
    container.appendChild(clone);
    console.log(`Menor ${newIndex} agregado al DOM. Contenedor tiene ${container.children.length} hijos`);
    
    recountElements();
    console.log(`Menor agregado. Nuevo contador: ${minorCount}`);
    
    if (index === null) {
        const menoresBody = document.getElementById('menores-body');
        if (menoresBody.classList.contains('hidden')) {
            toggleAccordion('menores-header');
        }
        
        const minorRows = document.querySelectorAll('#minors-container .minor-item');
        const lastMinorRow = minorRows[minorRows.length - 1];
        const lastMinorNameInput = lastMinorRow.querySelector('.minor-input-name');
        lastMinorNameInput.focus();
    }
    
    return newIndex;
}

// Función para renumerar menores
function renumberMinors() {
    const minorRows = document.querySelectorAll('#minors-container .minor-item');
    minorRows.forEach((row, index) => {
        const inputs = row.querySelectorAll('input, select');
        inputs.forEach(input => {
            const nameParts = input.name.split('[');
            if (nameParts.length > 1) {
                input.name = `${nameParts[0]}[${index}]${input.name.substring(input.name.indexOf(']') + 1)}`;
            }
        });
    });
}

// Función para agregar un vehículo
function addVehicle(index = null) {
    const newIndex = index !== null ? index : vehicleCount;
    console.log(`Agregando vehículo con índice ${newIndex}. Contador actual: ${vehicleCount}`);
    
    const template = document.getElementById('vehicle-template');
    const clone = document.importNode(template.content, true);
    
    const inputs = clone.querySelectorAll('input, select');
    inputs.forEach(input => {
        const originalName = input.name;
        input.name = input.name.replace('INDEX', newIndex);
        console.log(`Campo renombrado de ${originalName} a ${input.name}`);
        input.setAttribute('data-index', newIndex);
    });
    
    const removeBtn = clone.querySelector('.remove-vehicle-btn');
    if (removeBtn) {
        removeBtn.addEventListener('click', function() {
            const row = this.closest('.vehicle-item');
            if (row) {
                row.remove();
                recountElements();
                reindexVehicles();
            }
        });
    }
    
    const container = document.getElementById('vehicles-container');
    container.appendChild(clone);
    
    recountElements();
    console.log(`Vehículo agregado. Nuevo contador: ${vehicleCount}`);
    
    if (index === null) {
        const vehiculosBody = document.getElementById('vehiculos-body');
        if (vehiculosBody.classList.contains('hidden')) {
            toggleAccordion('vehiculos-header');
        }
        
        const vehicleRows = document.querySelectorAll('#vehicles-container .vehicle-item');
        const lastVehicleRow = vehicleRows[vehicleRows.length - 1];
        const lastVehicleTypeSelect = lastVehicleRow.querySelector('.vehicle-input-type');
        lastVehicleTypeSelect.focus();
    }
    
    return newIndex;
}

// Función para reindexar vehículos
function reindexVehicles() {
    const vehicleRows = document.querySelectorAll('#vehicles-container .vehicle-item');
    vehicleRows.forEach((row, index) => {
        const inputs = row.querySelectorAll('input, select');
        inputs.forEach(input => {
            const nameParts = input.name.split('[');
            if (nameParts.length > 1) {
                input.name = `${nameParts[0]}[${index}]${input.name.substring(input.name.indexOf(']') + 1)}`;
            }
        });
    });
}

// Función para agregar una mascota
function addPet(index = null) {
    const newIndex = index !== null ? index : petCount;
    console.log(`Agregando mascota con índice ${newIndex}. Contador actual: ${petCount}`);
    
    const template = document.getElementById('pet-template');
    const clone = document.importNode(template.content, true);
    
    const inputs = clone.querySelectorAll('input, select');
    inputs.forEach(input => {
        const originalName = input.name;
        input.name = input.name.replace('INDEX', newIndex);
        console.log(`Campo renombrado de ${originalName} a ${input.name}`);
        input.setAttribute('data-index', newIndex);
    });
    
    const removeBtn = clone.querySelector('.remove-pet-btn');
    if (removeBtn) {
        removeBtn.addEventListener('click', function() {
            const row = this.closest('.pet-item');
            if (row) {
                row.remove();
                recountElements();
                reindexPets();
            }
        });
    }
    
    const container = document.getElementById('pets-container');
    container.appendChild(clone);
    
    recountElements();
    console.log(`Mascota agregada. Nuevo contador: ${petCount}`);
    
    if (index === null) {
        const mascotasBody = document.getElementById('mascotas-body');
        if (mascotasBody.classList.contains('hidden')) {
            toggleAccordion('mascotas-header');
        }
        
        const petRows = document.querySelectorAll('#pets-container .pet-item');
        const lastPetRow = petRows[petRows.length - 1];
        const lastPetNameInput = lastPetRow.querySelector('.pet-input-name');
        lastPetNameInput.focus();
    }
    
    return newIndex;
}

// Función para reindexar mascotas
function reindexPets() {
    const petRows = document.querySelectorAll('#pets-container .pet-item');
    petRows.forEach((row, index) => {
        const inputs = row.querySelectorAll('input, select');
        inputs.forEach(input => {
            const nameParts = input.name.split('[');
            if (nameParts.length > 1) {
                input.name = `${nameParts[0]}[${index}]${input.name.substring(input.name.indexOf(']') + 1)}`;
            }
        });
    });
}

// Función para alternar acordeones
function toggleAccordion(headerId) {
    const header = document.getElementById(headerId);
    const body = header.nextElementSibling;
    const icon = header.querySelector('.accordion-toggle svg');
    const isOpening = body.classList.contains('hidden');
    
    if (isOpening) {
        const allAccordionBodies = document.querySelectorAll('.accordion-body');
        const allAccordionIcons = document.querySelectorAll('.accordion-toggle svg');
        
        allAccordionBodies.forEach(accordionBody => {
            accordionBody.classList.add('hidden');
        });
        
        allAccordionIcons.forEach(accordionIcon => {
            accordionIcon.classList.remove('rotate-180');
        });
    }
    
    body.classList.toggle('hidden');
    icon.classList.toggle('rotate-180');
}

// Función principal de inicialización
function initializeFormulario() {
    console.log('DOM cargado, inicializando eventos...');
    
    // Inicializar botones
    document.getElementById('add-owner-btn').addEventListener('click', function() {
        addOwner();
        const ownerRows = document.querySelectorAll('#owners-container .owner-item');
        const lastOwnerRow = ownerRows[ownerRows.length - 1];
        const firstInput = lastOwnerRow.querySelector('input');
        if (firstInput) firstInput.focus();
    });
    
    document.getElementById('add-resident-btn').addEventListener('click', function() {
        addResident();
        const residentRows = document.querySelectorAll('#residents-container .resident-item');
        const lastResidentRow = residentRows[residentRows.length - 1];
        const firstInput = lastResidentRow.querySelector('input');
        if (firstInput) firstInput.focus();
    });
    
    document.getElementById('add-minor-btn').addEventListener('click', function() {
        addMinor();
        const minorRows = document.querySelectorAll('#minors-container .minor-item');
        const lastMinorRow = minorRows[minorRows.length - 1];
        const firstInput = lastMinorRow.querySelector('input');
        if (firstInput) firstInput.focus();
    });
    
    document.getElementById('add-vehicle-btn').addEventListener('click', function() {
        addVehicle();
        const vehicleRows = document.querySelectorAll('#vehicles-container .vehicle-item');
        const lastVehicleRow = vehicleRows[vehicleRows.length - 1];
        const firstSelect = lastVehicleRow.querySelector('select');
        if (firstSelect) firstSelect.focus();
    });
    
    document.getElementById('add-pet-btn').addEventListener('click', function() {
        addPet();
        const petRows = document.querySelectorAll('#pets-container .pet-item');
        const lastPetRow = petRows[petRows.length - 1];
        const firstInput = lastPetRow.querySelector('input');
        if (firstInput) firstInput.focus();
    });
    
    // Agregar eventos a los encabezados de acordeones
    const accordionHeaders = document.querySelectorAll('.accordion-header');
    accordionHeaders.forEach(header => {
        header.addEventListener('click', function() {
            toggleAccordion(this.id);
        });
    });
    
    // Agregar evento para mostrar/ocultar el campo de bicicletas
    document.getElementById('has_bicycles').addEventListener('change', toggleBicyclesCount);
    
    // Inicializar estado del campo de bicicletas
    toggleBicyclesCount();
}

// Función para cargar datos del servidor (será llamada desde el Blade)
function loadDataFromServer(serverData) {
    console.log('Cargando datos del servidor:', serverData);
    
    try {
        // Cargar propietarios
        if (serverData.owners && serverData.owners.length > 0) {
            document.getElementById('owners-container').innerHTML = '';
            serverData.owners.forEach((owner, index) => {
                const addedIndex = addOwner(index);
                const ownerRows = document.querySelectorAll('#owners-container .owner-item');
                const currentOwnerRow = ownerRows[ownerRows.length - 1];
                
                if (currentOwnerRow) {
                    const nameInput = currentOwnerRow.querySelector('input[name^="owners["][name$="[name]"]');
                    const documentInput = currentOwnerRow.querySelector('input[name^="owners["][name$="[document]"]');
                    const phoneInput = currentOwnerRow.querySelector('input[name^="owners["][name$="[phone]"]');
                    const emailInput = currentOwnerRow.querySelector('input[name^="owners["][name$="[email]"]');
                    
                    if (nameInput) nameInput.value = owner.name || '';
                    if (documentInput) documentInput.value = owner.document_number || '';
                    if (phoneInput) phoneInput.value = owner.phone_number || '';
                    if (emailInput) emailInput.value = owner.email || '';
                }
            });
        }
        
        // Cargar residentes
        if (serverData.residents && serverData.residents.length > 0) {
            document.getElementById('residents-container').innerHTML = '';
            serverData.residents.forEach((resident, index) => {
                const addedIndex = addResident(index);
                const residentRows = document.querySelectorAll('#residents-container .resident-item');
                const currentResidentRow = residentRows[residentRows.length - 1];
                
                if (currentResidentRow) {
                    const nameInput = currentResidentRow.querySelector('input[name^="residents["][name$="[name]"]');
                    const documentInput = currentResidentRow.querySelector('input[name^="residents["][name$="[document]"]');
                    const phoneInput = currentResidentRow.querySelector('input[name^="residents["][name$="[phone]"]');
                    const relationshipSelect = currentResidentRow.querySelector('select[name^="residents["][name$="[relationship_id]"]');
                    
                    if (nameInput) nameInput.value = resident.name || '';
                    if (documentInput) documentInput.value = resident.document_number || '';
                    if (phoneInput) phoneInput.value = resident.phone_number || '';
                    if (relationshipSelect && resident.relationship_id) relationshipSelect.value = resident.relationship_id;
                }
            });
        }
        
        // Cargar menores
        if (serverData.minors && serverData.minors.length > 0) {
            document.getElementById('minors-container').innerHTML = '';
            serverData.minors.forEach((minor, index) => {
                const addedIndex = addMinor(index);
                const minorRows = document.querySelectorAll('#minors-container .minor-item');
                const currentMinorRow = minorRows[minorRows.length - 1];
                
                if (currentMinorRow) {
                    const nameInput = currentMinorRow.querySelector('input[name^="minors["][name$="[name]"]');
                    const ageInput = currentMinorRow.querySelector('input[name^="minors["][name$="[age]"]');
                    const genderSelect = currentMinorRow.querySelector('select[name^="minors["][name$="[gender]"]');
                    
                    if (nameInput) nameInput.value = minor.name || '';
                    if (ageInput) ageInput.value = minor.age || '';
                    if (genderSelect && minor.gender) genderSelect.value = minor.gender;
                }
            });
        }
        
        // Cargar vehículos
        if (serverData.vehicles && serverData.vehicles.length > 0) {
            document.getElementById('vehicles-container').innerHTML = '';
            serverData.vehicles.forEach((vehicle, index) => {
                const addedIndex = addVehicle(index);
                const vehicleRows = document.querySelectorAll('#vehicles-container .vehicle-item');
                const currentVehicleRow = vehicleRows[vehicleRows.length - 1];
                
                if (currentVehicleRow) {
                    const typeSelect = currentVehicleRow.querySelector('select[name^="vehicles["][name$="[type]"]');
                    const plateInput = currentVehicleRow.querySelector('input[name^="vehicles["][name$="[license_plate]"]');
                    const brandSelect = currentVehicleRow.querySelector('select[name^="vehicles["][name$="[brand]"]');
                    const colorSelect = currentVehicleRow.querySelector('select[name^="vehicles["][name$="[color]"]');
                    
                    if (typeSelect && vehicle.type) typeSelect.value = vehicle.type;
                    if (plateInput) plateInput.value = vehicle.license_plate || '';
                    if (brandSelect && vehicle.brand_id) brandSelect.value = vehicle.brand_id;
                    if (colorSelect && vehicle.color_id) colorSelect.value = vehicle.color_id;
                }
            });
        }
        
        // Cargar mascotas
        if (serverData.pets && serverData.pets.length > 0) {
            document.getElementById('pets-container').innerHTML = '';
            serverData.pets.forEach((pet, index) => {
                const addedIndex = addPet(index);
                const petRows = document.querySelectorAll('#pets-container .pet-item');
                const currentPetRow = petRows[petRows.length - 1];
                
                if (currentPetRow) {
                    const nameInput = currentPetRow.querySelector('input[name^="pets["][name$="[name]"]');
                    const typeSelect = currentPetRow.querySelector('select[name^="pets["][name$="[type]"]');
                    const breedSelect = currentPetRow.querySelector('select[name^="pets["][name$="[breed]"]');
                    
                    if (nameInput) nameInput.value = pet.name || '';
                    if (typeSelect && pet.type) typeSelect.value = pet.type;
                    if (breedSelect && pet.breed_id) breedSelect.value = pet.breed_id;
                }
            });
        }
        
        updateCounters();
        console.log('Datos del servidor cargados correctamente');
        
    } catch (error) {
        console.error('Error al cargar datos del servidor:', error);
    }
}