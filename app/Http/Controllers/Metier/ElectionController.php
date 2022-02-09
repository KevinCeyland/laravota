<?php

namespace App\Http\Controllers\Metier;

use App\Models\Election;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ElectionController extends Controller
{
    /**
     * Récupère toutes les elections et les retournes sous formes d'array
     *
     * @return Array
     */
    public function index() {
        $elections = Election::all();
        $arrayElections = array();
        foreach ($elections as $key => $value) {
            $arrayElections[$key]['id'] = $value['id'];
            $arrayElections[$key]['libelle'] = $value['libelle'];
        }
        return response()->json(['elections'=>$arrayElections]);
    }
}
