<?php

namespace App\Http\Controllers;

use App\Models\Apartment;
use App\Models\Owner;
use App\Models\Resident;
use App\Models\Relationship;
use App\Mail\VerificationCode;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class ResidentFormController extends Controller
{
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
            \Log::error('Error al enviar email: ' . $e->getMessage());
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
            
            // Cargar relaciones
            $apartamento->load(['owners', 'residents.relationship']);
            
            // Cargar todos los tipos de parentesco para el select
            $relationships = Relationship::orderBy('name')->get();
            
            return view('residentes.formulario', [
                'apartamento' => $apartamento,
                'relationships' => $relationships
            ]);
        } else {
            return back()->withErrors(['codigo' => 'El código ingresado no es válido.']);
        }
    }

    /**
     * Muestra el formulario para ingresar datos
     * Muestra el formulario para ingresar o actualizar datos
     */
    public function mostrarFormulario($number)
    {
        $apartamento = Apartment::where('number', $number)->first();
        
        // Cargar relaciones si el apartamento existe
        if ($apartamento) {
            // Cargar propietarios y residentes con eager loading
            $apartamento->load(['owners', 'residents.relationship']);
            
            // Verificar que los propietarios se cargaron correctamente
            if ($apartamento->owners->isEmpty()) {
                // Si no hay propietarios, intentar cargarlos nuevamente
                $apartamento->load('owners');
            }
        }
        
        // Cargar todos los tipos de parentesco para el select
        $relationships = Relationship::orderBy('name')->get();
        
        // Depurar para verificar que los datos se están pasando correctamente
        // dd($apartamento->toArray()); // Descomentar para depurar
        
        return view('residentes.formulario', [
            'apartamento' => $apartamento,
            'relationships' => $relationships
        ]);
    }

    /**
     * Guarda los datos del formulario
     */
    public function guardarDatos(Request $request)
    {
        $request->validate([
            'number' => 'required|string|max:5',
            'resident_name' => 'required|string|max:255',
            'resident_document' => 'required|string|max:20',
            'resident_phone' => 'required|string|max:20',
            'resident_email' => 'required|email|max:255',
            'has_bicycles' => 'nullable|boolean',
            'bicycles_count' => 'nullable|integer|min:1|required_if:has_bicycles,1',
            'received_manual' => 'nullable|boolean',
            'observations' => 'nullable|string',
            'owners' => 'nullable|array',
            'owners.*.name' => 'required|string|max:255',
            'owners.*.document' => 'required|string|max:20',
            'owners.*.phone' => 'nullable|string|max:20',
            'owners.*.email' => 'nullable|email|max:255',
            'residents' => 'nullable|array',
            'residents.*.name' => 'required|string|max:255',
            'residents.*.document' => 'required|string|max:20',
            'residents.*.phone' => 'nullable|string|max:20',
            'residents.*.email' => 'nullable|email|max:255',
            'residents.*.parentesco' => 'nullable|exists:relationships,id',
        ]);

        // Buscar o crear el apartamento
        $apartamento = Apartment::firstOrNew(['number' => $request->number]);
        
        // Actualizar datos del apartamento
        $apartamento->resident_name = strtoupper($request->resident_name);
        $apartamento->resident_document = $request->resident_document;
        $apartamento->resident_phone = $request->resident_phone;
        $apartamento->resident_email = strtolower($request->resident_email);
        $apartamento->has_bicycles = $request->has_bicycles ?? false;
        $apartamento->bicycles_count = $request->has_bicycles ? $request->bicycles_count : null;
        $apartamento->received_manual = $request->received_manual ?? false;
        $apartamento->observations = $request->observations;
        
        $apartamento->save();
        
        // Procesar propietarios
        if ($request->has('owners')) {
            // Eliminar propietarios existentes si los hay
            $apartamento->owners()->delete();
            
            // Agregar nuevos propietarios
            foreach ($request->owners as $ownerData) {
                $apartamento->owners()->create([
                    'name' => strtoupper($ownerData['name']),
                    'document_number' => $ownerData['document'],
                    'phone_number' => $ownerData['phone'] ?? null,
                    'email' => isset($ownerData['email']) ? strtolower($ownerData['email']) : null,
                ]);
            }
        }
        
        // Procesar residentes
        if ($request->has('residents')) {
            // Eliminar residentes existentes si los hay
            $apartamento->residents()->delete();
            
            // Agregar nuevos residentes
            foreach ($request->residents as $residentData) {
                $apartamento->residents()->create([
                    'name' => strtoupper($residentData['name']),
                    'document_number' => $residentData['document'],
                    'phone_number' => $residentData['phone'] ?? null,
                    'email' => isset($residentData['email']) ? strtolower($residentData['email']) : null,
                    'relationship_id' => $residentData['parentesco'] ?? null,
                ]);
            }
        }
        
        return redirect()->route('residentes.confirmacion');
    }

    /**
     * Muestra la página de confirmación
     */
    public function confirmacion()
    {
        return view('residentes.confirmacion');
    }
}
