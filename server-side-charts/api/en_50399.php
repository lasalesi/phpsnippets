<?php   
 /* this is an API to generate charts on a php server
  *
  * use like this
  *
  * scatter_chart.php?x[0]=1,2,3,4,5&y[0]=1,2,3,4,5&legend[0]=Outside&title=Average temperature&xlabel=Hour&ylabel=Temperature
  *
  * Parameters:									Example
  *  x[n]: 		x-values for series n			x[0]=0,2,4,6,8
  *  y[n]: 		y-values for series n			y[0]=1,1,5,3,7
  *  legend[n]: legend for series n				legend[0]=My values
  *  title:		chart title						title=Cool chart
  *  xlabel:	label of x axis					xlabel=Time
  *  ylabel:	label of y axis					ylabel=Fun factor
  *  scale:		manual scaling					scale=0,100,0,100
  *  color[n]:	line color for series n (rgb)	color[0]=0,50,250
  */

 /* load pChart library inclusions */
 require_once("../pChart/pData.php");
 require_once("../pChart/pDraw.php");
 // require_once("../pChart/class/pImage.class.php");
 require_once("../pChart/pScatter.php");
 
 /* constants */
 $width = 700;
 $height = 450;
 
 /* ********************      Read parameters      ****************** */
 if (isset($_GET['x'])) {
	$x_str_array = $_GET['x'];
 } else {
	$x_str_array = array(0=>"1,2,3,4,5");
 }
 if (isset($_GET['y'])) {
	$y_str_array = $_GET['y'];
 } else {
	$y_str_array = array(0=>"1,2,3,4,5");
 }
 if (isset($_GET['legend'])) {
	$legend_str_array = $_GET['legend'];
 } else {
	$legend_str_array = array(0=>"Sample data");
 }
 if (isset($_GET['title'])) {
	$title = $_GET['title'];
 } else  {
	$title = "My awesome chart";
 }
 if (isset($_GET['xaxislabel'])) {
	$xaxislabel = $_GET['xaxislabel'];
 }
 if (isset($_GET['xlabel'])) {
	$xaxislabel = $_GET['xlabel'];
 }
 if (isset($_GET['yaxislabel'])) {
	$yaxislabel = $_GET['yaxislabel'];
 }
 if (isset($_GET['ylabel'])) {
	$yaxislabel = $_GET['ylabel'];
 }
 if (isset($_GET['scale'])) {
	 $scale_str = $_GET['scale'];
	 $scale_values = explode(',', $scale_str);
	 if (count($scale_values) == 4) {
		$scale_min_x = floatval($scale_values[0]);
		$scale_max_x = floatval($scale_values[1]);
		$scale_min_y = floatval($scale_values[2]);
		$scale_max_y = floatval($scale_values[3]);		
	} else {
		$scale_min_x = 0;
		$scale_max_x = floatval($scale_values[0]);
		$scale_min_y = 0;
		$scale_max_y = floatval($scale_values[0]);
	}
 } else {
 		$scale_min_x = 0;
		$scale_max_x = 100;
		$scale_min_y = 0;
		$scale_max_y = 100;
 }
 if (isset($_GET['color'])) {
	 $color_array = $_GET['color'];
 }
 
 /* ************** Create and populate the pData object ************* */
 $myData = new pData();  

 // Create the X axis and the bound series 
 for ($i = 0; $i < count($x_str_array); $i++) {
	$myData->addPoints(explode(",", $x_str_array[$i]), "X" . $i);
 }
 if (isset($xaxislabel)) {
	$myData->setAxisName(0,$xaxislabel);
 }
 $myData->setAxisXY(0,AXIS_X);
 $myData->setAxisPosition(0,AXIS_POSITION_BOTTOM);

 // Create the Y axis and the bound series 
 for ($i = 0; $i < count($y_str_array); $i++) {
	$myData->addPoints(explode(",", $y_str_array[$i]), "Y" . $i);
	$myData->setSerieOnAxis("Y" . $i,1);
 }
 if (isset($yaxislabel)) {
	$myData->setAxisName(1,$yaxislabel);
 }
 $myData->setAxisXY(1,AXIS_Y);
 // $myData->setAxisUnit(1,"°");
 $myData->setAxisPosition(1,AXIS_POSITION_LEFT);

 // Create the scatter chart binding 
 for ($i = 0; $i < count($x_str_array); $i++) {
	$myData->setScatterSerie("X" . $i, "Y" . $i, $i);
	$myData->setScatterSerieDescription($i, $legend_str_array[$i]);
	if (isset($color_array[$i])) {
		$rgb = explode(',', $color_array[$i]);
		if (count($rgb) == 3) {
			$myData->setScatterSerieColor($i,array(
				"R"=>intval($rgb[0]),"G"=>intval($rgb[1]),"B"=>intval($rgb[2])));
		}
	}
 }
 
 /* ***************     Create the pChart object     *************** */
 $myPicture = new pImage($width, $height, $myData);

 /* Add a border to the picture */
 $myPicture->drawRectangle(0,0,($width - 1), ($height - 1), array("R"=>0,"G"=>0,"B"=>0));
 
 /* Write the chart title */ 
 $myPicture->setFontProperties(array("FontName"=>"../pChart/fonts/verdana.ttf", "FontSize"=>11));
 $myPicture->drawText(50, 35, $title, array("FontSize"=>20, "Align"=>TEXT_ALIGN_BOTTOMLEFT));
 
 /* Set the default font */
 $myPicture->setFontProperties(array("FontName"=>"../pChart/fonts/verdana.ttf","FontSize"=>8));

 /* Define the chart area */
 $myPicture->setGraphArea(60,40,($width - 50), (0.85 * $height));

 /* Create the Scatter chart object */
 $myScatter = new pScatter($myPicture,$myData);

 /* manually scale the chart */
 $AxisBoundaries = array(
	0=>array("Min"=>$scale_min_x,"Max"=>$scale_max_x,"Rows"=>10,"RowHeight"=>120),
	1=>array("Min"=>$scale_min_y,"Max"=>$scale_max_y)
 );
 $ScaleSettings = array(
    "XMargin"=>15,
    "YMargin"=>15,
    "Floating"=>TRUE,
    "GridR"=>200,
    "GridG"=>200,
    "GridB"=>200,
    "DrawSubTicks"=>FALSE,
    "CycleBackground"=>TRUE,
    "Mode"=>SCALE_MODE_MANUAL,
    "ManualScale"=>$AxisBoundaries
 );
 $myScatter->drawScatterScale($ScaleSettings); 
 
 /* Turn on shadow computing */
 $myPicture->setShadow(TRUE,array("X"=>1,"Y"=>1,"R"=>0,"G"=>0,"B"=>0,"Alpha"=>10));

 /* Draw a scatter plot chart */
 $myScatter->drawScatterLineChart();

 /* Draw the legend */
 $myScatter->drawScatterLegend(($width - 200), ($height - 20), array("Mode"=>LEGEND_HORIZONTAL,"Style"=>LEGEND_NOBORDER));

 /* Set the default font */
 $myPicture->setFontProperties(array("FontName"=>"../pChart/fonts/verdana.ttf","FontSize"=>11));

 /* Write a label over the chart */
 //$LabelSettings = array("Decimals"=>1,"TitleMode"=>LABEL_TITLE_BACKGROUND,"TitleR"=>255,"TitleG"=>255,"TitleB"=>255);
 //$myScatter->writeScatterLabel(1,17,$LabelSettings);

 /* ***************         Render the picture        *************** */
 $myPicture->autoOutput();
