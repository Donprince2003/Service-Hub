<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/rateYo/2.3.2/jquery.rateyo.min.css"> 
</head>
<body>
    <div style="width: 600px; margin: 30px auto"> 
        <div id="rateYo"></div> 
    </div> 

    <!-- Load jQuery first -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script> 
    <!-- Load rateYo plugin -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/rateYo/2.3.2/jquery.rateyo.min.js"></script> 

    <script> 
        $(document).ready(function() {
            $("#rateYo").rateYo({ 
                rating: 2.384728383838, 
                spacing: "10px", 
                numStars: 5, 
                minValue: 0, 
                maxValue: 5, 
                normalFill: 'black', 
                ratedFill: 'orange'
            }); 
        });
    </script> 
</body>
</html>
