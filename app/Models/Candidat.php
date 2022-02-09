<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
        return $this->belongsToMany(Election::class, "vote");
    }
}
