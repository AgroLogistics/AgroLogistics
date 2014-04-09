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


Sample Input:

    [
        {
            "cropType": "Yam",
            "shelfLife": "15",
            "harvests":
                    [
                        {"quantity": "10000", "availableDateStart": "1/03/2014", "availableDateEnd": "4/03/2014", "source": "1"},
                        {"quantity": "20000", "availableDateStart": "13/03/2014", "availableDateEnd": "17/03/2014", "source": "1"},
                        {"quantity": "6000", "availableDateStart": "11/03/2014", "availableDateEnd": "12/03/2014", "source": "2"},
                        {"quantity": "10000", "availableDateStart": "09/03/2014", "availableDateEnd": "10/03/2014", "source": "3"},
                        {"quantity": "60000", "availableDateStart": "10/03/2014", "availableDateEnd": "11/03/2014", "source": "4"}
                    ]
                    
        },
        {
            "cropType": "Coconut",
            "shelfLife": "20",
            "harvests":
                    [
                        {"quantity": "9000", "availableDateStart": "14/03/2014", "availableDateEnd": "17/03/2014", "source": "5"},
                        {"quantity": "300", "availableDateStart": "18/03/2014", "availableDateEnd": "21/03/2014", "source": "1"},
                        {"quantity": "1000", "availableDateStart": "08/03/2014", "availableDateEnd": "11/03/2014", "source": "2"},
                        {"quantity": "50", "availableDateStart": "06/03/2014", "availableDateEnd": "09/03/2014", "source": "6"},
                        {"quantity": "60", "availableDateStart": "05/03/2014", "availableDateEnd": "08/03/2014", "source": "9"}
                    ]
                    
        }
    ]


Sample Output:

    [
        {
            "vesselName": "Titanic",
            "route":
                    [
                        {"portName": "KIN", "landingDate": "2/03/2014", "departureDate": "3/03/2014", "containerSpaces": "24"},
                        {"portName": "HAV", "landingDate": "4/03/2014", "departureDate": "6/03/2014", "containerSpaces": "13"},
                        {"portName": "MIA", "landingDate": "15/03/2014", "departureDate": "17/03/2014", "containerSpaces": "9"},
                        {"portName": "BEI", "landingDate": "25/03/2014", "departureDate": "30/03/2014", "containerSpaces": "7"},
                        
                        {"portName": "KIN", "landingDate": "1/04/2014", "departureDate": "3/04/2014", "containerSpaces": "12"},
                        {"portName": "HAV", "landingDate": "4/04/2014", "departureDate": "6/04/2014", "containerSpaces": "13"},
                        {"portName": "MIA", "landingDate": "15/04/2014", "departureDate": "17/04/2014", "containerSpaces": "10"},
                        {"portName": "BEI", "landingDate": "25/04/2014", "departureDate": "30/04/2014", "containerSpaces": "30"}
                        
                        
                        
                    ]
                    
        },
        {
            "vesselName": "Carnival",
            "route":
                    [
                        {"portName": "KIN", "landingDate": "1/03/2014", "departureDate": "3/03/2014", "containerSpaces": "3"},
                        {"portName": "HAV", "landingDate": "10/03/2014", "departureDate": "11/03/2014", "containerSpaces": "4"},
                        {"portName": "MIA", "landingDate": "15/03/2014", "departureDate": "17/03/2014", "containerSpaces": "4"},
                        {"portName": "BEI", "landingDate": "25/03/2014", "departureDate": "30/03/2014", "containerSpaces": "1"}
                    ]
                    
        }
    ]


Endpoint
--------

http://uwi-agrologistics.appspot.com/api/index/generate-graph
