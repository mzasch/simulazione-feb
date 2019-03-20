<?php
  session_start();
  $connection = mysqli_connect('localhost', 'simulazione_admin', 'simulazione', 'simulazione')
                or die('Something went horribly wrong with the connection' . mysqli_connect_error());

  if (!isset($_POST['datainizio']) || !isset($_POST['datafine']) || $_POST['datainizio'] == "" || $_POST['datafine'] == "") {
    header('Location: scelta-report.php');
    exit;
  }

  /* Converte la data dal formato PHP a quello di SQL*/
  $startDate = date('Y-m-d', strtotime($_POST['datainizio']));
  $endDate = date('Y-m-d', strtotime($_POST['datafine']));

  /* LEFT JOIN perché devo visualizzare anche le operazioni per le quali
     non esiste ancora la stazione di consegna
     */
  $query_report = "SELECT o.idUtente,
                        s1.Nome AS StazioneRitiro, s2.Nome AS StazioneConsegna,
                        o.tagRFID, o.Data_Ora_Ritiro, o.Data_Ora_Consegna, o.Costo
                  FROM operazioni o
                    JOIN stazioni s1 ON o.idStazioneRitiro = s1.idStazione
                    JOIN utenti u ON o.idUtente = u.idUtente
                    LEFT JOIN stazioni s2 ON o.idStazioneConsegna = s2.idStazione
                  WHERE u.idUtente = " . $_POST['utente'] .
                    " AND o.Data_Ora_Ritiro BETWEEN '$startDate' AND '$endDate'
                  ORDER BY o.Data_Ora_Ritiro DESC";

  if(!$result_report = mysqli_query($connection,$query_report)) {
    echo "Something went horribly wrong with the query report\n";
    echo "Errno: " . $connection -> errno . "\n";
    echo "Error: " . $connection -> error . "\n";
    exit;
  }

  /*
    Truccone n.1:
      I dati recuperati dalla query sono nella forma "una riga, una operazione";
      per poterli raggruppare per data il procedimento è quello di creare un
      array associativo che abbini una data (senza orario) all'array delle
      operazioni svolte in quella data.

      L'array $groupedResult conterrà quindi un insieme di stringhe (le date),
      ognuna abbinata ad un array che avrà all'interno le righe rappresentanti
      le operazioni svolte in quella data.

      La variabile $i serve per dare un indice diverso ad ogni riga, anche se
      appartiene a date diverse. Non è importante, in quanto il foreach
      successivo itera sulle righe dell'array senza bisogno dell'indice di
      ognuna.
   */
  $groupedResult = array();
  $i = 0;
  while ($res = mysqli_fetch_assoc($result_report)) {
    /* Truccone n.2:
        Per usare ksort (che ordina un array alfabeticamente) devo convertire
        la data nel formato Y-m-d (Anno, mese, giorno), rimuovendo l'ora*/
    $groupDate = (new DateTime($res['Data_Ora_Ritiro']))->format("Y-m-d");
    $groupedResult[$groupDate][$i] = $res;
    $i++;
  }
  ksort($groupedResult);

  /* Estraggo nome e cognome dell'utente selezionato */
  $query_utente = "SELECT u.Nome, u.Cognome FROM utenti u WHERE u.idUtente = " . $_POST['utente'];
  if(!$result_utente = mysqli_query($connection,$query_utente)) {
    echo "Something went horribly wrong with query_utente\n";
    echo "Errno: " . $connection -> errno . "\n";
    echo "Error: " . $connection -> error . "\n";
    exit;
  }

  $ut = mysqli_fetch_assoc($result_utente);

  if ($result_report -> num_rows === 0) {
    echo "<p>Non sono stati effettuati noleggi nel periodo selezionato.</p>";
  } else {
    echo "<div id='report'>\n";
    echo "<div><h3>" . $ut['Nome'] . " " . $ut['Cognome'] . "</h3></div>\n";
    foreach($groupedResult as $date => $group) {
      echo "<div class='daily-report'>\n";
      // Conversione del formato della data da Y-m-d a d-m-Y
      echo "<div><h4 class='header'>" . date('d-m-Y', strtotime($date)) . "</h4></div>\n";
      echo "<div>\n";
      echo "<table>\n<thead>\n<th>Tag RFID</th><th>Orario Ritiro</th><th>St. Ritiro</th><th>Orario Consegna</th><th>St. Consegna</th><th>Costo</th>\n</thead>\n<tbody>\n";
      foreach($group as $operation){
        echo "<tr " . ($operation['StazioneConsegna'] == "" ? "class='ongoing-op'" : "") . ">\n";
        echo "<td>" . $operation['tagRFID'] . "</td>";
        // Converto la data estraendo solo l'orario dell'operazione
        echo "<td>" . (new DateTime($operation['Data_Ora_Ritiro']))->format("H:i:s") . "</td>";
        echo "<td>" . $operation['StazioneRitiro'] . "</td>";

        /* Se la bici è in uso la data di consegna non esiste, ma new DateTime
           restituisce la data e ora attuali*/
        if(isset($operation['Data_Ora_Consegna'])){
          echo "<td>" . (new DateTime($operation['Data_Ora_Consegna']))->format("H:i:s") . "</td>";
        } else {
          echo "<td></td>";
        }
        
        echo "<td>" . $operation['StazioneConsegna'] . "</td>";
        echo "<td>" . ($operation['Costo'] == 0 ? "n.d." : $operation['Costo']) . "</td>\n";
        echo "</tr>\n";
      }
      echo "</tbody>\n</table>\n</div>\n</div>\n";
    }
    echo "</div>\n</div>\n";
  }
?>
