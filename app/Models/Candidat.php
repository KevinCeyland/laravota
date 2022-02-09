<?php

namespace App\Models;

use App\Models\PartiePolitique;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Candidat extends Model
{
    use HasFactory;
    protected $guarded = [];
    public function users()
    {
        return $this->belongsToMany(User::class, "vote");
    }
    public function elections()
    {
        return $this->belongsToMany(Election::class);
    }
    public function partie_politique()
    {
        return $this->belongsTo(PartiePolitique::class);
    }
}
