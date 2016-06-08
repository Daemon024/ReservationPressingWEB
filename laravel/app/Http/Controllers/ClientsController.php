<?php

namespace App\Http\Controllers;

use DB;
use Request;
use App\Http\Requests\EditClientRequest;
use App\Prestations;
use App\Commandes;
use App\Clients;
use App\Employes;
use App\Produits;
use Auth;
use DateTime;
use Carbon\Carbon;
use App\Http\Requests;
//
class ClientController extends Controller
{
    public function ClientEdit (){
    	 $userId = Auth::id();
    	 $NomClient = '';
    	 $PrenomClient = '';
    	 $AdresseClient = '';
    	 $CodePostalClient = '';
    	 $VilleClient = '';
    	 $emailClient = '';
    	 $telClient = '';
    	 $passClient = '';
    	 $leClient = Clients::where('id', $userId)
               ->take(1)
               ->get();
     	 //
     	 foreach ($leClient as $leClient) {
     	 	$NomClient = $leClient->nom;
     	 	$PrenomClient = $leClient->prenom;
     	 	$AdresseClient = $leClient->adresse;
     	 	$CodePostalClient = $leClient->codepostal;
     	 	$VilleClient = $leClient->ville;
     	 	$emailClient = $leClient->email;
     	 	$telClient = $leClient->tel;
     	 	$passClient = $leClient->password;
     	 }
     	 //
      return view('appviews/compte', compact('leClient','NomClient','PrenomClient','AdresseClient','CodePostalClient','VilleClient','emailClient','telClient','passClient'))
                       ->with('leClient', $leClient)
                       ->with('NomClient', $NomClient)
                       ->with('PrenomClient', $PrenomClient)
                       ->with('AdresseClient', $AdresseClient)
                       ->with('CodePostalClient', $CodePostalClient)
                       ->with('VilleClient', $VilleClient)
                       ->with('emailClient', $emailClient)
                       ->with('telClient', $telClient)
                       ->with('passClient', $passClient);
        //
    }
    //
    public function store(){
    // Sauvegarde des nouvelles informations du client dans la BDD, on recupère l'utilisateur co avec Auth:: ,
    // on recupère toutes les informatiosn du form avec le Request::all() et ensuite on met tout dans un array/variable update
    // avec un for each on parcours l'array que la Request nous à renvoyé et on le file avec une requete update sur la BDD pour update les champs.
    $userId = Auth::id();
    $data = Request::all();
    if(is_null($data['password'])){
      $update = [
                  'nom'     => $data['nom'],
                  'prenom'   => $data['prenom'],
                  'adresse'     => $data['adresse'],
                  'codepostal'    => $data['codepostal'],
                  'ville'    => $data['ville'],
                  'email'    => $data['email'],
                  'tel'    => $data['tel'],

              ];
    }
    else {
      $update = [
                  'nom'     => $data['nom'],
                  'prenom'   => $data['prenom'],
                  'adresse'     => $data['adresse'],
                  'codepostal'    => $data['codepostal'],
                  'ville'    => $data['ville'],
                  'email'    => $data['email'],
                  'tel'    => $data['tel'],
                  'password'    => bcrypt($data['password']),
              ];
    }
    	DB::table('Clients')->where('id', $userId)->limit(1)->update($update);
      return redirect('/dashboard');
	}
}
