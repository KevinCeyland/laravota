<?php

namespace App\Http\Controllers\Metier;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class VoteController extends Controller
{
    public function voteCandidat($idCandidat) {
        try {

            $user = User::find(Auth::user()->id);
            if($user->candidats) {
                return response()->json(["messageVoteDouble" => "Vous ne pouvez pas voter une seconde fois !"]);
            }
            $user->candidats()->attach($idCandidat);

            return response()->json(["message" => "Le vote à bien été pris en compte !"]);
        } catch (\Throwable $th) {
            return response()->json(["messageError" => "Une erreur est survenue lors du vote, veuillez réessayer plus tard !"]);
        }

    }
}
