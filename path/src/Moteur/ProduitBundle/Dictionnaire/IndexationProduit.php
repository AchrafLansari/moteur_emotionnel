<?php

namespace Moteur\ProduitBundle\Dictionnaire;

class IndexationProduit{
	private $titre = "";
	private $auteur = "";
	private $description = "";
	
	private $indexTitre = array();
	private $indexPoidsTitre = array();
	
	private $indexAuteur = array();
	private $indexPoidsAuteur = array();
	
	private $indexDescription = array();
	private $indexPoidsDescription = array();
	
	public $indexMotPoids = array();
	
	//la liste des mots vides
	private $listeMotsVides;
	
	//coefficient donnant plus de poids aux Ã©lÃ©ments de l'entete
	private $coefficientTitre = 5;
	private $coefficientAuteur = 10;
	private $coefficientDescription = 1;
	
	private $cheminMotsVide = 'mots_vides.txt';
	private $cheminDictionnaireLemmes = 'dictionnaire_lemme_fr.csv';
	
	public function __construct($titre, $auteur, $description, $path){
		$this->titre = $titre;
		$this->auteur = $auteur;
		$this->description = $description;
		$this->cheminMotsVide = $path . $this->cheminMotsVide;
		$this->cheminDictionnaireLemmes = $path . $this->cheminDictionnaireLemmes;
		
		$this->setListeMotsVides();
		
		$separateurs = " \n\t\r,«;.»’'\"()!?-";

		$this->indexTitre = $this->fractionner_chaine($this->titre, $separateurs);
		$this->indexTitre = $this->supprimerPetitsSegments($this->indexTitre, 4);
		sort($this->indexTitre);
		$this->indexTitre = $this->supprimerMotsVide($this->indexTitre);
		$this->indexTitre = array_map("strtolower", $this->indexTitre);
		$this->indexPoidsTitre = array_count_values($this->indexTitre);
		//$this->indexPoidsTitre = $this->lemmatisation($this->indexPoidsTitre);
		
		$this->indexAuteur = $this->fractionner_chaine($this->auteur, $separateurs);
		$this->indexAuteur = $this->supprimerPetitsSegments($this->indexAuteur, 4);
		sort($this->indexAuteur);
		$this->indexAuteur = $this->supprimerMotsVide($this->indexAuteur);
		$this->indexAuteur = array_map("strtolower", $this->indexAuteur);
		$this->indexPoidsAuteur = array_count_values($this->indexAuteur);
		//$this->indexPoidsAuteur = $this->lemmatisation($this->indexPoidsAuteur);
		
		$this->indexDescription = $this->fractionner_chaine($this->description, $separateurs);
		$this->indexDescription = $this->supprimerPetitsSegments($this->indexDescription, 4);
		sort($this->indexDescription);
		$this->indexDescription = $this->supprimerMotsVide($this->indexDescription);
		$this->indexDescription = array_map("strtolower", $this->indexDescription);
		$this->indexPoidsDescription = array_count_values($this->indexDescription);
		//$this->indexPoidsDescription = $this->lemmatisation($this->indexPoidsDescription);
		
		$this->indexMotPoids = $this->fusionner_index_poids();
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
		$return;
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
		$return;
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

	/**
	 * @return Ambigous <number, string>
	 */
	private function fusionner_index_poids(){
		
		$retour = array();
		
		foreach($this->indexPoidsAuteur as $mot => $poids){
			$retour[$mot] = $poids * $this->coefficientAuteur;
		}
		
		foreach($this->indexPoidsTitre as $mot => $poids){
   			if(isset($retour[$mot]))
   				$retour[$mot] += $poids * $this->coefficientTitre;
   			else{
   				$retour[$mot] = $poids * $this->coefficientTitre;
   			}
		}
		
		foreach($this->indexPoidsDescription as $mot => $poids){
			if(isset($retour[$mot]))
				$retour[$mot] += $poids * $this->coefficientDescription;
			else
				$retour[$mot] = $poids * $this->coefficientDescription;
		}
		
		return $retour;
	}
	
	/**
	 * Multiplie les valeurs d'un tableau clé valeur par un coefficient
	 * 
	 * @param unknown $occurences le tableau de terme => occurence
	 * @param unknown $coefficient le poids des termes
	 * @return array key => value
	 */
	private function occurrences2poids($occurences, $coefficient){
		foreach ($occurences as $terme => $frequence){
			if(is_numeric($frequence)){
				$occurences[$terme] = $frequence * $coefficient;
			}
		}
		return $occurences;
	}
	
	private function lemmatisation($index){
		$file = fopen($this->cheminDictionnaireLemmes, "r");
		
		$retour = array();
		foreach ($index as $mot => $occurence){
			$i = 0;
			while($i == 0 && $data = fgetcsv($file)){
				if(strcmp(iconv('utf-8', 'ascii//TRANSLIT', $mot), iconv('utf-8', 'ascii//TRANSLIT', $data[0])) < 0){
					$i++;
				}
				else if($mot == $data[0]){
					$i++;
					$retour[$data[1]] = $occurence;
				}	
			}
			if(!isset($retour[$data[1]]))
				$retour[$mot] = $occurence;
		}
		return $retour;
	}
}