<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;

class MediaModel extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'media';
    protected $primaryKey = 'id_media';
    protected $fillable = [
        'id_berita',
        'id_potensi',
        'file_id',
        'youtube_id',
        'tipe_media'
    ];

    protected $hidden = [
        'created_at',
        'updated_at'
    ];

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->id_media = Str::uuid();
        });
    }

    public function berita()
    {
        return $this->belongsTo(BeritaModel::class, 'id_berita', 'id_berita');
    }

    public function potensi()
    {
        return $this->belongsTo(PotensiModel::class, 'id_potensi', 'id_potensi');
    }

}
