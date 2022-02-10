<?php

namespace App\Http\Controllers\Metier;

use App\Models\Candidat;
use App\Helpers\ParamHelper;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CandidatController extends Controller
{
     /**
     * Récupère tous les candidats et les retournes sous formes d'array
     *
     * @return Array
     */
    public function index($idElection) {
        $candidats = Candidat::whereHas("elections", function($q) use ($idElection) {
            $q->where('election_id', '=', $idElection);
        })->get();
        $arrayCandidats = array();
        foreach ($candidats as $key => $value) {
            $arrayCandidats[$key]['id'] = $value['id'];
            $arrayCandidats[$key]['nom'] = $value['nom'];
            $arrayCandidats[$key]['prenom'] = $value['prenom'];
            $arrayCandidats[$key]['partie_politique'] = $value->partie_politique->libelle;
            $arrayCandidats[$key]['date_naissance'] = $value['date_naissance'];
            $arrayCandidats[$key]['programme'] = $value['programme'];
            $arrayCandidats[$key]['photo'] = ParamHelper::getImageStringAttribute($value['photo'], "img/" . pathinfo($value['photo'], PATHINFO_EXTENSION), 'photoCandidat');

        }
        return response()->json(['candidats' => $arrayCandidats]);
    }
    public function indexAll() {
        $candidats = Candidat::all();
        $arrayCandidats = array();
        foreach ($candidats as $key => $value) {
            $arrayCandidats[$key]['id'] = $value['id'];
            $arrayCandidats[$key]['nom'] = $value['nom'];
            $arrayCandidats[$key]['prenom'] = $value['prenom'];
            $arrayCandidats[$key]['date_naissance'] = $value['date_naissance'];
            $arrayCandidats[$key]['partie_politique'] = $value->partie_politique->libelle;
            $arrayCandidats[$key]['programme'] = $value['programme'];
            $arrayCandidats[$key]['photo'] = ParamHelper::getImageStringAttribute($value['photo'], "img/" . pathinfo($value['photo'], PATHINFO_EXTENSION), 'photoCandidat');

        }
        return response()->json(['candidats' => $arrayCandidats]);
    }
}
