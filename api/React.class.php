<?php

namespace Aggregator\Support;
   
    require_once('ThingHTTP.class.php');   // La classe React utilise les objets thingHTTP
	use Aggregator\Support\ThingHTTP;
	use Aggregator\Support\ThingHTTPException;

class React
{
   /**  Constructeur
    *   @param $bdd une connexion à la base de donnée
	*   @id    id du react à construire
	*/
    function __construct($bdd, $id) {
		$this->bdd = $bdd;
		$sql = "SELECT * FROM reacts WHERE id = ".$id;
		$stmt = $bdd->query($sql);
		$this->property = $stmt->fetchObject();
	}

	/** Methode to perform the react
     *  @return boolean
     */	
    public function perform() {
		if($this->property) {
			switch ($this->property->react_type) {
				case 'numeric' :
					$retour = $this->action($this->numeric());
					break;
				case 'nodata' :
					$retour = $this->action($this->nodata());
					break;
			}
			return $retour;
		} else{
			return false;
		}	
	}
	
	/** 
	 * Methode pour tester les conditions  numeriques
	 * @return boolean
	 */
	private function numeric(){
		// lecture de la dernière valeur du champ n° field_number du canal channel_id
		$sql = "SELECT field" . $this->property->field_number ." as value FROM feeds WHERE id_channel = ". $this->property->channel_id ." ORDER BY `feeds`.`date` desc limit 1";
		$stmt = $this->bdd->query($sql);
		$this->property->action_value = $stmt->fetchObject()->value;

		// calcul de la comparaison
		$condition = $this->comparaison( $this->property->action_value , $this->property->condition , $this->property->condition_value );
		
		return $condition;	 
	}
	
	/** 
	 * Methode pour tester l'absence d'update d'un canal id channel_id depuis condition_value minutes
	 * @return boolean true si la condition remplie
	 */	
	
	private function nodata(){
		
		$sql = "SELECT count(*) as nb FROM `channels` where `id` = {$this->property->channel_id} and `last_write_at` > DATE_SUB(NOW(), INTERVAL {$this->property->condition_value} MINUTE)";
	    $stmt = $this->bdd->query($sql);
	    $nb =  $stmt->fetchObject()->nb;
		$this->property->action_value = 0;
		if ($nb == 0) 
			return true;
		else
			return false;	
    }
	
	/** 
	 * Methode pour exécuter l'action associée
	 */	
	private function action($condition){
		// Exécution de l'action associé
		if ($condition && ( !$this->property->last_result || $this->property->run_action_every_time)){
					
			try{
				$http = new ThingHTTP($this->bdd, $this->property->actionable_id);
				$http->send_request();
			}
			catch(ThingHTTPException $e) {
				echo $e->getMessage();
				return false;
			}
			// Sauvegarde de l'heure d'éxécution & de la valeur d'action
			$sql = "UPDATE `reacts` SET `last_run_at`= now(), `action_value` = {$this->property->action_value} WHERE `id` = {$this->property->id}";
			$stmt = $this->bdd->query($sql);
					
		}
			
		// Sauvegarde de la propriété last_result  dans la base
		if ($condition)  { $last_result = 1; } else  $last_result = 0;
		$sql = "UPDATE `reacts` SET `last_result` = '{$last_result}' WHERE `reacts`.`id` = {$this->property->id}";
		$stmt = $this->bdd->query($sql);
		return true;				
	}
	
	/** 
	 * Methode to get the name
	 */
	 
	public function getName(){
		return $this->property->name;
	}
	
	/** Methode to show property
     *  @return nothing
     */	 
	public function showProperty(){
		
		echo '<pre>';
		var_dump($this->property);
		echo '</pre>';
	}

	/** Methode pour calculer la comparaison numérique
     *  @return booleen
     */	

    private function comparaison($arg1, $op, $arg2)
    {
        $retour = false;
		switch ($op) {
			case "lt":
				$retour = $arg1 < $arg2;
				break;
			case "lte":
				$retour = $arg1 <= $arg2;
				break;
			case "gt":
				$retour = $arg1 > $arg2;
				break;
			case "gte":
				$retour = $arg1 >= $arg2;
				break;
			case "neq":
				$retour = $arg1 !== $arg2;
				break;
			case "eq":
				$retour = $arg1 === $arg2;
				break;				
		}
        return $retour;
    }

   /** 
	*  Methode pour calculer la distance entre deux points en m 
	*	à partir de leurs coordonnés GPS
    *   @param	$lat1 $lng1 les coordonnées du point 1
    *   @param	$lat2 $lng2 les coordonnées du point 2
    *   @return distance en m   	
	*/
	private  function get_distance_m($lat1, $lng1, $lat2, $lng2)
	{
        $earth_radius = 6378137;   // Terre = sphère de 6378km de rayon
        $rlo1 = deg2rad($lng1);
        $rla1 = deg2rad($lat1);
        $rlo2 = deg2rad($lng2);
        $rla2 = deg2rad($lat2);
        $dlo = ($rlo2 - $rlo1) / 2;
        $dla = ($rla2 - $rla1) / 2;
        $a = (sin($dla) * sin($dla)) + cos($rla1) * cos($rla2) * (sin($dlo) * sin($dlo));
        $d = 2 * atan2(sqrt($a), sqrt(1 - $a));
        return ($earth_radius * $d);
    }

	// déclaration des propriétés
    private $property;
    private $bdd;	
}