<?php

namespace App\Models;

use App\Models\Candidat;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PartiePolitique extends Model
{
    use HasFactory;
    protected $guarded = [];
    public function candidats()
    {
        return $this->hasMany(Candidat::class);
    }
}
