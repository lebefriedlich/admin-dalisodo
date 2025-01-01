<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;

class BeritaModel extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'berita_desa';
    protected $primaryKey = 'id_berita';
    protected $fillable = [
        'judul',
        'slug',
        'konten',
        'tanggal'
    ];

    protected $hidden = [
        'created_at',
        'updated_at'
    ];

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->id_berita = Str::uuid();
        });
    }

    public function media()
    {
        return $this->hasOne(MediaModel::class, 'id_berita', 'id_berita');
    }
}
