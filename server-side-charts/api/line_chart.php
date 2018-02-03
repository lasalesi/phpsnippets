<?php   
 /* this is an API to generate charts on a php server
  *
  * use like this
  *
  * line_chart.php?x=1,2,3,4,5&y[0]=1,2,3,4,5&legend[0]=Outside&title=Average temperature&xlabel=Hour&ylabel=Temperature
  *
  */

 /* load pChart library inclusions */
 require_once("../pChart/class/pData.class.php");
 require_once("../pChart/class/pDraw.class.php");
 require_once("../pChart/class/pImage.class.php");

 /* read parameters */
 if (isset($_GET['x']))
 {
	$x_str = $_GET['x'];
 }
 else
 {
	$x_str = "1,2,3,4,5";
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
 $MyData = new pData();  
 for ($i = 0; $i < count($y_str_array); $i++) {
	$MyData->addPoints(explode(",", $y_str_array[$i]), $legend_str_array[$i]);
 }
 //$MyData->addPoints(array(3,12,15,8,5,-5),"Probe 2");
 //$MyData->addPoints(array(2,7,5,18,19,22),"Probe 3");
 //$MyData->setSerieTicks("Probe 2",4);
 //$MyData->setSerieWeight("Probe 3",2);
 if (isset($xaxislabel)) {
	$MyData->setAxisName(1,$xaxislabel);
 }
 if (isset($yaxislabel)) {
	$MyData->setAxisName(0,$yaxislabel);
 }
 $MyData->addPoints(explode(",", $x_str), "Labels");
 $MyData->setSerieDescription("Labels","Months");
 $MyData->setAbscissa("Labels");


 /* Create the pChart object */
 $myPicture = new pImage(700,230,$MyData);

 /* Turn of Antialiasing */
 $myPicture->Antialias = FALSE;

 /* Add a border to the picture */
 $myPicture->drawRectangle(0,0,699,229,array("R"=>0,"G"=>0,"B"=>0));
 
 /* Write the chart title */ 
 $myPicture->setFontProperties(array("FontName"=>"../pChart/fonts/verdana.ttf", "FontSize"=>11));
 $myPicture->drawText(150, 35, $title, array("FontSize"=>20, "Align"=>TEXT_ALIGN_BOTTOMMIDDLE));

 /* Set the default font */
 $myPicture->setFontProperties(array("FontName"=>"../pChart/fonts/verdana.ttf","FontSize"=>6));

 /* Define the chart area */
 $myPicture->setGraphArea(60,40,650,200);

 /* Draw the scale */
 $scaleSettings = array("XMargin"=>10,"YMargin"=>10,"Floating"=>TRUE,"GridR"=>200,"GridG"=>200,"GridB"=>200,"DrawSubTicks"=>TRUE,"CycleBackground"=>TRUE);
 $myPicture->drawScale($scaleSettings);

 /* Turn on Antialiasing */
 $myPicture->Antialias = TRUE;

 /* Draw the line chart */
 $myPicture->drawLineChart();

 /* Write the chart legend */
 $myPicture->drawLegend(540,20,array("Style"=>LEGEND_NOBORDER,"Mode"=>LEGEND_HORIZONTAL));

 /* Render the picture */
 $myPicture->autoOutput();
