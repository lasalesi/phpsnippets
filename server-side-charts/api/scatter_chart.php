<?php   
 /* this is an API to generate charts on a php server
  *
  * use like this
  *
  * scatter_chart.php?x[0]=1,2,3,4,5&y[0]=1,2,3,4,5&legend[0]=Outside&title=Average temperature&xlabel=Hour&ylabel=Temperature
  *
  */

 /* load pChart library inclusions */
 require_once("../pChart/class/pData.class.php");
 require_once("../pChart/class/pDraw.class.php");
 require_once("../pChart/class/pImage.class.php");
 require_once("../pChart/class/pScatter.class.php");
 
 /* constants */
 $width = 700;
 $height = 450;
 
 /* read parameters */
 if (isset($_GET['x']))
 {
	$x_str_array = $_GET['x'];
 }
 else
 {
	$x_str_array = array(0=>"1,2,3,4,5");
 }
 if (isset($_GET['y']))
 {
	$y_str_array = $_GET['y'];
 }
 else
 {
	$y_str_array = array(0=>"1,2,3,4,5");
 }
 if (isset($_GET['legend']))
 {
	$legend_str_array = $_GET['legend'];
 }
 else
 {
	$legend_str_array = array(0=>"Sample data");
 }
 if (isset($_GET['title']))
 {
	$title = $_GET['title'];
 }
 else
 {
	$title = "My awesome chart";
 }
 if (isset($_GET['xaxislabel']))
 {
	$xaxislabel = $_GET['xaxislabel'];
 }
 if (isset($_GET['yaxislabel']))
 {
	$yaxislabel = $_GET['yaxislabel'];
 }

 /* Create and populate the pData object */
 $myData = new pData();  

 // Create the X axis and the binded series 
 for ($i = 0; $i < count($x_str_array); $i++) {
	$myData->addPoints(explode(",", $x_str_array[$i]), "X" . $i);
 }
 if (isset($xaxislabel)) {
	$myData->setAxisName(0,$xaxislabel);
 }
 $myData->setAxisXY(0,AXIS_X);
 $myData->setAxisPosition(0,AXIS_POSITION_BOTTOM);

 // Create the Y axis and the binded series 
 for ($i = 0; $i < count($y_str_array); $i++) {
	$myData->addPoints(explode(",", $y_str_array[$i]), "Y" . $i);
	$myData->setSerieOnAxis("Y" . $i,1);
 }
 if (isset($yaxislabel)) {
	$myData->setAxisName(1,$yaxislabel);
 }
 $myData->setAxisXY(1,AXIS_Y);
 //$myData->setAxisUnit(1,"°");
 $myData->setAxisPosition(1,AXIS_POSITION_LEFT);

 // Create the 1st scatter chart binding 
 for ($i = 0; $i < count($x_str_array); $i++) {
	$myData->setScatterSerie("X" . $i, "Y" . $i, $i);
	$myData->setScatterSerieDescription($i, $legend_str_array[$i]);
	//$myData->setScatterSerieTicks(0,4); // makes a dotted line
	//$myData->setScatterSerieColor(0,array("R"=>0,"G"=>0,"B"=>0));
 }
 
 /* Create the pChart object */
 $myPicture = new pImage($width, $height, $myData);

 /* Add a border to the picture */
 $myPicture->drawRectangle(0,0,($width - 1), ($height - 1), array("R"=>0,"G"=>0,"B"=>0));
 
 /* Write the chart title */ 
 $myPicture->setFontProperties(array("FontName"=>"../pChart/fonts/verdana.ttf", "FontSize"=>11));
 $myPicture->drawText(150, 35, $title, array("FontSize"=>20, "Align"=>TEXT_ALIGN_BOTTOMMIDDLE));

 /* Set the default font */
 $myPicture->setFontProperties(array("FontName"=>"../pChart/fonts/verdana.ttf","FontSize"=>8));

 /* Define the chart area */
 $myPicture->setGraphArea(60,40,($width - 50), (0.85 * $height));

 /* Create the Scatter chart object */
 $myScatter = new pScatter($myPicture,$myData);

 /* Draw the scale */
 $myScatter->drawScatterScale();

 /* Turn on shadow computing */
 //$myPicture->setShadow(TRUE,array("X"=>1,"Y"=>1,"R"=>0,"G"=>0,"B"=>0,"Alpha"=>10));

 /* Draw a scatter plot chart */
 $myScatter->drawScatterLineChart();

 /* Draw the legend */
 $myScatter->drawScatterLegend(($width - 120), ($height - 20), array("Mode"=>LEGEND_HORIZONTAL,"Style"=>LEGEND_NOBORDER));

 /* Set the default font */
 $myPicture->setFontProperties(array("FontName"=>"../pChart/fonts/verdana.ttf","FontSize"=>11));

 /* Write a label over the chart */
 $LabelSettings = array("Decimals"=>1,"TitleMode"=>LABEL_TITLE_BACKGROUND,"TitleR"=>255,"TitleG"=>255,"TitleB"=>255);
 $myScatter->writeScatterLabel(1,17,$LabelSettings);

 /* Render the picture */
 $myPicture->autoOutput();
