<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Transactions</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">

    <link rel="stylesheet" href="./src/css/transactions.css">
</head>

<body>
    <?php
    error_reporting(E_ERROR | E_PARSE);
    // importing modules
    require "./config.php";
    require "./src/php/functions.php";
    if($_SERVER["REQUEST_METHOD"] != "GET" && $_SERVER["REQUEST_METHOD"] != "POST"){
        exit;
    }
    if($_SERVER["REQUEST_METHOD"] == "GET" || !($_POST["password"] == $loginPassword || $_POST["session"] == hashPassword($loginPassword)) ){
        echo '
        <div class="card" style="width: 18rem;">
            <div class="card-body">
                <form class="password-form" method="POST" action='.$_SERVER["PHP_SELF"].'>
                '
                .
                (($_SERVER["REQUEST_METHOD"] == "POST")?('<span class="text-danger">it was invalid password!</span>'):"")
                .
                '
                <input type="password" name="password" class="form-control mb-3 password" placeholder="Login password">
                <button type="submit" class="btn btn-secondary">Login</button>
                </form>
            </div>
        </div>';
        exit;
    }
    ?>
    <?php
    try {
      $transactions = getTransactions($sqlInfo,$_POST["by"]);
    } catch (\Throwable $th) {
        error("Error while getting all transactions.","Internal error or Your search key (7)","./transactions.php");
    }
    ?>
    <form class="mb-3 search" method=POST>
        <input type="hidden" name="session" class="form-control" value='<?=hashPassword($_POST["password"])?>'>
        <input type="text" name="by" class="form-control search-input" value='<?=$_POST["by"]??NULL?>'  placeholder="Key Phrase or Comparison">
        <button type="submit" class="btn btn-secondary">Search</button>
    </form>
    <code class="transactions-count"><h5><?=count($transactions)?> transactions found</h5></code>
    <br>
    <table class="table">
        <tr>
            <?php
          foreach ($transactions[0] as $key => $value) {
              echo "<th>".strtoupper($key)."</th>";
          }
        ?>
        </tr>
        <?php
        $count = count($transactions);
        for ($index = $count-1; $index>=0; $index--){
            $transaction = $transactions[$index];
            echo "<tr>";
        foreach ($transaction as $key => $value) {
            if($key == "id"){
                echo "<th scope='row'>".$value ."</th>";
            } else{
                echo "<td>".$value ."</td>";
            }
        }
        echo "</tr>";
        }
        ?>
    </table>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous">
    </script>
    <!--
</body>
</html>