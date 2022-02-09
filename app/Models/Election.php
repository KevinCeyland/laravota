<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Election extends Model
{
    use HasFactory;
    protected $guarded = [];
    public function vote()
    {
        return $this->belongsToMany(User::class, "vote");
    }
    public function candidats()
    {
        return $this->belongsToMany(Candidat::class, "vote");
    }
}
