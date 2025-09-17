<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SetorGrid extends Model
{
    use HasFactory;

    protected $table = 'tb_setoresGrid';
    protected $primaryKey = 'idGrid';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'idSetor',
        'posX',
        'posY',
    ];

    // Relacionamento com setor
    public function setor()
    {
        return $this->belongsTo(Setor::class, 'idSetor', 'idSetor');
    }
}
