<?php

namespace App\Http\Controllers\Metier;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class VoteController extends Controller
{
    public function voteCandidat($idCandidat)
    {
        try {
            if (Auth::user()->role->libelle != "Administrateur") {
                $user = User::find(Auth::user()->id);
                if (count($user->candidats) > 0) {
                    return response()->json(["messageVoteDouble" => "Vous ne pouvez pas voter une seconde fois !"]);
                }
                $user->candidats()->attach($idCandidat);

                return response()->json(["message" => "Le vote à bien été pris en compte !"]);
            } else {
                return response()->json(["messageError" => "Vous ne pouvez pas voter en tant qu'administrateur !"]);
            }
        } catch (\Throwable $th) {
            return response()->json(["messageError" => "Une erreur est survenue lors du vote, veuillez réessayer plus tard !"]);
        }
    }
}
