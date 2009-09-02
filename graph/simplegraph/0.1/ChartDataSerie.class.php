<?php
/**
 * This class helps drawing a chart
 *
 * @Component
 */
 
class ChartDataSerie {
 		/**
         * Chart legend
         *
         * @Property
         * @Compulsory
         * @var array<string>
         */
        public $legend;
        
        /**
         * Chart values
         *
         * @Property
		 * @Compulsory
		 * @var array<string>
         */
        public $values;
        
        /**
         * Chart color
         *
         * @Property
         * @Compulsory
         * @var array<string>
         */
        public $color;
        
        public function countLegend() {
        	return count($this->legend);
        }
        
		public function countValues() {
        	return count($this->values);
        }
		public function countColor() {
        	return count($this->color);
        }

}

?>