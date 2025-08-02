<?php

namespace App\Http\Controllers;

use App\Models\Apartment;
use App\Models\Owner;
use App\Models\Resident;
use App\Models\Relationship;
use App\Models\Minor;
use App\Models\Vehicle;
use App\Models\Pet;
use App\Models\Brand;
use App\Models\Color;
use App\Models\Breed;
use App\Mail\VerificationCode;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class ResidentFormController extends Controller
{
    /**
     * Método privado para obtener todas las variables necesarias para el formulario
     */
    private function getFormData($apartamento = null)
    {
        // Cargar relaciones si el apartamento existe
        if ($apartamento) {
            $apartamento->load(['owners', 'residents.relationship', 'minors', 'vehicles', 'pets.breed']);
        }
        
        return [
            'apartamento' => $apartamento,
            'relationships' => Relationship::orderBy('name')->get(),
            'brands' => Brand::orderBy('name')->get(),
            'colors' => Color::orderBy('name')->get(),
            'breeds' => Breed::orderBy('name')->get(),
        ];
    }

    /**
     * Muestra la página inicial para ingresar el número de apartamento
     */
    public function index()
    {
        return view('residentes.index');
    }

    /**
     * Verifica si el apartamento existe en la base de datos
     */
    public function verificarApartamento(Request $request)
    {
        $request->validate([
            'number' => 'required|string|max:5',
        ]);

        $apartamento = Apartment::where('number', $request->number)->first();

        if ($apartamento) {
            // El apartamento existe, redirigir a la página para enviar código
            return redirect()->route('residentes.enviar-codigo', ['number' => $request->number]);
        } else {
            // El apartamento no existe, redirigir al formulario para crear nuevo
            return redirect()->route('residentes.formulario', ['number' => $request->number]);
        }
    }

    /**
     * Envía un código de verificación al email del residente principal
     */
    public function enviarCodigo($number)
    {
        $apartamento = Apartment::where('number', $number)->firstOrFail();
        
        // Generar código de verificación
        $codigo = Str::random(6);
        
        // Guardar el código en la sesión
        Session::put('verification_code', $codigo);
        Session::put('apartment_number', $apartamento->number);
        
        try {
            // Enviar el código por email
            Mail::to($apartamento->resident_email)->send(new \App\Mail\VerificationCode($codigo));
            $emailEnviado = true;
        } catch (\Exception $e) {
            // Capturar cualquier error en el envío de email
            $emailEnviado = false;
            // Registrar el error para depuración
            Log::error('Error al enviar email: ' . $e->getMessage());
        }
        
        return view('residentes.verificar-codigo', [
            'apartamento' => $apartamento,
            'codigo' => $codigo, // En producción, considerar eliminar esta línea
            'emailEnviado' => $emailEnviado
        ]);
    }

    /**
     * Verifica el código ingresado por el usuario
     */
    public function verificarCodigo(Request $request)
    {
        $request->validate([
            'codigo' => 'required|string',
        ]);

        $codigoGuardado = Session::get('verification_code');
        $apartmentNumber = Session::get('apartment_number');

        if ($request->codigo === $codigoGuardado) {
            $apartamento = Apartment::where('number', $apartmentNumber)->firstOrFail();
            
            $data = $this->getFormData($apartamento);
            
            return view('residentes.formulario', $data);
        } else {
            return back()->withErrors(['codigo' => 'El código ingresado no es válido.']);
        }
    }

    /**
     * Muestra el formulario para ingresar o actualizar datos
     */
    public function mostrarFormulario($number)
    {
        $apartamento = Apartment::where('number', $number)->first();
        
        $data = $this->getFormData($apartamento);
        
        return view('residentes.formulario', $data);
    }

    /**
     * Guarda los datos del formulario - CON LOGS DE DEPURACIÓN
     */
    public function guardarDatos(Request $request)
    {
        // LOGS DE DEPURACIÓN
        Log::info('=== INICIO GUARDADO DE DATOS ===');
        Log::info('Action received: ' . $request->input('action'));
        Log::info('Apartment number: ' . $request->input('number'));
        Log::info('Request method: ' . $request->method());
        Log::info('All request data: ', $request->all());

        try {
            $validatedData = $request->validate([
                'number' => 'required|string|max:5',
                'resident_name' => 'required|string|max:255',
                'resident_document' => 'required|string|max:20',
                'resident_phone' => 'required|string|max:20',
                'resident_email' => 'required|email|max:255',
                'has_bicycles' => 'nullable|boolean',
                'bicycles_count' => 'nullable|integer|min:1|required_if:has_bicycles,1',
                'received_manual' => 'nullable|boolean',
                'observations' => 'nullable|string',
                'action' => 'required|in:save_continue,save_exit',
                
                // Propietarios
                'owners' => 'nullable|array',
                'owners.*.name' => 'required|string|max:255',
                'owners.*.document' => 'required|string|max:20',
                'owners.*.phone' => 'required|string|max:20',
                'owners.*.email' => 'required|email|max:255',
                
                // Residentes
                'residents' => 'nullable|array',
                'residents.*.name' => 'required|string|max:255',
                'residents.*.document' => 'required|string|max:20',
                'residents.*.phone' => 'required|string|max:20',
                'residents.*.relationship_id' => 'required|exists:relationships,id',
                'residents.*.phone_for_intercom' => 'nullable|boolean',
                
                // Menores de edad
                'minors' => 'nullable|array',
                'minors.*.name' => 'required|string|max:255',
                'minors.*.age' => 'required|integer|min:0|max:17',
                'minors.*.gender' => 'required|string|in:niño,niña',
                
                // Vehículos
                'vehicles' => 'nullable|array',
                'vehicles.*.type' => 'required|string|in:carro,moto',
                'vehicles.*.license_plate' => 'required|string|max:10',
                'vehicles.*.brand_id' => 'required|exists:brands,id',
                'vehicles.*.color_id' => 'required|exists:colors,id',
                
                // Mascotas
                'pets' => 'nullable|array',
                'pets.*.name' => 'required|string|max:255',
                'pets.*.type' => 'required|string|in:perro,gato',
                'pets.*.breed_id' => 'required|exists:breeds,id',
            ], [
                'required' => 'El campo :attribute es obligatorio.',
                'email' => 'El campo :attribute debe ser un correo válido.',
                'string' => 'El campo :attribute debe ser texto.',
                'integer' => 'El campo :attribute debe ser un número.',
                'min' => 'El campo :attribute debe tener al menos :min.',
                'in' => 'El valor seleccionado para :attribute no es válido.',
                'exists' => 'El valor seleccionado para :attribute no es válido.',
                'required_if' => 'El campo :attribute es obligatorio.',
            ], [
                'resident_name' => 'nombre del residente principal',
                'resident_document' => 'cédula del residente principal',
                'resident_phone' => 'celular del residente principal',
                'resident_email' => 'email del residente principal',
                'bicycles_count' => 'cantidad de bicicletas',
                'owners.*.name' => 'nombre del propietario',
                'owners.*.document' => 'cédula del propietario',
                'owners.*.phone' => 'celular del propietario',
                'owners.*.email' => 'email del propietario',
                'residents.*.name' => 'nombre del residente',
                'residents.*.document' => 'cédula del residente',
                'residents.*.phone' => 'celular del residente',
                'residents.*.relationship_id' => 'parentesco del residente',
                'minors.*.name' => 'nombre del menor',
                'minors.*.age' => 'edad del menor',
                'minors.*.gender' => 'género del menor',
                'vehicles.*.type' => 'tipo de vehículo',
                'vehicles.*.license_plate' => 'placa del vehículo',
                'vehicles.*.brand_id' => 'marca del vehículo',
                'vehicles.*.color_id' => 'color del vehículo',
                'pets.*.name' => 'nombre de la mascota',
                'pets.*.type' => 'tipo de mascota',
                'pets.*.breed_id' => 'raza de la mascota',
            ]);

            Log::info('Validation successful');
            Log::info('Validated action: ' . $validatedData['action']);

            DB::beginTransaction();
            Log::info('Database transaction started');

            // Buscar o crear el apartamento
            $apartamento = Apartment::firstOrNew(['number' => $validatedData['number']]);
            Log::info('Apartment found/created for: ' . $validatedData['number']);
            
            // Actualizar datos del apartamento
            $apartamento->resident_name = strtoupper($validatedData['resident_name']);
            $apartamento->resident_document = $validatedData['resident_document'];
            $apartamento->resident_phone = $validatedData['resident_phone'];
            $apartamento->resident_email = strtolower($validatedData['resident_email']);
            $apartamento->has_bicycles = $request->boolean('has_bicycles');
            $apartamento->bicycles_count = $request->boolean('has_bicycles') ? $validatedData['bicycles_count'] : null;
            $apartamento->received_manual = $request->boolean('received_manual');
            $apartamento->observations = $validatedData['observations'];
            
            $apartamento->save();
            Log::info('Apartment saved successfully with ID: ' . $apartamento->id);
            
            // Procesar todas las relaciones
            Log::info('Processing owners...');
            $this->processPropietarios($apartamento, $validatedData['owners'] ?? []);
            
            Log::info('Processing residents...');
            $this->processResidentes($apartamento, $validatedData['residents'] ?? []);
            
            Log::info('Processing minors...');
            $this->processMenores($apartamento, $validatedData['minors'] ?? []);
            
            Log::info('Processing vehicles...');
            $this->processVehiculos($apartamento, $validatedData['vehicles'] ?? []);
            
            Log::info('Processing pets...');
            $this->processMascotas($apartamento, $validatedData['pets'] ?? []);

            DB::commit();
            Log::info('Database transaction committed successfully');

            // LÓGICA DUAL DE GUARDADO
            $action = $validatedData['action'];
            Log::info('Processing action: ' . $action);
            
            if ($action === 'save_continue') {
                Log::info('Redirecting to continue editing');
                // Guardar y continuar editando
                return redirect()
                    ->route('residentes.formulario', ['number' => $apartamento->number])
                    ->with('success', '¡Información guardada exitosamente! Puedes continuar editando.')
                    ->with('show_success_toast', true);
            } else {
                Log::info('Redirecting to confirmation page');
                // Guardar y salir (comportamiento original)
                return redirect()
                    ->route('residentes.confirmacion')
                    ->with('apartamento_number', $apartamento->number)
                    ->with('success', 'Información guardada y actualizada exitosamente.');
            }

        } catch (ValidationException $e) {
            DB::rollBack();
            Log::error('Validation error: ', $e->errors());
            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput()
                ->with('error', 'Por favor corrige los errores en el formulario.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('General error in guardarDatos: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            
            return redirect()->back()
                ->withInput()
                ->with('error', 'Ocurrió un error al guardar la información. Por favor intenta nuevamente.');
        }
    }

    /**
     * Método auxiliar para procesar propietarios
     */
    private function processPropietarios($apartamento, $ownersData)
    {
        Log::info('Processing ' . count($ownersData) . ' owners');
        $apartamento->owners()->delete();
        
        foreach ($ownersData as $index => $ownerData) {
            if (!empty($ownerData['name']) && !empty($ownerData['document'])) {
                $owner = $apartamento->owners()->create([
                    'name' => strtoupper($ownerData['name']),
                    'document_number' => $ownerData['document'],
                    'phone_number' => $ownerData['phone'],
                    'email' => strtolower($ownerData['email']),
                ]);
                Log::info('Owner created with ID: ' . $owner->id);
            }
        }
    }

    /**
     * Método auxiliar para procesar residentes
     */
    private function processResidentes($apartamento, $residentsData)
    {
        Log::info('Processing ' . count($residentsData) . ' residents');
        $apartamento->residents()->delete();
        
        foreach ($residentsData as $index => $residentData) {
            if (!empty($residentData['name']) && !empty($residentData['document'])) {
                $resident = $apartamento->residents()->create([
                    'name' => strtoupper($residentData['name']),
                    'document_number' => $residentData['document'],
                    'phone_number' => $residentData['phone'] ?? null,
                    'relationship_id' => $residentData['relationship_id'] ?? null,
                    'phone_for_intercom' => $residentData['phone_for_intercom'] ?? false,
                ]);
                Log::info('Resident created with ID: ' . $resident->id);
            }
        }
    }

    /**
     * Método auxiliar para procesar menores
     */
    private function processMenores($apartamento, $minorsData)
    {
        Log::info('Processing ' . count($minorsData) . ' minors');
        $apartamento->minors()->delete();
        
        foreach ($minorsData as $index => $minorData) {
            if (!empty($minorData['name'])) {
                $minor = $apartamento->minors()->create([
                    'name' => strtoupper($minorData['name']),
                    'age' => $minorData['age'] ?? null,
                    'gender' => $minorData['gender'] ?? null,
                ]);
                Log::info('Minor created with ID: ' . $minor->id);
            }
        }
    }

    /**
     * Método auxiliar para procesar vehículos
     */
    private function processVehiculos($apartamento, $vehiclesData)
    {
        Log::info('Processing ' . count($vehiclesData) . ' vehicles');
        $apartamento->vehicles()->delete();
        
        foreach ($vehiclesData as $index => $vehicleData) {
            if (!empty($vehicleData['type']) && !empty($vehicleData['license_plate'])) {
                $vehicle = $apartamento->vehicles()->create([
                    'type' => $vehicleData['type'],
                    'license_plate' => strtoupper($vehicleData['license_plate']),
                    'brand_id' => $vehicleData['brand_id'] ?? null,
                    'color_id' => $vehicleData['color_id'] ?? null,
                ]);
                Log::info('Vehicle created with ID: ' . $vehicle->id);
            }
        }
    }

    /**
     * Método auxiliar para procesar mascotas
     */
    private function processMascotas($apartamento, $petsData)
    {
        Log::info('Processing ' . count($petsData) . ' pets');
        $apartamento->pets()->delete();
        
        foreach ($petsData as $index => $petData) {
            if (!empty($petData['name']) && !empty($petData['type'])) {
                $pet = $apartamento->pets()->create([
                    'name' => strtoupper($petData['name']),
                    'type' => $petData['type'],
                    'breed_id' => $petData['breed_id'] ?? null,
                ]);
                Log::info('Pet created with ID: ' . $pet->id);
            }
        }
    }

    /**
     * Muestra la página de confirmación
     */
    public function confirmacion()
    {
        // Verificar que la sesión tenga los datos necesarios
        if (!session('success') && !session('apartamento_number')) {
            return redirect()->route('residentes.index')
                ->with('error', 'Acceso no autorizado.');
        }
        
        return view('residentes.confirmacion');
    }
}