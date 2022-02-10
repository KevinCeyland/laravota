<?php

namespace App\Http\Controllers\Metier;

use App\Models\Election;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ResultatController extends Controller
{
    public function getResultatsElection($idElection) {

        $election = Election::find($idElection);
        $selectionsCandidat = $election->candidats;

        $arrayResultats = array();

        foreach ($selectionsCandidat as $key => $value) {
            $arrayResultats[$key]['name'] = $value['prenom'] . " " . $value['nom'];
            if($value->users) {
                $nbVotes = count($value->users()->where('election_id', "=", $idElection)->get());
            }
            $arrayResultats[$key]['data'] = array($nbVotes);
        }

        return response()->json(['resultat' => $arrayResultats]);
    }
}
