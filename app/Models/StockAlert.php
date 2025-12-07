<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockAlert extends Model
{
    protected $fillable = ['user_id', 'producto_id', 'notified_at'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function producto()
    {
        return $this->belongsTo(Producto::class);
    }
}
