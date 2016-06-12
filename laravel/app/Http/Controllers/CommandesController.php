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

      ///////////////////////////////////////////////////////////////
      $nomProduitI = '';
      $produits_idI = '';
      $tarifI = '';
      $prestaidI = '';
      $userId = Auth::id();
      $idProduit = Produits::where('nom', $data['nomProduit'])
               ->take(1)
               ->lists('id');
      ///////////////////////////////////////////////////////////////
      $idPresta = Prestations::where('nom', $data['prestations_id'])
               ->take(1)
               ->get();
      ///////////////////////////////////////////////////////////////
      $laCommande = DB::table('Prestations')
      ->join('Produits', 'produits_id', '=', 'Produits.id')
      ->join('Tarifs', 'prestations_id', '=', 'Prestations.id')
      ->where('Prestations.nom', '=', $data['prestations_id'])
      ->where('Prestations.produits_id', '=', $idProduit)
      ->get();
      ///////////////////////////////////////////////////////////////
      foreach ($laCommande as $laCommande) {
        $nomProduitI = $laCommande->nom;
        $produits_idI = $laCommande->produits_id;
        $tarifI = $laCommande->tarif;
        $prestaidI = $laCommande->prestations_id;
      }
      ///////////////////////////////////////////////////////////////
      if(is_null($data['commentaire'])){
        $update = [
                    'commentaire'     => 'Aucun commentaire',
                    'dateDepot'   => $data['dateDepot'],
                    'pretPourRecuperation' => '0',
                    'prestations_id'     => $prestaidI,
                    'clients_id'    => $userId,
                    'employes_id'    => '1',
                ];
      }
      else {
        $update = [
                    'commentaire'     => $data['commentaire'],
                    'dateDepot'   => $data['dateDepot'],
                    'pretPourRecuperation' => '0',
                    'prestations_id'     => $prestaidI,
                    'clients_id'    => $userId,
                    'employes_id'    => '1',
                ];
      }
      // return $update;
        DB::table('Commandes')->insert($update);
        return redirect('/dashboard');
      ///////////////////////////////////////////////////////////////
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
      return $data;
      return view('appviews/confirmationReservation', compact('commande','client','produit','prestation'))
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
      //  $prestationsProduits = DB::table('Prestations')
      //   ->join('Produits', 'produits_id', '=', 'Produits.id')
      //   ->join('Tarifs', 'prestations_id', '=', 'Prestations.id')
      //   ->selectRaw('DISTINCT(Produits.nom) as nomProduit, Prestations.nom as nomPrestation, Tarifs.tarif as Tarif')
      //   ->orderBy('Produits.nom','desc')
      //   ->lists('nomProduit');

      $prestationsProduits = DB::table('Prestations')
      ->join('Produits', 'produits_id', '=', 'Produits.id')
      ->join('Tarifs', 'prestations_id', '=', 'Prestations.id')
      ->selectRaw(('DISTINCT(Produits.nom)'))->groupBy('Produits.nom')->pluck('Produits.nom','Produits.nom');

      $prestationsPrestations = DB::table('Prestations')
       ->join('Produits', 'produits_id', '=', 'Produits.id')
       ->join('Tarifs', 'prestations_id', '=', 'Prestations.id')
       ->selectRaw(('DISTINCT(Prestations.nom)'))->groupBy('Produits.nom')->pluck('Prestations.nom','Prestations.nom');

      $prestationsTarifs = DB::table('Prestations')
        ->join('Produits', 'produits_id', '=', 'Produits.id')
        ->join('Tarifs', 'prestations_id', '=', 'Prestations.id')
        ->selectRaw(('DISTINCT(Tarifs.tarif)'))->groupBy('Tarifs.tarif')->lists('Tarifs.tarif');
      // return $prestationsTarifs;

       return view('appviews/reservation', compact('userId','produits','prestationsProduits','prestationsProduits','prestationsTarifs'))
                       ->with('userId', $userId)
                       ->with('prestationsProduits', $prestationsProduits)
                       ->with('prestationsPrestations', $prestationsPrestations)
                       ->with('prestationsTarifs', $prestationsTarifs)
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
                //-----------------------------------------------------------------------------------------//
        $GraphPrestaN = DB::table('Commandes')->where('Commandes.clients_id', $userId)
                ->join('Clients', 'clients_id', '=', 'Clients.id')
                ->join('Prestations', 'prestations_id', '=', 'Prestations.id')
                ->join('Produits', 'produits_id', '=', 'Produits.id')
                // ->join('Tarifs', 'Tarifs.Prestations_id', '=', 'Prestations.id')
                ->selectRaw('Prestations.nom, COUNT(*) as count')
                ->groupBy('Prestations.nom')
                ->orderBy('count', 'desc')
                ->lists('count');
        //-----------------------------------------------------------------------------------------//
        $GraphPrestaT = DB::table('Commandes')->where('Commandes.clients_id', $userId)
                ->join('Clients', 'clients_id', '=', 'Clients.id')
                ->join('Prestations', 'prestations_id', '=', 'Prestations.id')
                ->join('Produits', 'produits_id', '=', 'Produits.id')
                // ->join('Tarifs', 'Tarifs.Prestations_id', '=', 'Prestations.id')
                ->selectRaw('Prestations.nom, COUNT(*) as count')
                ->groupBy('Prestations.nom')
                ->orderBy('count', 'desc')
                ->lists('Prestations.nom');
        //-----------------------------------------------------------------------------------------//
        // Requête séparé en deux parties pour les statistiques client avec ChartsJS
        return view('appviews/dashboard', compact('GraphCommandesT','GraphCommandesN','Commandes','GraphPrestaN','GraphPrestaT'))
                       ->with('Commandes', $Commandes)
                       ->with('GraphPrestaN', $GraphPrestaN)
                       ->with('GraphPrestaT', $GraphPrestaT)
                       ->with('GraphCommandesT', $GraphCommandesT)
                       ->with('GraphCommandesN', $GraphCommandesN);
                       // ->with('calendar', $calendar);
        //------------------------------------------------------------------------------------------//
    }
}
