<?php

namespace App\Http\Controllers\Metier;

use App\Models\User;
use App\Models\Election;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function index() {
        $users = User::all();
        $arrayUsers = array();
        foreach ($users as $key => $value) {
            $arrayUsers[$key]['id'] = $value['id'];
            $arrayUsers[$key]['nom'] = $value['nom'];
            $arrayUsers[$key]['prenom'] = $value['prenom'];
            $arrayUsers[$key]['email'] = $value['email'];
            $arrayUsers[$key]['rue'] = $value['rue'];
            $arrayUsers[$key]['ville'] = $value['ville'];
            $arrayUsers[$key]['role'] = $value->role->libelle;
            $arrayUsers[$key]['carte_identite'] = $value['carte_identite'];
            $arrayUsers[$key]['codePostal'] = $value['codePostal'];
        }
        return response()->json(['users' => $arrayUsers]);
    }
    public function update(Request $request, $id) {
        try {

            $user = User::find($id);


            $validator = Validator::make($request->all(), [
                'nom' => 'required|string',
                'prenom' => 'required|string',
                'email' => 'required|email',
                'rue' => 'required|string',
                'ville' => 'required|string',
                'codePostal' => 'required|string',
                'carte_identite' => 'required',
            ]);

            if ($validator->fails()) {
                $messageError = $validator->errors();
                $messageError = $messageError->getMessages();
                $first_key = array_key_first($messageError);
                $first_value = $messageError[$first_key];
                return response()->json(["messageError" => $first_value]);
            }


            $validated = $validator->validated();

            $user->nom = $validated['nom'];
            $user->prenom = $validated['prenom'];
            $user->email = $validated['email'];
            $user->rue = $validated['rue'];
            $user->ville = $validated['ville'];
            $user->codePostal = $validated['codePostal'];
            $user->carte_identite = $validated['carte_identite'];

            $user->save();

            return response()->json(['message' => "L'utilisateur à bien été modifié !", "user" => $user]);

        } catch(\Throwable $e) {
            return response()->json(['messageError' => "Une erreur est survenu avec l'enregistrement de l'utilisateur !"]);
        }
    }
    public function delete($id) {
        $user = User::find($id);
        $user->candidats()->detach();
        $user->delete();
        return response()->json(['message' => "L'utilisateur à bien été supprimé !", "id" => $id]);
    }
}
