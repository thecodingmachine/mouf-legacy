<?php
require_once 'UbuntuHack.php';

/**
 * This class helps drawing a Pie chart
 *
 * @Component
 */

class PieChartBuilder {
        
	/**
	 * Theme of your pie chart
	 * 
	 * @Property
	 * @Compulsory
	 * @var ChartTheme
	 */
	public $theme;
	
	/**
	 * DataSet of your pie chart
	 * 
	 * @Property
	 * @compulsory
	 * @var ChartDataSerie
	 */
	public $dataSet;
	
	/**
	 * The number of digits to print after the dot.
	 * Defaults to 0.
	 * 
	 * @Property
	 * @var int
	 */
	//public $labelPrecision = 0;
	
	/**
	 * The distance in pixels between the labels and the pie chart.
	 * Defaults to 0.
	 * Can be negative.
	 * Positive values go towards the center of the pie and negative towards the outside.
	 * 
	 * @Property
	 * @var int
	 */
	public $labelPosition;
	
	public function setTheme(ChartTheme $theme) {
		$this->theme = $theme;
	}
        
	public function setDataSet(ChartDataSerie $dataSet) {
		$this->dataSet = $dataSet; 
 	}
 
	public function draw() {  
		ArtichowUbuntuHack::hack();
		
		// La classe Pie est celle utilis�e pour dessiner les camemberts.
		require_once(dirname(__FILE__).'/../../artichow/1.1.0/Pie.class.php');
		$graph = $this->theme->getAwGraph();
		
		$colors = null;
		if (is_array($this->dataSet->color) && !empty($this->dataSet->color)) {
			$colors = array();
			foreach ($this->dataSet->color as $color) {
				$colorArr = $this->theme->colorHtmlToDecimal($color);
				$colors[] = new Color($colorArr[0], $colorArr[1], $colorArr[2], 0);
			}
		}

		/*$values = array();
		// Tableau des valeurs
		for ($i=0;$i<count($this->dataSet->values);$i++) {
			$values[utf8_decode($this->dataSet->legend[$i])] = $this->dataSet->values[$i];
		}*/
		$legend = array();
		// Tableau des valeurs
		for ($i=0;$i<count($this->dataSet->legend);$i++) {
			$legend[$i] = utf8_decode($this->dataSet->legend[$i]);
		}

		// Seules les valeurs numériques sont utilisées pour l'instant,
		// avec le thême de couleur par défaut.
		$pie = new awPie($this->dataSet->values, $colors);
		
		// Pr�cision des valeurs.
		$pie->setLabelPrecision($this->theme->accuracy);
				
		// Ajout de la l�gende
		$pie->setLegend($legend);

		// Hide the legend if necessary.
		if (!$this->theme->showLegend) {
			$pie->legend->hide();
		}
		
		// Repositionnement de la l�gende
		$pie->legend->setPosition($this->theme->posLegendX, $this->theme->posLegendY);
		
		// D�calage du camembert sur la gauche et vers le bas
		$pie->setCenter($this->theme->posChartX, $this->theme->posChartY);
		
		// Redimensionnement du camembert, taille relative � l'objet Graph le contenant.
		$pie->setSize($this->theme->sizeChartX, $this->theme->sizeChartY);
		
		// Ajout d'un petit effet 3D; la valeur est donn�e en pixel.
		$pie->set3D($this->theme->thickness);
		
		$pie->setLabelPosition($this->labelPosition);
		
		
		$graph->add($pie);
		$graph->draw();
		
		
		
	}
		
}


?>