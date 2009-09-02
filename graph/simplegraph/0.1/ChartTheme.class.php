<?php
/**
 * This class helps drawing a chart
 *
 * @Component
 */
 
class ChartTheme {
 		/**
         * Chart title.<br />
         *
         * @Property
         * @Compulsory
         * @var string
         */
        public $title;
		
		/**
         * Chart complete render height.<br />
         * Height of your graph in full size mode.<br />
         *
         * @Property
         * @Compulsory
         * @var string
         */
        public $completeHeight;
		
		/**
         * Chart complete render width.<br />
         * Width of your graph in full size mode.<br />
         *
         * @Property
         * @Compulsory
         * @var string
         */
        public $completeWidth;
        
        /**
         * Chart background color 1 for gradient.<br />
         * For instance: CCCCFF<br />
         *
         * @Property
		 * @Compulsory
		 * @var string
         */
        public $backgroundColorOne;
        
        /**
         * Chart background color 2 for gradient.<br />
         * For instance: FFFF88<br />
         *
         * @Property
		 * @Compulsory
		 * @var string
         */
        public $backgroundColorTwo;
        
        /**
         * It works only for Line or Column Charts.<br />
         * Angle of color gradient.<br />
         * In degrees, initial value: 0<br />
         *
         * @Property
		 * @Compulsory
		 * @OneOf("0", "90")
		 * @OneOfText("From up to bottom", "From left to right")
		 * @var string
         */
        public $backgroundAngle;
        
        /**
         * It works only for Line or Column Charts.<br />
         * Anti-aliasing is a method of making lines more smooth, but server will use lots of resources.<br />
         * Do you want to use it ?
         * Initial value: yes<br />
         *
         * @Property
         * @Compulsory
         * @var bool
         */
        public $antiAliasing;
        
        /**
         * It works only for Column Charts.<br />
         * Do you want to smooth columns ?<br />
         * Initial value: yes<br />
         *
         * @Property
         * @Compulsory
         * @var bool
         */
        public $columnSmooth;
        
        /**
         * Chart decimal values accuracy.<br />
         * How many decimals do you need ?<br />
         * Initial value: 2<br />
         *
         * @Property
         * @Compulsory
         * @var string
         */
        public $accuracy;
        
        /**
         * It works only for Line or Column Charts.<br />
         * Y axis values accuracy.<br />
         * How many decimals do you need ?<br />
         * Initial value: 2<br />
         *
         * @Property
         * @Compulsory
         * @var string
         */
        public $accuracyY;
        
        /**
         * It works only for Pie Charts.<br />
         * Chart horizontal position.<br />
         * In purcent, chart center position compared to left side of picture.<br />
         * Set 0.5 to have your chart in picture center.<br />
         * Initial value: 0.4<br />
         *
         * @Property
         * @Compulsory
         * @var string
         */
        public $posChartX;
        
        /**
         * It works only for Pie Charts.<br />
         * Chart vertical position
         * In purcent, chart center position compared to up side of picture.<br />
         * Set 0.5 to have your chart in picture center.<br />
         * Initial value: 0.58<br />
         *
         * @Property
         * @Compulsory
         * @var string
         */
        public $posChartY;
        
        /**
         * It works only for Pie Charts.<br />
         * Chart horizontal size.<br />
         * In purcent, chart size compared to picture.<br />
         * Initial value: 0.65<br />
         *
         * @Property
         * @Compulsory
         * @var string
         */
        public $sizeChartX;
        
        /**
         * It works only for Pie Charts.<br />
         * Chart vertical size.<br />
         * In purcent, chart size compared to picture.<br />
         * Initial value: 0.65
         *
         * @Property
         * @Compulsory
         * @var string
         */
        public $sizeChartY;
        
        /**
         * It works only for Line Charts.<br />
         * You can see Line graph or Area graph.<br />
         * Do you want to hide line and see area ?
         * Initial value: yes<br />
         *
         * @Property
         * @Compulsory
         * @var bool
         */
        public $lineHideLine;
        
