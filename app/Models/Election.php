<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Election extends Model
{
    use HasFactory;
    protected $guarded = [];
    public function candidats()
    {
        return $this->belongsToMany(Candidat::class);
    }
    public function votes()
    {
        return $this->belongsToMany(Candidat::class, "votes")->withPivot('election_id');
    }
}
