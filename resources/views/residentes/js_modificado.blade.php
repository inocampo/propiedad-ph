// Cargar residentes existentes
@foreach($apartamento->residents as $index => $resident)
    // Crear un nuevo residente
    addResident();
    
    // Obtener el último residente añadido
    const residentRows = document.querySelectorAll('#residents-container .resident-item');
    const lastResident = residentRows[residentRows.length - 1];
    
    // Llenar los campos
    lastResident.querySelector('input[name^="residents["][name$="[name]"]').value = "{{ $resident->name }}";
    lastResident.querySelector('input[name^="residents["][name$="[document]"]').value = "{{ $resident->document_number }}";
    lastResident.querySelector('input[name^="residents["][name$="[phone]"]').value = "{{ $resident->phone_number ?? '' }}";
    lastResident.querySelector('input[name^="residents["][name$="[email]"]').value = "{{ $resident->email ?? '' }}";
    
    // Seleccionar el parentesco correcto si existe
    const parentescoSelect = lastResident.querySelector('select[name^="residents["][name$="[parentesco]"]');
    if (parentescoSelect) {
        parentescoSelect.value = "{{ $resident->relationship_id ?? '' }}";
    }
@endforeach
