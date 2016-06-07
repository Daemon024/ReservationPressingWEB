<?php

namespace App\Http\Controllers;
use DB;
use Request;
use App\Http\Requests\CreateReservationRequest;
use App\Prestations;
use App\Commandes;
use App\Clients;
use App\Employes;
use App\Produits;
use Auth;
use DateTime;
use Carbon\Carbon;
use App\Http\Requests;

class CommandesController extends Controller
{
    public function create()
    {

    }
    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store()
    {
      $data = Request::all();
      $commande = Commandes::where('clients_id', $data['clients_id'])
               ->where('Commandes.dateDepot', $data['dateDepot'])
               ->take(1)
               ->get();
      $client = Clients::where('id', $data['clients_id'])
               ->take(1)
               ->get();
      $produit = Produits::where('id', $data['produits_id'])
               ->take(1)
               ->get();
      $prestation = Prestations::where('id', $data['prestations_id'])
               ->take(1)
               ->get();
      return view('prestationsviews/confirmationReservation', compact('commande','client','produit','prestation'))
                       ->with('commande', $commande)
                       ->with('client', $client)
                       ->with('produit', $produit)
                       ->with('prestation', $prestation);
    }
    //------------------------------------------------------------------------------------------//
    public function priseReservation()
    {
       $userId = Auth::id();
       $produits = DB::table('Produits')->lists('nom', 'id');
       $prestations = DB::table('Prestations')->lists('nom', 'id');
       return view('prestationsviews/reservation', compact('userId','produits','prestations'))
                       ->with('userId', $userId)
                       ->with('prestations', $prestations)
                       ->with('produits', $produits);
    }
    //------------------------------------------------------------------------------------------//
	 /**
	 * Fonction de récupération des commandes, jointure sur les tables clients, produits, reservations, employes.
	 * on retourne un objet avec toutes les infos relatives au prestations du client.
	 */
    //------------------------------------------------------------------------------------------//
    public function RecupCommandes()
    {
        $userId = Auth::id();
        // Requête pour récupérer les prestations du clients
    		$Commandes = DB::table('Commandes')->where('Commandes.clients_id', $userId)
    			      ->join('Clients', 'clients_id', '=', 'Clients.id')
                ->join('Prestations', 'prestations_id', '=', 'Prestations.id')
                ->join('Employes', 'employes_id', '=', 'Employes.id')
                ->join('Produits', 'produits_id', '=', 'Produits.id')
                ->selectRaw('Employes.nom as nomEmploye, Employes.prenom as prenomEmploye, Employes_id as idEmploye, Clients.nom as nomClient, Clients.prenom as prenomClient, commentaire, dateDepot, pretPourRecuperation, dateRecuperation, adresse, codepostal, ville, email, tel, dateArrivee, typeContrat, salaire, Prestations.nom as nomPrestation, Produits.nom as nomProduit')
                ->orderBy('Commandes.id','desc')
                ->get();
        //------------------------------------------------------------------------------------------//
        $CalendrierFetch = json_encode(DB::table('Commandes')->where('Commandes.clients_id', $userId)
                ->join('Clients', 'clients_id', '=', 'Clients.id')
                ->join('Prestations', 'prestations_id', '=', 'Prestations.id')
                ->join('Produits', 'produits_id', '=', 'Produits.id')
                ->orderBy('dateDepot')
                ->lists('dateDepot','Produits.nom'));
        $GraphCommandesT = DB::table('Commandes')->where('Commandes.clients_id', $userId)
                ->join('Clients', 'clients_id', '=', 'Clients.id')
                ->join('Prestations', 'prestations_id', '=', 'Prestations.id')
                ->join('Produits', 'produits_id', '=', 'Produits.id')
                ->join('Tarifs', 'Tarifs.Prestations_id', '=', 'Prestations.id')
                ->selectRaw('Produits.nom, COUNT(*) as count')
                ->groupBy('Produits.nom')
                ->orderBy('count', 'desc')
                ->lists('Produits.nom');
        //------------------------------------------------------------------------------------------//
        $GraphCommandesN = DB::table('Commandes')->where('Commandes.clients_id', $userId)
                ->join('Clients', 'clients_id', '=', 'Clients.id')
                ->join('Prestations', 'prestations_id', '=', 'Prestations.id')
                ->join('Produits', 'produits_id', '=', 'Produits.id')
                ->join('Tarifs', 'Tarifs.Prestations_id', '=', 'Prestations.id')
                ->selectRaw('Produits.nom, COUNT(*) as count')
                ->groupBy('Produits.nom')
                ->orderBy('count', 'desc')
                ->lists('count');
        // Requête séparé en deux parties pour les statistiques client avec ChartsJS
        return view('prestationsviews/dashboard', compact('GraphCommandesT','GraphCommandesN','Commandes'))
                       ->with('Commandes', $Commandes)
                       ->with('GraphCommandesT', $GraphCommandesT)
                       ->with('GraphCommandesN', $GraphCommandesN);
        //------------------------------------------------------------------------------------------//
    }
}
