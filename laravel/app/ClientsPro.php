<?php

namespace App;
use App\Prestations;
use App\Employes;
use App\Produits;
use App\Tarifs;
use App\Clients;
use Illuminate\Database\Eloquent\Model;

class ClientsPro extends Model
{
	protected $table = 'ClientsPro';
  protected $fillable = ['idClientsPro','raisonsociale','siret','remise'];
	// Un client à une ou plusieurs prestations.
	public function Prestations()
	{
	    return $this->hasMany('App\Prestations');
	}

}
