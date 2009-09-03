<?php
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
	 * @compulsory
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
	
	public function setTheme(ChartTheme $theme) {
		$this->theme = $theme;
	}
        
	public function setDataSet(ChartDataSerie $dataSet) {
		$this->dataSet = $dataSet; 
 	}
 
	public function draw() {  
		
		// La classe Pie est celle utilisée pour dessiner les camemberts.
		require_once(dirname(__FILE__).'/../../artichow/1.1.0/Pie.class.php');
		$graph = new Graph($this->theme->completeWidth, $this->theme->completeHeight);
		
		// ... ajout d'une ombre portée...
		// Si vous utilisez Artichow pour PHP 4 & 5,
		// utilisez SHADOW_RIGHT_BOTTOM à la place de Shadow::RIGHT_BOTTOM
		$graph->shadow->setPosition(Shadow::RIGHT_BOTTOM);
		$graph->shadow->setSize($this->theme->shadow);
		// ... et d'un joli fond.
		$colorOne = $this->theme->colorHtmlToDecimal($this->theme->backgroundColorOne);
		$colorTwo = $this->theme->colorHtmlToDecimal($this->theme->backgroundColorTwo);
		$graph->setBackgroundGradient(
			new LinearGradient(
				new Color($colorOne[0], $colorOne[1], $colorOne[2], 0),
				new Color($colorTwo[0], $colorTwo[1], $colorTwo[2], 0),
				0
			)
		);
		
		// Tableau des valeurs
		for ($i=0;$i<count($this->dataSet->values);$i++) {
			$values[utf8_decode($this->dataSet->legend[$i])] = $this->dataSet->values[$i];
		}
			
		// Seules les valeurs numériques sont utilisées pour l'instant,
		// avec le thème de couleur par défaut.
		$pie = new Pie(array_values($values));
		
		// Précision des valeurs.
		$pie->setLabelPrecision($this->theme->accuracy);
		
		// Ajout de la légende
		$pie->setLegend(array_keys($values));
		
		// Repositionnement de la légende
		$pie->legend->setPosition($this->theme->posLegendX, $this->theme->posLegendY);
		
		// Décalage du camembert sur la gauche et vers le bas
		$pie->setCenter($this->theme->posChartX, $this->theme->posChartY);
		
		// Redimensionnement du camembert, taille relative à l'objet Graph le contenant.
		$pie->setSize($this->theme->sizeChartX, $this->theme->sizeChartX);
		
		// Ajout d'un petit effet 3D; la valeur est donnée en pixel.
		$pie->set3D($this->theme->thickness);
		
		// Ajout d'un titre...
		$pie->title->set(utf8_decode($this->theme->pieTitle));
		
		// ... repositionnement...
		$pie->title->move($this->theme->piePosTitleX, $this->theme->piePosTitleY);
		
		// ... et embellissement.
		$colorBgTitle = $this->theme->colorHtmlToDecimal($this->theme->pieTitleBackgroundColor);
		$colorFrameTitle = $this->theme->colorHtmlToDecimal($this->theme->pieTitleFrameColor);
		$pie->title->setFont(new TuffyBold($this->theme->pieTitleFontSize));
		$pie->title->setBackgroundColor(new Color($colorBgTitle[0], $colorBgTitle[1], $colorBgTitle[2], $this->theme->pieTitleBackgroundTransparency));
		$pie->title->setPadding($this->theme->piePaddingTitleX, $this->theme->piePaddingTitleX, $this->theme->piePaddingTitleY, $this->theme->piePaddingTitleY);
		$pie->title->border->setColor(new Color($colorFrameTitle[0], $colorFrameTitle[1], $colorFrameTitle[2], 0));
		
		$graph->add($pie);
		$graph->draw();
		
		
		
	}
		
}


?>