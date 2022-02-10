<?php

namespace App\Http\Controllers\Metier;

use App\Models\Candidat;
use Illuminate\Support\Str;
use App\Helpers\ParamHelper;
use Illuminate\Http\Request;
use App\Models\PartiePolitique;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class CandidatController extends Controller
{
    /**
     * Récupère tous les candidats et les retournes sous formes d'array
     *
     * @return Array
     */
    public function index($idElection)
    {
        try {
            $candidats = Candidat::whereHas("elections", function ($q) use ($idElection) {
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
        } catch (\Throwable $th) {
            return response()->json(['messageError' => "Une erreur est survenu avec la base de données !"]);
        }
    }
    public function indexAll()
    {
        try {
            $candidats = Candidat::all();
            $arrayCandidats = array();
            foreach ($candidats as $key => $value) {
                $arrayCandidats[$key]['id'] = $value['id'];
                $arrayCandidats[$key]['nom'] = $value['nom'];
                $arrayCandidats[$key]['prenom'] = $value['prenom'];
                $arrayCandidats[$key]['date_naissance'] = $value['date_naissance'];
                $arrayCandidats[$key]['partie_politique'] = $value->partie_politique->libelle;
                $arrayCandidats[$key]['partie_politique_id'] =  $value['partie_politique_id'];
                $arrayCandidats[$key]['programme'] = $value['programme'];
                $arrayCandidats[$key]['photo'] = ParamHelper::getImageStringAttribute($value['photo'], "img/" . pathinfo($value['photo'], PATHINFO_EXTENSION), 'photoCandidat');
            }
            $partie_politique = PartiePolitique::all();
            $arrayPartiePolitique = array();
            foreach ($partie_politique as $key => $value) {
                $arrayPartiePolitique[$key]['id'] = $value['id'];
                $arrayPartiePolitique[$key]['libelle'] = $value['libelle'];
            }
            return response()->json(['candidats' => $arrayCandidats, "partie_politique" => $arrayPartiePolitique]);
        } catch (\Throwable $th) {
            return response()->json(['messageError' => "Une erreur est survenu avec la base de données !"]);
        }
    }
    public function store(Request $request)
    {
        try {
            $candidat = new Candidat();

            if ($request->photo == null || $request->photo == "undefined") {
                $candidat->photo = null;
            } else {
                $validator = Validator::make($request->all(), [
                    'photo' => 'image' . ParamHelper::validateExtensions(),
                ]);

                if ($validator->fails()) {
                    $messageError = $validator->errors();
                    $messageError = $messageError->getMessages();
                    $first_key = array_key_first($messageError);
                    $first_value = $messageError[$first_key];
                    return response()->json(["messageError" => $first_value]);
                }

                $validated = $validator->validate();

                $namePhoto = Str::random(32) . "." . $validated['photo']->getClientOriginalExtension();

                $pathCouverturesTutos = "photoCandidat/" . $namePhoto;

                Storage::disk("public_folder")->put($pathCouverturesTutos, ParamHelper::resizeImg($validated['photo'])->__toString());

                $candidat->photo = $namePhoto;
            }
            $validator = Validator::make($request->all(), [
                'nom' => 'required|string',
                'prenom' => 'required|string',
                'date_naissance' => 'nullable',
                'programme' => 'required',
                'partie_politique_id' => 'required',
            ]);

            if ($validator->fails()) {
                $messageError = $validator->errors();
                $messageError = $messageError->getMessages();
                $first_key = array_key_first($messageError);
                $first_value = $messageError[$first_key];
                return response()->json(["messageError" => $first_value]);
            }


            $validated = $validator->validated();


            $candidat->nom = $validated['nom'];
            $candidat->prenom = $validated['prenom'];
            $candidat->date_naissance = $validated['date_naissance'];
            $candidat->programme = $validated['programme'];
            $candidat->partie_politique_id = $validated['partie_politique_id'];

            $candidat->save();

            $arrayCandidat['id'] = $candidat->id;
            $arrayCandidat['nom'] = $candidat->nom;
            $arrayCandidat['prenom'] = $candidat->prenom;
            $arrayCandidat['date_naissance'] =  $candidat->date_naissance;
            $arrayCandidat['partie_politique'] =  $candidat->partie_politique->libelle;
            $arrayCandidat['partie_politique_id'] =  $candidat->partie_politique_id;
            $arrayCandidat['programme'] = $candidat->programme;
            $arrayCandidat['photo'] = ParamHelper::getImageStringAttribute($candidat->photo, "img/" . pathinfo($candidat->photo, PATHINFO_EXTENSION), 'photoCandidat');

            return response()->json(['message' => "Le candidat a bien été enregistré !", "candidat" => $arrayCandidat]);
        } catch (\Throwable $th) {
            return response()->json(['messageError' => "Une erreur est survenu avec l'enregistrement du candidat !"]);
        }
    }
    public function update($id, Request $request)
    {

            $candidat = Candidat::find($id);

            if ($request['changePhoto'] == true) {

                $validator = Validator::make($request->all(), [
                    'photo' => 'image' . ParamHelper::validateExtensions(),
                ]);

                if ($validator->fails()) {
                    $messageError = $validator->errors();
                    $messageError = $messageError->getMessages();
                    $first_key = array_key_first($messageError);
                    $first_value = $messageError[$first_key];
                    return response()->json(["messageError" => $first_value]);
                }

                $validated = $validator->validate();

                $namePhoto = Str::random(32) . "." . $validated['photo']->getClientOriginalExtension();

                $pathCouverturesTutos = "photoCandidat/" . $namePhoto;

                Storage::disk("public_folder")->put($pathCouverturesTutos, ParamHelper::resizeImg($validated['photo'])->__toString());

                $candidat->photo = $namePhoto;
            }

            $validator = Validator::make($request->all(), [
                'nom' => 'required|string',
                'prenom' => 'required|string',
                'date_naissance' => 'nullable',
                'programme' => 'nullable',
                'partie_politique_id' => 'required',
            ]);

            if ($validator->fails()) {
                $messageError = $validator->errors();
                $messageError = $messageError->getMessages();
                $first_key = array_key_first($messageError);
                $first_value = $messageError[$first_key];
                return response()->json(["messageError" => $first_value]);
            }


            $validated = $validator->validated();

            $candidat->nom = $validated['nom'];
            $candidat->prenom = $validated['prenom'];
            $candidat->date_naissance = $validated['date_naissance'];
            $candidat->programme = $validated['programme'];
            $candidat->partie_politique_id = $validated['partie_politique_id'];
            $candidat->save();

            $arrayCandidat['id'] = $candidat->id;
            $arrayCandidat['nom'] = $candidat->nom;
            $arrayCandidat['prenom'] = $candidat->prenom;
            $arrayCandidat['date_naissance'] =  $candidat->date_naissance;
            $arrayCandidat['partie_politique'] =  $candidat->partie_politique->libelle;
            $arrayCandidat['partie_politique_id'] =  $candidat->partie_politique_id;
            $arrayCandidat['programme'] = $candidat->programme;
            $arrayCandidat['photo'] = ParamHelper::getImageStringAttribute($candidat->photo, "img/" . pathinfo($candidat->photo, PATHINFO_EXTENSION), 'photoCandidat');

            return response()->json(['message' => "Le candidat a bien été modifier !", "candidat" => $arrayCandidat]);

    }
    public function delete($id)
    {
        try {
            $candidat = Candidat::find($id);
            ParamHelper::deleteFile($candidat->photo, "photoCandidat");
            $candidat->elections()->detach();
            $candidat->users()->detach();
            $candidat->delete();
            return response()->json(['message' => "Le candidat a bien été supprimé !"]);
        } catch (\Throwable $th) {
            return response()->json(['messageError' => "Une erreur est survenu avec la suppréssion du candidat !", 'id' => $id]);
        }
    }
}
