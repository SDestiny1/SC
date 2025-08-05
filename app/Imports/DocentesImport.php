<?php
namespace App\Imports;

use App\Models\User;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class DocentesImport implements ToCollection
{
    public $imported = 0;
    public $skipped = 0;
    public $skippedDetails = [];

    public function collection(Collection $rows)
    {
        $rows->shift(); // quitar encabezado

        foreach ($rows as $index => $row) {
            if (!$row[0] || !$row[1]) continue;

            $correo = strtolower(trim($row[1]));

            if (User::where('_id', $correo)->exists()) {
                $this->skipped++;
                $this->skippedDetails[] = "Fila {$index}: El correo {$correo} ya existe";
                continue;
            }

            $nombre = ucfirst(trim($row[0]));
            $apellidoPaterno = ucfirst(trim($row[2]));
            $apellidoMaterno = ucfirst(trim($row[3]));
            $grupoID = trim($row[4]);

            try {
                User::create([
                    '_id' => $correo,
                    'nombre' => $nombre,
                    'apellidoPaterno' => $apellidoPaterno,
                    'apellidoMaterno' => $apellidoMaterno,
                    'grupoID' => $grupoID,
                    'rol' => 'docente',
                    'password' => Hash::make($nombre . '123'),
                    'activo' => true,
                    'fechaRegistro' => now(),
                    'fechaNacimiento' => null,
                ]);
                $this->imported++;
            } catch (\Exception $e) {
                $this->skipped++;
                $this->skippedDetails[] = "Fila {$index}: Error inesperado al insertar {$correo}";
                Log::error($e);
            }
        }
    }
}
