<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class ServicioImagen extends Model
{
    protected $table = 'servicio_imagens';
    protected $fillable = ['servicio_id','ruta','orden'];
    
    public function getUrlAttribute(): string
    {
        $p = str_replace('\\', '/', $this->ruta ?? '');
        if (!$p) return asset('images/place.png');

        if (str_starts_with($p, 'public/')) {
            return Storage::url(substr($p, 7));
        }
        if (str_starts_with($p, '/storage/') || str_starts_with($p, 'storage/')) {
            return asset(ltrim($p, '/'));
        }
        if (str_starts_with($p, '/images/') || str_starts_with($p, 'images/')) {
            return asset(ltrim($p, '/'));
        }
        if (preg_match('#^https?://#i', $p)) {
            return $p;
        }
        return asset(ltrim($p, '/'));
    }
}