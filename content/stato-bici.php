<?php
  session_start();
  $connection = mysqli_connect('localhost', 'simulazione_admin', 'simulazione', 'simulazione')
                or die('Something went horribly wrong with the connection' . mysqli_connect_error());

  /* Seleziono i tag RFID delle biciclette attualmente non disponibili */
  $query_bici = "SELECT b.TagRFID FROM biciclette b WHERE b.stato = 'unavailable'";

  if(!$result_bici = mysqli_query($connection,$query_bici)) {
    echo "Something went horribly wrong with the query\n";
    echo "Errno: " . $connection -> errno . "\n";
    echo "Error: " . $connection -> error . "\n";
    exit;
  }

  echo "<h3>Stato delle biciclette</h3>\n";
  if ($result_bici -> num_rows === 0) {
    echo "<p>Non ci sono attualmente biciclette in uso.</p>";
  } else {
    echo "<p>Ci sono attualmente $result_bici->num_rows biciclette in uso.</p>";

    /* Per ognuno dei tag RFID precedenti, seleziono l'ultima operazione in
       ordine di tempo. */
    while($res = mysqli_fetch_assoc($result_bici)) {
      $rfidBici = $res['TagRFID'];
      $query = "SELECT o.tagRFID, u.Nome, u.Cognome, s.Nome, o.Data_Ora_Ritiro
                    FROM operazioni o
                    JOIN utenti u ON o.idUtente = u.idUtente
                    JOIN stazioni s ON o.idStazioneRitiro = s.idStazione
                  WHERE o.tagRFID = $rfidBici
                  AND o.Data_Ora_Consegna IS NULL
                  ORDER BY o.Data_Ora_Consegna DESC
                  LIMIT 1";

      if(!$result = mysqli_query($connection, $query)) {
        echo "Something went horribly wrong with the query\n";
        echo "Errno: " . $connection -> errno . "\n";
        echo "Error: " . $connection -> error . "\n";
        exit;
      }

      while ($op = mysqli_fetch_assoc($result)) {
        echo "<div class='bici-status'>\n";
        echo "<div class='bici-id'><h4>Id: " . $op['tagRFID'] . "</h4></div>\n";
        echo "<div class='bici-status-content'>\n";
        echo "<p>Attualmente <strong>in uso</strong></p>\n";
        echo "<p>Prelevata da " . $op['Nome'] . " " . $op['Cognome'] . "</p>\n";
        echo "<p>Ora prelievo: " . $op['Data_Ora_Ritiro'] . "</p>\n";
        echo "<p>Stazione ritiro: " . $op['Nome'] . "</p>\n";
        echo "</div>\n</div>\n";
      }
    }
  }
?>

<!-- Semplice script javascript per attivare l'animazione di apertura del
     div contenente i dati della bicicletta in uso.
     Inoltre, avete notato il modo diverso di fare i commenti rispetto a prima?
   -->
<script type="text/javascript">
  $(".bici-status").click(function () {
    $header = $(this);
    $content = $header.children('.bici-status-content').first();
    $content.slideToggle(500);
  });
</script>
