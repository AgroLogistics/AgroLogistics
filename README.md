AgroLogistics - Reporting Component
===================================

Description 
------------

Prepared for: Dr. Curtis Busby-Earle  

Prepared by: Dean J. Jones, Evon Binns, Matthew Thomas, Delano Gaskin  

April 9, 2014  

Version number: v.1.0.0  


Component Description
---------------------

This component is designed to accept input and output a line|pie|scatter|bar graph.


Arguments
---------

    - requestData
        - title: The title that is to be displayed on the chart
        - data: A JSON array of numeric values
        - xAxisTitle: The title to be displayed for the x-axis
        - yAxisTitle: The title to be displayed for the y-axis
        - width: The width of the graph to be rendered in pixels (no units are to be specified)
        - height: The height of the graph to be generated (no units are to be specified)
        - chartType: The type of chart that the system should generate (options: line|pie|scatter|bar)

*requestData is a POST parameter that contains a JSON string with the keys described above.*

Sample Input
------------

    {
        "title": "New Title",
        "data": [12,235,436,34,34,343],
        "xAxisTitle": "Weeks",
        "yAxisTitle": "Production",
        "width": 600,
        "height": 200,
        "chartType": "line"
    }
            


Sample Output:
--------------
    <image>


Endpoint
--------

http://uwi-agrologistics.appspot.com/api/graph-generator/generate-graph


Tests
-----

http://uwi-agrologistics.appspot.com/test/index/test-generate-graph