        /**
         * It works only for Line and Column Charts.<br />
         * Graph area color.<br />
         * Color of area under the line graph.<br />
         * For instance: 88FF88;
         *
         * @Property
         * @Compulsory
         * @var string
         */
        public $graphAreaColor;
        
        /**
         * It works only for Line Charts.<br />
         * Graph area transparency, in purcent<br />
         * Set 100 to get transparency and 0 to get opacity.<br />
         * Initial value: 75<br />
         *
         * @Property
		 * @Compulsory
		 * @var string
         */
        public $graphAreaTransparency;
        
        /**
         * Interval between displayed labels.<br />
         * Set to 1 to see all labels.<br />
         * Initial value: 1<br />
         *
         * @Property
         * @Compulsory
         * @var string
         */
        public $labelInterval;
        
        /**
         * Bar shadow size, in pixels.<br />
         * Set 0 to 2D Chart.<br />
         * Initial value: 3<br />
         *
         * @Property
         * @Compulsory
         * @var string
         */
        public $columnShadowSize;
        
        /**
         * It works only for Column Charts.<br />
         * Position of bar shadow.<br />
         *
         * @Property
		 * @Compulsory
		 * @OneOf("1", "2", "3", "4")
		 * @OneOfText("Left top", "Left bottom", "Right top", "Right bottom")
		 * @var string
         */
        public $columnShadowPosition;
        
        /**
         * It works only for Column Charts.<br />
         * Column shadow color.
         * Initial value: DDDDDD<br />
         *
         * @Property
         * @Compulsory
         * @var string
         */
        public $columnShadowColor;
        
        /**
         * It works only for Column Charts.<br />
         * Column shadow transparency.
         * Initial value: 10<br />
         *
         * @Property
         * @Compulsory
         * @var string
         */
        public $columnShadowTransparency;
        
        /**
         * Left space between axes and line (or columns), in purcent.<br />
         * Initial value: 5<br />
         *
         * @Property
         * @Compulsory
         * @var string
         */
        public $spaceAxesLeft;
        
        /**
         * Right space between axes and line (or columns), in purcent.<br />
         * Initial value: 5<br />
         *
         * @Property
         * @Compulsory
         * @var string
         */
        public $spaceAxesRight;
        
        /**
         * Top space between axes and line (or columns), in purcent.<br />
         * Initial value: 0<br />
         *
         * @Property
         * @Compulsory
         * @var string
         */
        public $spaceAxesTop;
        
        /**
         * Bottom space between axes and line (or columns), in purcent.<br />
         * Initial value: 0<br />
         *
         * @Property
         * @Compulsory
         * @var string
         */
        public $spaceAxesBottom;
        
        /**
         * Only for pie charts.<br />
         * 3D effect thickness.<br />
         * Number of pixels to set graph thickness.<br />
         * Initial value: 10<br />
         *
         * @Property
         * @Compulsory
         * @var string
         */
        public $thickness;
        
        /**
         * Chart legend horizontal position.<br />
         * In purcent, chart center position compared to left side of picture.<br />
         * Set 0 to have the legend in left part of your graph.<br />
         * Set 1 to have the legend in right part of your graph.<br />
         * Initial value: 1.35<br />
         *
         * @Property
         * @Compulsory
         * @var string
         */
        public $posLegendX;
        
        /**
         * Chart legend vertical position.<br />
         * In purcent, chart legend position compared to upper side of picture.<br />
         * Set 0 to have the legend in upper part of your graph.<br />
         * Set 1 to have the legend in lower part of your graph.<br />
         * Initial value: 0.25<br />
         * @Property
         * @Compulsory
         * @var string
         */
        public $posLegendY;
        
        /**
         * Chart title font size.<br />
         * Initial value: 14<br />
         *
         * @Property
         * @Compulsory
         * @var string
         */
        public $titleFontSize;
        
