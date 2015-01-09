<?php

namespace Moteur\ProduitBundle\Dictionnaire;

class IndexationMot{
	private $requete;
	public $indexRequete;
	private $listeMotsVides;
	private $cheminMotsVide = 'mots_vides.txt';
	private $cheminDictionnaireLemmes = 'dictionnaire_lemme_fr.csv';
	
	public function __construct($requete, $path){
		$this->requete = $requete;
		$this->cheminMotsVide = $path . $this->cheminMotsVide;
		$this->cheminDictionnaireLemmes = $path . $this->cheminDictionnaireLemmes;
		
		$this->setListeMotsVides();
		
		$separateurs = " \n\t\r,«;.»’'\"()!?+-";

		$this->indexRequete = $this->fractionner_chaine($this->requete, $separateurs);
		//$this->indexRequete = $this->supprimerPetitsSegments($this->indexRequete, 4);
		sort($this->indexRequete);
		$this->indexRequete = array_map("strtolower", $this->indexRequete);
		$this->indexRequete = $this->supprimerMotsVide($this->indexRequete);
	}
	
	/**
	 * DÃ©finit une liste de mots vides
	 * @param unknown $file le chemin du fichier contenant les mots vides
	 */
	private function setListeMotsVides(){
		$this->listeMotsVides = array_unique(explode("\r\n", file_get_contents($this->cheminMotsVide)));
	}
	
	/**
	 * Supprime tous les mots n'ayant pas de signification d'un tableau de mots
	 * 
	 * @param unknown $segments le tableau de mots Ã  traiter
	 * @return un tableau sans les Ã©lÃ©ments n'ayant pas de sens
	 */
	private function supprimerMotsVide($segments){
		$return = array();
		foreach ($segments as $segment){
			if(!in_array($segment, $this->listeMotsVides))
				$return[] = $segment;
		}
		return $return;
	}
	
	/**
	 * Supprime tous les mots trop petits d'un tableau de mots
	 * 
	 * @Param $segments le tableau de chaines de caractÃ¨res
	 * @Param $taille la taille minimale des chaines de caractÃ¨res
	 * @Return un array sans les mots trop petits
	 */
	private function supprimerPetitsSegments($segments, $taille){
		$return = array();
		foreach ($segments as $segment){
			if(strlen(utf8_decode($segment))>=$taille)
				$return[] = $segment;
		}
		return $return;
	}
	
	/**
	 * Transforme un texte en tableau de mots
	 * 
	 * @Param $string la chaine de caractères à  traiter
	 * @Return un tableau de chaine de caractères
	 */
	private function fractionner_chaine($string, $separateurs){
		$arrayString = [];
		$tok = strtok($string, $separateurs);
		while ($tok !== false){
			$arrayString[] = $tok;
			$tok = strtok($separateurs);
		}
		return $arrayString;
	}
}