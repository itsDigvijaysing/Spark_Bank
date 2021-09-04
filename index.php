<!doctype html>
<html lang="en">
  <style>
  body {font-family: Arial, Helvetica, sans-serif;}
  * {box-sizing: border-box;}

  .bg-img {
    /* The image used */
    background-image: url("../Spark-Bank/img/money.jpg");
    height: 85vh;
    width: 100%;
    /* min-height: 760px; */

    /* Center and scale the image nicely */
    background-position: center;
    background-repeat: no-repeat;
    background-size: cover;
  }
  </style>
  
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="icon" href="../Spark-Bank/img/spark_foundation.png">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <link rel="stylesheet" type="text/css" href="css/navbar.css">

    <title>Spark Banking System</title>
  </head>

  <body>
    <?php
    include 'navbar.php';
    ?>
    <div class="bg-img">
      <div class="container">
                <div class="row activity text-center">
                      <div class="col-4 act" style="margin-top: 15%;">
                        <img src="img/user.png" class="img-fluid">
                        <br>
                        <a href="createuser.php"><button style="background-color : #2785C4;">Create a User</button></a>
                      </div>
                      <div class="col-4 act">
                        <img src="img/transfer.png" class="img-fluid">
                        <br>
                        <a href="transfermoney.php"><button style="background-color : #2785C4;">Make a Transaction</button></a>
                      </div>
                      <div class="col-4 act" style="margin-top: 15%;">
                        <img src="img/history.png" class="img-fluid">
                        <br>
                        <a href="transactionhistory.php"><button style="background-color : #2785C4;">Transaction History</button></a>
                      </div>
                </div>
      </div>
    </div>
    <footer class="text-center py-2">
          <p><b>Spark Foundation Project by Digvijaysing<b></p>
    </footer>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>
  </body>
</html>

