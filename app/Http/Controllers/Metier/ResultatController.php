<?php

namespace App\Http\Controllers\Metier;

use App\Models\Election;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ResultatController extends Controller
{
    public function getResultatsElection($idElection) {
        $elections = Election::find($idElection);
        $electionsVotes = $elections->votes;
        $arrayResultats = array();
        foreach ($electionsVotes as $key => $value) {
            $arrayResultats[$key]['name'] = $value['prenom'] . " " . $value['nom'];
            if($value->votes) {
                $nbVotes = count($value->pivot->where('election_id', "=", $idElection)->get());
                $arrayResultats[$key]['data'] = array($nbVotes);
            }
        }
        return response()->json(['resultat' => $arrayResultats]);
    }
}
