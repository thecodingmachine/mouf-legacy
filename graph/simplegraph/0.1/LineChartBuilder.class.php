<?php
/**
 * This class helps drawing a line chart
 *
 * @Component
 */

class LineChartBuilder {
        
	/**
	 * Theme of your line chart
	 * 
	 * @Property
	 * @compulsory
	 * @var ChartTheme
	 */
	public $theme;
	
	/**
	 * DataSet of your line chart
	 * 
	 * @Property
	 * @compulsory
	 * @var ChartDataSerie
	 */
	public $dataSet;
	
	public function setTheme(ChartTheme $theme) {
		$this->theme = $theme;
	}
        
	public function setDataSet(ChartDataSerie $dataSet) {
		$this->dataSet = $dataSet; 
 	}
 	
 	public function calculInterval($labelNumber, $intervalNumber) {
 		
 		if ($intervalNumber != 0){
 			$intervalSize = (int)$labelNumber / (int)$intervalNumber;
 		}
 		else{
 			$intervalSize = 1;
 		}
 		return $intervalSize;
 	}
 
	public function draw() {       

		// On inclue le fichier qui nous permettra de dessiner des courbes
		require_once(dirname(__FILE__).'/../../artichow/1.1.0/LinePlot.class.php');
	
		// Il est toujours n�cessaire de donner une taille � la cr�ation de votre graphique.
		// Ici, le graphique mesurera 400 x 400 pixels.
		$graph = new Graph($this->theme->completeWidth, $this->theme->completeHeight);
	   
		// L'anti-aliasing permet d'afficher des courbes plus naturelles,
		// mais cette option consomme beaucoup de ressources sur le serveur.
		$graph->setAntiAliasing($this->theme->antiAliasing);

		// On cr�� la courbe
		$plot = new LinePlot($this->dataSet->values);
		
		// Ajoute un d�grad� de fond
		$colorOne = $this->theme->colorHtmlToDecimal($this->theme->backgroundColorOne);
		$colorTwo = $this->theme->colorHtmlToDecimal($this->theme->backgroundColorTwo);
		$plot->setBackgroundGradient(
			new LinearGradient(
				// On donne deux couleurs pour le d�grad�
				new Color($colorOne[0], $colorOne[1], $colorOne[2], 0),
				new Color($colorTwo[0], $colorTwo[1], $colorTwo[2], 0),
				// On sp�cifie l'angle du d�grad� lin�aire
				// 0� pour aller du haut vers le bas
				$this->theme->backgroundAngle
			)
		);
		
		
  		// On cache la ligne qui relie les valeurs...
		$plot->hideLine($this->theme->lineHideLine);
		// ... Mais on sp�cifie une couleur de fond pour la ligne,
		// ce qui permet tout de m�me de mettre en valeur la courbe.
		// On donne une forte transparence � cette couleur,
		// cela permet de laisser transpara�tre la grille du graphique.
		$colorArea = $this->theme->colorHtmlToDecimal($this->theme->graphAreaColor);
		$plot->setFillColor(new Color($colorArea[0], $colorArea[1], $colorArea[2], $this->theme->graphAreaTransparency));
  		
  		
		// On change la pr�cision des �tiquettes de l'axe des ordonn�es
		// La pr�cision est d�sormais de 1 chiffre apr�s la virgule
		$plot->yAxis->setLabelPrecision($this->theme->accuracyY);
		
		// On ajoute 5 % d'espace � gauche et � droite de la courbe.
		// On ne change pas l'espace du haut et du bas de la courbe.
		$plot->setSpace(
			$this->theme->spaceAxesLeft, /* Gauche */
			$this->theme->spaceAxesRight, /* Droite */
			$this->theme->spaceAxesTop, /* Haut */
			$this->theme->spaceAxesBottom /* Bas */
		);
		
		for ($i=0;$i<count($this->dataSet->legend);$i++) {
			$xAxisLabel[$i]= utf8_decode($this->dataSet->legend[$i]);
		}
  		$plot->xAxis->setLabelText($xAxisLabel);
		
  		$plot->grid->setInterval(1, 1);
  		//$plot->xAxis->label->setInterval($this->theme->labelInterval);
  		$plot->xAxis->label->setInterval($this->calculInterval($this->dataSet->countValues(), $this->theme->labelInterval));
  		
		$graph->add($plot);
		$graph->draw();
		
	}
		
}

?>