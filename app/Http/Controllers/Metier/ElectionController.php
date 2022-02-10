<?php

namespace App\Http\Controllers\Metier;

use App\Models\Election;
use App\Helpers\ParamHelper;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class ElectionController extends Controller
{
    /**
     * Récupère toutes les elections et les retournes sous formes d'array
     *
     * @return Array
     */
    public function index()
    {
        $elections = Election::all();
        $arrayElections = array();
        foreach ($elections as $key => $value) {
            $arrayElections[$key]['id'] = $value['id'];
            $arrayElections[$key]['libelle'] = $value['libelle'];
            $arrayElections[$key]['dateElection'] = $value['dateElection'];
        }
        return response()->json(['elections' => $arrayElections]);
    }
    public function store(Request $request)
    {
        try {

            $election = new Election();


            $validator = Validator::make($request->all(), [
                'libelle' => 'required|string',
                'dateElection' => 'required',

            ]);

            if ($validator->fails()) {
                $messageError = $validator->errors();
                $messageError = $messageError->getMessages();
                $first_key = array_key_first($messageError);
                $first_value = $messageError[$first_key];
                return response()->json(["messageError" => $first_value]);
            }


            $validated = $validator->validated();

            $election->libelle = $validated['libelle'];
            $election->dateElection = $validated['dateElection'];
            $election->save();

            $arrayElection['id'] = $election->id;
            $arrayElection['libelle'] = $election->libelle;
            $arrayElection['dateElection'] = $election->dateElection;


            return response()->json(['message' => "L'éléction a bien été enregistrer !", "election" => $arrayElection]);

        } catch (\Throwable $th) {
            return response()->json(['messageError' => "Une erreur est survenu avec l'enregistrement de l'éléction !"]);
        }
    }
    public function update($id, Request $request)
    {

        $election = Election::find($id);


        $validator = Validator::make($request->all(), [
            'libelle' => 'required|string',
            'dateElection' => 'required',

        ]);

        if ($validator->fails()) {
            $messageError = $validator->errors();
            $messageError = $messageError->getMessages();
            $first_key = array_key_first($messageError);
            $first_value = $messageError[$first_key];
            return response()->json(["messageError" => $first_value]);
        }


        $validated = $validator->validated();

        $election->libelle = $validated['libelle'];
        $election->dateElection = $validated['dateElection'];
        $election->save();

        $arrayElection['id'] = $election->id;
        $arrayElection['libelle'] = $election->libelle;
        $arrayElection['dateElection'] = $election->dateElection;


        return response()->json(['message' => "L'éléction a bien été modifier !", "election" => $arrayElection]);
    }
    public function delete($id)
    {
        try {
            $election = Election::find($id);
            $election->candidats()->detach();
            $election->delete();
            return response()->json(['message' => "L'éléction a bien été supprimé !"]);
        } catch (\Throwable $th) {
            return response()->json(['messageError' => "Une erreur est survenu avec la suppréssion de l'éléction !", 'id' => $id]);
        }
    }
}
