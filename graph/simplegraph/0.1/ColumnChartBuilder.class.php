<?php
/**
 * This class helps drawing a column chart
 *
 * @Component
 */

class ColumnChartBuilder {
        
	/**
	 * Theme of your column chart
	 * 
	 * @Property
	 * @compulsory
	 * @var ChartTheme
	 */
	public $theme;
	
	/**
	 * DataSet of your column chart
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
		
		// On inclue le fichier qui nous permettra de dessiner des histogrammes
		require_once(dirname(__FILE__).'/../../artichow/1.1.0/BarPlot.class.php');

		// Il est toujours nécessaire de donner une taille à la création de votre graphique.
		// Ici, le graphique mesurera 400 x 400 pixels.
		$graph = new Graph($this->theme->completeWidth, $this->theme->completeHeight);
		
		// L'anti-aliasing permet d'afficher des courbes plus naturelles,
		// mais cette option consomme beaucoup de ressources sur le serveur.
		$graph->setAntiAliasing($this->theme->antiAliasing);
		
		// On créé l'histogramme
		$plot = new BarPlot($this->dataSet->values);
		
		// Ajoute un dégradé de fond
		$colorOne = $this->theme->colorHtmlToDecimal($this->theme->backgroundColorOne);
		$colorTwo = $this->theme->colorHtmlToDecimal($this->theme->backgroundColorTwo);
		$plot->setBackgroundGradient(
			new LinearGradient(
				// On donne deux couleurs pour le dégradé
				new Color($colorOne[0], $colorOne[1], $colorOne[2], 0),
				new Color($colorTwo[0], $colorTwo[1], $colorTwo[2], 0),
				// On spécifie l'angle du dégradé linéaire
				// 0° pour aller du haut vers le bas
				$this->theme->backgroundAngle
			)
		);
		
		for ($i=0;$i<count($this->dataSet->legend);$i++) {
			$xAxisLabel[$i]= utf8_decode($this->dataSet->legend[$i]);
		}
  		$plot->xAxis->setLabelText($xAxisLabel);
		
		// Ajoute une couleur de fond aux barres
		$colorArea = $this->theme->colorHtmlToDecimal($this->theme->graphAreaColor);
		$plot->setBarColor(new Color($colorArea[0], $colorArea[1], $colorArea[2]));
		
		// On ajoute 5 % d'espace à gauche et à droite de la courbe.
		// On ne change pas l'espace du haut et du bas de la courbe.
		$plot->setSpace(
			$this->theme->spaceAxesLeft, /* Gauche */
			$this->theme->spaceAxesRight, /* Droite */
			$this->theme->spaceAxesTop, /* Haut */
			$this->theme->spaceAxesBottom /* Bas */
		);
		
		// On choisit une ombre de 3 pixels
		$plot->barShadow->setSize($this->theme->columnShadowSize);
		// Où placer l'ombre ?
		// Si vous utilisez Artichow pour PHP 4 & 5, transformez Shadow::RIGHT_TOP en SHADOW_RIGHT_TOP
		$plot->barShadow->setPosition($this->theme->columnShadowPosition);
		// Couleur de l'ombre
		$colorShadow = $this->theme->colorHtmlToDecimal($this->theme->columnShadowColor);
		$plot->barShadow->setColor(new Color($colorShadow[0], $colorShadow[1], $colorShadow[2], $this->theme->columnShadowTransparency));
		// Lisser les extrémités de l'ombre ?
		$plot->barShadow->smooth($this->theme->columnSmooth);
		
		$graph->add($plot);
		$graph->draw();
		
	}
		
}

?>