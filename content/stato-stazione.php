<?php
  session_start();
  $connection = mysqli_connect('localhost', 'simulazione_admin', 'simulazione', 'simulazione')
                or die('Something went horribly wrong with the connection' . mysqli_connect_error());

  /* Nel caso in cui non sia stata impostato il parametro stazione (cioè siamo
     arrivati a questa pagina non dalla form scelta-stazione.php), applichiamo
     un codice 302 - Moved temporarily e redirigiamo la richiesta alla pagina
     corretta. */
  if(!isset($_POST['stazione'])){
    header('Location: scelta-stazione.php');
    exit;
  }

  /* Questo array è assolutamente inutile: abbina dei colori agli id delle
     stazioni, così da recuperare le immagini in images/"color"-station.png.
     In una applicazione reale avremmo questa informazione salvata nel
     database, ma non l'abbiamo inserita per lasciare il DB quanto più simile
     a quanto richiesto dal testo. */
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
  <img src='images/<?php echo $colors[$op['idStazione']] ?>-station.png' alt='Stazione <?php echo $colors[$op['idStazione']] ?>' />;
  <div class="dati-stazione">
    <p>
    <?php
      echo $op['Indirizzo'] . " - Bici disponibili: " . $op['BiciDisponibili'] . "\n";
    ?>
  </p>
  </div>
</div>
