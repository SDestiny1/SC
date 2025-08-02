<?php

namespace App\Imports;

use App\Models\User;
use App\Models\Group;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class StudentsImport implements ToCollection, WithHeadingRow
{
    private $imported = 0;
    private $skipped = 0;
    private $errors = [];
    
    public function collection(Collection $rows)
    {
        foreach ($rows as $index => $row) {
            try {
                // Normalizar nombres de columnas
                $row = $this->normalizeRow($row);
                
                // Validar estructura del archivo
                if (!$this->validateRow($row, $index)) {
                    continue;
                }
                
                // Verificar si el usuario ya existe
                if (User::where('_id', $row['correo'])->exists()) {
                    $this->errors[] = "Fila {$index}: El correo {$row['correo']} ya existe";
                    $this->skipped++;
                    continue;
                }
                
                // Buscar el grupo por _id (exact match, case sensitive)
                $grupoModel = Group::find($row['grupo']);
                
                if (!$grupoModel) {
                    $this->errors[] = "Fila {$index}: No existe un grupo con ID {$row['grupo']}";
                    $this->skipped++;
                    continue;
                }
                
                // Crear el usuario
                User::create([
                    '_id' => $row['correo'],
                    'nombre' => $row['nombre'],
                    'apellidoPaterno' => $row['apellido_paterno'],
                    'apellidoMaterno' => $row['apellido_materno'] ?? null,
                    'rol' => 'alumno',
                    'grupoID' => $grupoModel->_id, // Usamos el _id del grupo encontrado
                    'password' => bcrypt(Str::lower($row['nombre']) . '123'),
                    'activo' => true,
                    'fechaRegistro' => now(),
                    'updated_at' => now()
                ]);
                
                $this->imported++;
                
            } catch (\Exception $e) {
                $this->errors[] = "Fila {$index}: Error - " . $e->getMessage();
                $this->skipped++;
                Log::error("Error importing student row {$index}: " . $e->getMessage());
            }
        }
    }
    
    private function normalizeRow($row)
    {
        // Normalizar nombres de columnas a un formato estándar
        return [
            'nombre' => $row['nombre'] ?? $row['Nombre'] ?? null,
            'apellido_paterno' => $row['apellido_paterno'] ?? $row['apellidopaterno'] ?? $row['Apellido paterno'] ?? null,
            'apellido_materno' => $row['apellido_materno'] ?? $row['apellidomaterno'] ?? $row['Apellido materno'] ?? null,
            'correo' => $row['correo'] ?? $row['Correo'] ?? $row['email'] ?? null,
            'grupo' => $row['grupo'] ?? $row['Grupo'] ?? null
        ];
    }
    
    private function validateRow($row, $index)
    {
        // Validar campos requeridos
        $requiredFields = ['nombre', 'apellido_paterno', 'correo', 'grupo'];
        
        foreach ($requiredFields as $field) {
            if (empty($row[$field])) {
                $this->errors[] = "Fila {$index}: Falta el campo requerido '{$field}'";
                $this->skipped++;
                return false;
            }
        }
        
        // Validar formato de correo
        if (!filter_var($row['correo'], FILTER_VALIDATE_EMAIL)) {
            $this->errors[] = "Fila {$index}: El correo {$row['correo']} no es válido";
            $this->skipped++;
            return false;
        }
        
        return true;
    }
    
    public function getImportedCount()
    {
        return $this->imported;
    }
    
    public function getSkippedCount()
    {
        return $this->skipped;
    }
    
    public function getErrors()
    {
        return $this->errors;
    }
}