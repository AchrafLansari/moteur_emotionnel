<?php

namespace Moteur\UtilisateurBundle\Geolocalisation;

use Doctrine\ORM\Query\Expr\Math;
class GeoIp{

	private $ipadress;
	public $pays = "";
	public $departement = "";
	public $ville = "";
	
	public function __construct(){
		if (getenv('HTTP_CLIENT_IP'))
			$this->ipaddress = getenv('HTTP_CLIENT_IP');
		else if(getenv('HTTP_X_FORWARDED_FOR'))
			$this->ipaddress = getenv('HTTP_X_FORWARDED_FOR');
		else if(getenv('HTTP_X_FORWARDED'))
			$this->ipaddress = getenv('HTTP_X_FORWARDED');
		else if(getenv('HTTP_FORWARDED_FOR'))
			$this->ipaddress = getenv('HTTP_FORWARDED_FOR');
		else if(getenv('HTTP_FORWARDED'))
			$this->ipaddress = getenv('HTTP_FORWARDED');
		else if(getenv('REMOTE_ADDR'))
			$this->ipaddress = getenv('REMOTE_ADDR');
		else
			$this->ipaddress = 'UNKNOWN';
	}
	
	public function setIpadress($ip){
		$this->ipadress = $ip;
	}
	
	public function getIpadress(){
		return $this->ipadress;
	}
	
	public function geoCheckIP()
	{
		if(!filter_var($this->ipadress, FILTER_VALIDATE_IP))
		{
			throw new InvalidArgumentException("IP is not valid");
		}
	
		$response=@file_get_contents('http://www.netip.de/search?query='.$this->ipadress);
		if (empty($response))
		{
			throw new InvalidArgumentException("Error contacting Geo-IP-Server");
		}
	
		$patterns=array();
		//$patterns["domain"] = '#Domain: (.*?)&nbsp;#i';
		$patterns["country"] = '#Country: (.*?)&nbsp;#i';
		$patterns["state"] = '#State/Region: (.*?)<br#i';
		$patterns["town"] = '#City: (.*?)<br#i';
		
		$this->pays = preg_match($patterns["country"],$response,$value) && !empty($value[1]) && strlen($value[1]) > 3 ?
						$value[1] :
						null;
		$this->departement = preg_match($patterns["state"],$response,$value) && !empty($value[1]) ?
						$value[1] :
						null;
		$this->ville = preg_match($patterns["town"],$response,$value) && !empty($value[1]) ?
						$value[1] :
						null;
		
		//On renvoi vrai seulement si on a réussit à correctement géolocaliser l'utilisateur
		if($this->pays!=null && $this->departement!=null && $this->ville!=null)
			return true;
		return false;
	}
}