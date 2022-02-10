<?php

namespace App\Http\Controllers\Metier;

use App\Models\Election;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ResultatController extends Controller
{
    public function getResultatsElection($idElection) {
        $elections = Election::find($idElection);
        $candidatsElections = $elections->candidats;
        $arrayResultats = array();
        foreach ($candidatsElections as $key => $value) {
            $arrayResultats[$key]['name'] = $value['prenom'] . " " . $value['nom'];
            if($value->users) {
                $nbVotes = count($value->users);
                $arrayResultats[$key]['data'] = array($nbVotes);
            }
        }
        return response()->json(['resultat' => $arrayResultats]);
    }
}
