<?php

namespace App;
use App\Prestations;
use App\Employes;
use App\Produits;
use App\Tarifs;
use App\Clients;
use Illuminate\Database\Eloquent\Model;

class Commandes extends Model
{
		protected $table = 'Commandes';
    // En gros ici on dit quel champs sont modifiables dans la BDD, genre ou on peut Insert
    protected $fillable = ['id','commentaire','dateCreation','PretPourRecuperation', 'prestation_id', 'clients_id','employes_id','pointsfidelité'];
    protected $dates = ['dateDepot','dateRecuperation'];
    // Ici c'est les relations entre les tables ou jointures
    // hasOne = a un , hasMany = en a plusieurs c'est pour faire les 1.1 1.0 etc comme en UML
    public function Prestations()
		{
	    return $this->hasOne('App\Prestations');
		}
		public function Clients()
		{
	    return $this->hasOne('App\Clients');
		}
		public function Employes()
		{
	    return $this->hasOne('App\Employes');
		}
}
