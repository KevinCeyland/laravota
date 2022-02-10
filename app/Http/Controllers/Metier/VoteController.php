<?php

namespace App\Http\Controllers\Metier;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class VoteController extends Controller
{
    public function voteCandidat(Request $request)
    {

            if (Auth::user()->role->libelle != "Administrateur") {
                $user = User::find(Auth::user()->id);
                foreach ($user->candidats as $key => $value) {
                    if($value->pivot->election_id == $request->idElection && $value->pivot->user_id == Auth::user()->id) {
                        return response()->json(["messageVoteDouble" => "Vous ne pouvez pas voter une seconde fois dans cette éléction !"]);
                    }
                }

                $user->candidats()->attach($request->idCandidat, array("election_id" => $request->idElection));

                return response()->json(["message" => "Le vote à bien été pris en compte !"]);
            } else {
                return response()->json(["messageError" => "Vous ne pouvez pas voter en tant qu'administrateur !"]);
            }

    }
}