        /**
         * Chart title horizontal position.<br />
         * In pixels, initial value: 0<br />
         *
         * @Property
         * @Compulsory
         * @var string
         */
        public $posTitleX;
        
        /**
         * Chart title vertical position.<br />
         * In pixels, initial value: -40<br />
         *
         * @Property
         * @Compulsory
         * @var string
         */
        public $posTitleY;
        
        /**
         * Title background color.<br />
         * Initial value: FFFFFF<br />
         *
         * @Property
		 * @Compulsory
		 * @var string
         */
        public $backgroundTitleColor;
        
        /**
         * Title background color transparency, in purcent.<br />
         * Set 100 to get transparency and 0 to get opacity.<br />
         * Initial value: 70<br />
         *
         * @Property
		 * @Compulsory
		 * @var string
         */
        public $titleBackgroundTransparency;
        
        /**
         * Title frame color.<br />
         * Initial value: 000000<br />
         *
         * @Property
		 * @Compulsory
		 * @var string
         */
        public $titleFrameColor;
        
        /**
         * Horizontal title frame interval.<br />
         * It's the space between left or right sides frame and title.
         * In pixels, initial value: 5<br />
         *
         * @Property
         * @Compulsory
         * @var string
         */
        public $paddingTitleX;
        
        /**
         * Vertial title frame interval.<br />
         * It's the space between upper or lower sides frame and title.
         * In pixels, initial value: 2<br />
         *
         * @Property
         * @Compulsory
         * @var string
         */
        public $paddingTitleY;
        
        /**
         * Picture shadow size.<br />
         * In pixels, it's the shadow size behind your graph.
         * Initial value: 4<br />
         *
         * @Property
         * @Compulsory
         * @var string
         */
        public $shadow;
        
        
		public function __construct() 
		{ 
			$this->pieTitle = "myChart";
			$this->pieTitleBackgroundColor = "FFFFFF";
			$this->pieTitleBackgroundTransparency = 70;
			$this->pieTitleFrameColor = "000000";
			$this->piePaddingTitleX = 5;
			$this->piePaddingTitleY = 2;
			$this->piePosTitleY = -40; 
			$this->piePosTitleX = 0;
			
			$this->backgroundColorOne = "CCCCFF";
			$this->backgroundColorTwo = "FFFF88";
			$this->backgroundAngle = 0;
			$this->completeHeight = 300;
			$this->completeWidth = 300;
			$this->accuracy = 2; 
			$this->posLegendY = .25; 
			$this->posLegendX = 1.35; 
			$this->posChartY = .58; 
			$this->posChartX = .4;
			$this->sizeChartY = .65; 
			$this->sizeChartX = .65;
			$this->titleFontSize = 14;
			$this->thickness = 10;
			$this->shadow = 4;
			$this->antiAliasing = TRUE;
			$this->accuracyY = 2;
			$this->spaceAxesLeft = 5;
			$this->spaceAxesRight = 5;
			$this->spaceAxesTop = 0;
			$this->spaceAxesBottom = 0;
			$this->lineHideLine = TRUE;
			$this->graphAreaColor = "88FF88";
			$this->graphAreaTransparency = 75;
			$this->labelInterval = 0;
			$this->columnShadowSize = 3;
			$this->columnShadowPosition = 1;
			$this->columnShadowColor = "DDDDDD";
			$this->columnShadowTransparency = 10;
			$this->columnSmooth = TRUE;
			
		}
		
		public function colorHtmlToDecimal($color) {
			$primaryOne = substr($color,0,2);
			$primaryTwo = substr($color,2,2);
			$primaryThree = substr($color,4,2);
			
			$primaryOne = hexdec($primaryOne);
			$primaryTwo = hexdec($primaryTwo);
			$primaryThree = hexdec($primaryThree);
			
			return array($primaryOne, $primaryTwo, $primaryThree);
		}
        
}

?>