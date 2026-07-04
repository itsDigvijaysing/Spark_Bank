<?php
include 'config.php';

if(isset($_POST['submit']))
{
    $from = filter_var($_GET['id'], FILTER_VALIDATE_INT);
    $to = filter_var($_POST['to'], FILTER_VALIDATE_INT);
    $amount = filter_var($_POST['amount'], FILTER_VALIDATE_FLOAT);

    if ($from === false || $to === false || $amount === false) {
        echo "<script>alert('Invalid transfer details');</script>";
    }
    else if ($from === $to) {
        echo "<script>alert('Sender and receiver must be different');</script>";
    }
    else if ($amount < 0) {
        echo '<script type="text/javascript">';
        echo ' alert("Oops! Negative values cannot be transferred")';
        echo '</script>';
    }
    else if ($amount == 0) {
         echo "<script type='text/javascript'>";
         echo "alert('Oops! Zero value cannot be transferred')";
         echo "</script>";
     }
    else {
        $senderStmt = mysqli_prepare($conn, "SELECT id, name, balance FROM users WHERE id = ?");
        mysqli_stmt_bind_param($senderStmt, "i", $from);
        mysqli_stmt_execute($senderStmt);
        $senderResult = mysqli_stmt_get_result($senderStmt);
        $sender = mysqli_fetch_assoc($senderResult);
        mysqli_stmt_close($senderStmt);

        $receiverStmt = mysqli_prepare($conn, "SELECT id, name, balance FROM users WHERE id = ?");
        mysqli_stmt_bind_param($receiverStmt, "i", $to);
        mysqli_stmt_execute($receiverStmt);
        $receiverResult = mysqli_stmt_get_result($receiverStmt);
        $receiver = mysqli_fetch_assoc($receiverResult);
        mysqli_stmt_close($receiverStmt);

        if (!$sender || !$receiver) {
            echo "<script>alert('Invalid sender or receiver account');</script>";
        }
        else if ($amount > $sender['balance']) {
            echo '<script type="text/javascript">';
            echo ' alert("Bad Luck! Insufficient Balance")';
            echo '</script>';
        }
        else {
            mysqli_begin_transaction($conn);

            $senderBalance = $sender['balance'] - $amount;
            $receiverBalance = $receiver['balance'] + $amount;

            $debitStmt = mysqli_prepare($conn, "UPDATE users SET balance = ? WHERE id = ?");
            mysqli_stmt_bind_param($debitStmt, "di", $senderBalance, $from);
            $debitOk = mysqli_stmt_execute($debitStmt);
            mysqli_stmt_close($debitStmt);

            $creditStmt = mysqli_prepare($conn, "UPDATE users SET balance = ? WHERE id = ?");
            mysqli_stmt_bind_param($creditStmt, "di", $receiverBalance, $to);
            $creditOk = mysqli_stmt_execute($creditStmt);
            mysqli_stmt_close($creditStmt);

            $transactionStmt = mysqli_prepare(
                $conn,
                "INSERT INTO transaction(sender, receiver, balance) VALUES (?, ?, ?)"
            );
            mysqli_stmt_bind_param(
                $transactionStmt,
                "ssd",
                $sender['name'],
                $receiver['name'],
                $amount
            );
            $transactionOk = mysqli_stmt_execute($transactionStmt);
            mysqli_stmt_close($transactionStmt);

            if ($debitOk && $creditOk && $transactionOk) {
                mysqli_commit($conn);
                echo "<script> alert('Transaction Successful');
                                window.location='transactionhistory.php';
                      </script>";
            } else {
                mysqli_rollback($conn);
                echo "<script>alert('Transaction failed. Please try again.');</script>";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transaction</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="css/table.css">
    <link rel="stylesheet" type="text/css" href="css/navbar.css">

    <style type="text/css">
    	
		button{
			border:none;
			background: #d9d9d9;
		}
	    button:hover{
			background-color:#777E8B;
			transform: scale(1.1);
			color:white;
		}

    </style>
</head>

<body style="background-color : #71C9CE ;">
 
<?php
  include 'navbar.php';
?>

	<div class="container">
        <h2 class="text-center pt-4" style="color : black;">Transaction</h2>
            <?php
                $sid = filter_var($_GET['id'], FILTER_VALIDATE_INT);
                $userStmt = mysqli_prepare($conn, "SELECT id, name, email, balance FROM users WHERE id = ?");
                mysqli_stmt_bind_param($userStmt, "i", $sid);
                mysqli_stmt_execute($userStmt);
                $result = mysqli_stmt_get_result($userStmt);
                $rows = mysqli_fetch_assoc($result);
                mysqli_stmt_close($userStmt);

                if(!$rows)
                {
                    echo "Error: user not found";
                } else {
            ?>
            <form method="post" name="tcredit" class="tabletext" ><br>
        <div>
            <table class="table table-striped table-condensed table-bordered">
                <tr style="color : black;">
                    <th class="text-center">Id</th>
                    <th class="text-center">Name</th>
                    <th class="text-center">Email</th>
                    <th class="text-center">Balance</th>
                </tr>
                <tr style="color : black;">
                    <td class="py-2"><?php echo htmlspecialchars($rows['id']); ?></td>
                    <td class="py-2"><?php echo htmlspecialchars($rows['name']); ?></td>
                    <td class="py-2"><?php echo htmlspecialchars($rows['email']); ?></td>
                    <td class="py-2"><?php echo htmlspecialchars($rows['balance']); ?></td>
                </tr>
            </table>
        </div>
        <br><br><br>
        <label style="color : black;"><b>Transfer To:</b></label>
        <select name="to" class="form-control" required>
            <option value="" disabled selected>Choose</option>
            <?php
                $listStmt = mysqli_prepare($conn, "SELECT id, name, balance FROM users WHERE id != ?");
                mysqli_stmt_bind_param($listStmt, "i", $sid);
                mysqli_stmt_execute($listStmt);
                $result = mysqli_stmt_get_result($listStmt);
                if(!$result)
                {
                    echo "Error loading users";
                }
                while($recipient = mysqli_fetch_assoc($result)) {
            ?>
                <option class="table" value="<?php echo htmlspecialchars($recipient['id']);?>" >
                
                    <?php echo htmlspecialchars($recipient['name']); ?> (Balance: 
                    <?php echo htmlspecialchars($recipient['balance']); ?> ) 
               
                </option>
            <?php 
                }
                mysqli_stmt_close($listStmt);
            ?>
            <div>
        </select>
        <br>
        <br>
            <label style="color : black;"><b>Amount:</b></label>
            <input type="number" class="form-control" name="amount" required>   
            <br><br>
                <div class="text-center" >
            <button class="btn mt-3" name="submit" type="submit" id="myBtn" >Transfer</button>
            </div>
        </form>
            <?php } ?>
    </div>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ho+j7jyWK8fNQe+A12Hb8AhRq26LrZ/JpcUGGOn+Y7RsweNrtN/tE3MoK7ZeZDyx" crossorigin="anonymous"></script>
</body>
</html>
