<?php
  session_start();
  $connection = mysqli_connect('localhost', 'simulazione_admin', 'simulazione', 'simulazione')
                or die('Something went horribly wrong with the connection' . mysqli_connect_error());

  if(!isset($_POST['stazione'])){
    header('Location: scelta-stazione.php');
    exit;
  }

  $colors = array('1' => 'purple', '2' => 'green');

  $query = "SELECT s.idStazione, s.Indirizzo, s.BiciDisponibili, s.Latitudine, s.Longitudine
              FROM stazioni s
              WHERE s.idStazione = ". $_POST['stazione'];

  if(!$result = $connection -> query($query)) {
    echo "Something went horribly wrong with the query syntax\n";
    echo "Errno: " . $connection -> errno . "\n";
    echo "Error: " . $connection -> error . "\n";
    exit;
  }

  if ($result -> num_rows === 0) {
    echo "Something went horribly wrong with the query number";
    echo "Errno: " . $connection -> errno . "\n";
    echo "Error: " . $connection -> error . "\n";
    exit;
  }
  $op = $result -> fetch_assoc();
?>
<h3>Stato della stazione</h3>
<div class='info-stazione'>
  <?php
    echo "<img src='images/" . $colors[$op['idStazione']] . "-station.png' alt='Stazione" . $colors[$op['idStazione']] . "' />";
  ?>
  <div class="dati-stazione">
    <?php
      echo $op['Indirizzo'] . " - Bici disponibili: " . $op['BiciDisponibili'] . "\n";
    ?>
  </div>
</div>
