<?php
class AgroLogistics_Component_GraphGenerator
{
    public static function generateGraph($title, $chartingData, $xAxisTitle, $yAxisTitle, $width, $height, $chartType)
    {
        // Create a graph instance
        switch($chartType)
        {
            case 'line':
            case 'scatter':
            case 'bar':
                $graph = new Graph($width, $height);

                // Specify what scale we want to use,
                // int = integer scale for the X-axis
                // int = integer scale for the Y-axis
                $graph->SetScale('intint');

                // Setup titles and X-axis labels
                $graph->xaxis->title->Set($xAxisTitle);

                // Setup Y-axis title
                $graph->yaxis->title->Set($yAxisTitle);
                break;

            case 'pie':
                $graph = new PieGraph($width, $height);
                break;

            default:
                throw new InvalidChartTypeException();
        }

        // Setup a title for the graph
        $graph->title->Set($title);

        $ydata = $chartingData;

        // Create the linear plot
        switch($chartType)
        {
            case 'line':
                $graphObject = new LinePlot($ydata);
                break;

            case 'scatter':
//                    $graph->SetScale('linlin'); 

                $graph->SetShadow(); 

                $graph->title->SetFont(FF_FONT1,FS_BOLD); 

                $graphObject = new ScatterPlot($ydata); 
                $graphObject->mark->SetType(MARK_FILLEDCIRCLE); 
                $graphObject->mark->SetFillColor("red"); 
                $graphObject->mark->SetWidth(5);       
                break;

            case 'bar':
//                    $graph->SetScale('linlin'); 

                $graph->SetShadow(); 

                $graphObject = new BarPlot($ydata); 

                break;

            case 'pie':

                $graphObject = new PiePlot3D($ydata); 

                break;

            default:
                throw new InvalidChartTypeException();
        }

        // Add the plot to the graph
        $graph->Add($graphObject);

        // Display the graph
        $graph->Stroke();

        // Stream the result back as a PNG image
        header("Content-type: image/png");
    }
}