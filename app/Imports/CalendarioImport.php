<?php
namespace App\Imports;

use App\Models\SchoolCalendar;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use MongoDB\BSON\UTCDateTime;
use Illuminate\Support\Facades\Log;
use PhpOffice\PhpSpreadsheet\Shared\Date as ExcelDate;

class CalendarioImport implements ToCollection, WithHeadingRow
{
    private $importedCount = 0;
    private $skippedCount = 0;

    public function collection(Collection $rows)
    {
        Log::info('Iniciando importación con '.$rows->count().' filas');
        
        $calendario = SchoolCalendar::firstOrCreate(
            ['_id' => 'calendario_2025A'],
            ['año' => 2025, 'eventos' => []]
        );

        foreach ($rows as $index => $row) {
            try {
                // Validación de campos requeridos
                if (empty($row['tipo']) || empty($row['nombre']) || empty($row['fecha_inicio'])) {
                    Log::warning("Fila {$index} omitida - Campos requeridos vacíos");
                    $this->skippedCount++;
                    continue;
                }

                // Convertir fechas de Excel (ya sea número serial o texto)
                $fechaInicio = $this->parseExcelDate($row['fecha_inicio']);
                $fechaFin = !empty($row['fecha_fin']) 
                    ? $this->parseExcelDate($row['fecha_fin'])
                    : $fechaInicio;

                if (!$fechaInicio) {
                    Log::error("Fecha inválida en fila {$index}");
                    $this->skippedCount++;
                    continue;
                }

                // Crear el evento
                $evento = [
                    'nombreEvento' => $row['nombre'],
                    'tipoEvento' => $row['tipo'],
                    'fechaInicio' => $fechaInicio,
                    'fechaFin' => $fechaFin,
                    'descripcion' => $row['descripcion'] ?? null,
                    'activo' => true,
                    'imported_at' => new UTCDateTime(now()->getTimestamp() * 1000)
                ];

                // Insertar en MongoDB
                $calendario->push('eventos', $evento);
                $this->importedCount++;

                Log::info("Fila {$index} importada: ".json_encode($evento));

            } catch (\Exception $e) {
                Log::error("Error en fila {$index}: ".$e->getMessage());
                $this->skippedCount++;
            }
        }

        Log::info("Importación completada. Importados: {$this->importedCount}, Omitidos: {$this->skippedCount}");
    }

    private function parseExcelDate($value)
    {
        try {
            // Si es un número serial de Excel (como 45999)
            if (is_numeric($value)) {
                return new UTCDateTime(ExcelDate::excelToTimestamp($value) * 1000);
            }
            
            // Si es una cadena de texto (como "2025-12-08")
            if (is_string($value)) {
                $date = Carbon::createFromFormat('Y-m-d', trim($value));
                return new UTCDateTime($date->getTimestamp() * 1000);
            }

            return null;
        } catch (\Exception $e) {
            Log::error("Error al parsear fecha: {$value} - ".$e->getMessage());
            return null;
        }
    }

    public function getImportedCount(): int
    {
        return $this->importedCount;
    }

    public function getSkippedCount(): int
    {
        return $this->skippedCount;
    }
}