<?php
    if(isset($_GET['dr_id']))
    {
        $dr = $_GET['dr_id'];
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    
</body>
</html>

<div class="filter_bid_data">

</div>

<!-- General JS Scripts -->
<script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>

<script>

$(document).ready(function()
        {
    filter_bid_data();
        
        function filter_bid_data()
        {
            var bidding_data = 'fetch_data';
            $.ajax({
                url:"location.php",
                method:"POST",
                data:{bidding_data:bidding_data, dr_id:<?php echo $dr; ?>},
                success:function(data){
                    $('.filter_bid_data').html(data);
                }
            });
        }
        
        setInterval(function()
        {
            filter_bid_data(); 
        }, 500);

        });
</script>