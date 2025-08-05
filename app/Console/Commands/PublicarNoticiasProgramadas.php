<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Carbon\Carbon;

class PublicarNoticiasProgramadas extends Command
{
    protected $signature = 'noticias:publicar-programadas';
    protected $description = 'Publica noticias cuya fecha de publicación ya llegó y sube su imagen a Cloudinary';

    public function handle()
    {
        $noticias = DB::connection('mongodb')
            ->collection('noticia')
            ->where('activo', false)
            ->where('fechaPublicacion', '<=', new \MongoDB\BSON\UTCDateTime(now()->getTimestampMs()))
            ->get();

        foreach ($noticias as $noticia) {
            $this->info("Publicando noticia: {$noticia['titulo']}");

            // Subir imagen si no está en Cloudinary
            if (isset($noticia['imagenLocalPath']) && !isset($noticia['imagenURL'])) {
                $cloudinary = Cloudinary::upload(storage_path('app/' . $noticia['imagenLocalPath']));
                $imagenURL = $cloudinary->getSecurePath();
            } else {
                $imagenURL = $noticia['imagenURL'] ?? null;
            }

            // Actualizar noticia
            DB::connection('mongodb')->collection('noticia')->where('_id', $noticia['_id'])->update([
                'activo' => true,
                'imagenURL' => $imagenURL,
            ]);
        }

        return Command::SUCCESS;
    }
}
