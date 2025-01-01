<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;

class PotensiModel extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'potensi_desa';
    protected $primaryKey = 'id_potensi';
    protected $fillable = [
        'judul',
        'slug',
        'deskripsi',
        'kategori'
    ];

    protected $hidden = [
        'created_at',
        'updated_at'
    ];

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->id_potensi = Str::uuid();
        });
    }

    public function media()
    {
        return $this->hasOne(MediaModel::class, 'id_potensi', 'id_potensi');
    }
}
