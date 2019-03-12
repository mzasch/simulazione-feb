<?php
  session_start();
  $connection = mysqli_connect('localhost', 'simulazione_admin', 'simulazione', 'simulazione')
                or die('Something went horribly wrong with the connection' . mysqli_connect_error());

  if (!isset($_POST['datainizio']) || !isset($_POST['datafine']) || $_POST['datainizio'] == "" || $_POST['datafine'] == "") {
    header('Location: scelta-report.php');
    exit;
  }

  $query_utente = "SELECT u.Nome, u.Cognome FROM utenti u WHERE u.idUtente = " . $_POST['utente'];

  if(!$result_utente = mysqli_query($connection,$query_utente)) {
    echo "Something went horribly wrong with the query utenti\n";
    echo "Errno: " . $connection -> errno . "\n";
    echo "Error: " . $connection -> error . "\n";
    exit;
  }
  $ut = mysqli_fetch_assoc($result_utente);
  $startDate = date('Y-m-d', strtotime($_POST['datainizio']));
  $endDate = date('Y-m-d', strtotime($_POST['datafine']));

  $query_report = "SELECT o.idUtente,
                        s1.Nome AS StazioneRitiro, s2.Nome AS StazioneConsegna,
                        o.tagRFID, o.Data_Ora_Ritiro, o.Data_Ora_Consegna, o.Costo
                  FROM operazioni o
                    JOIN stazioni s1 ON o.idStazioneRitiro = s1.idStazione
                    JOIN utenti u ON o.idUtente = u.idUtente
                    LEFT JOIN stazioni s2 ON o.idStazioneConsegna = s2.idStazione
                  WHERE u.idUtente = " . $_POST['utente'] .
                     " AND o.Data_Ora_Ritiro BETWEEN '" . $startDate . "' AND '$endDate'
                  ORDER BY o.Data_Ora_Ritiro DESC";

  if(!$result_report = mysqli_query($connection,$query_report)) {
    echo "Something went horribly wrong with the query report\n";
    echo "Errno: " . $connection -> errno . "\n";
    echo "Error: " . $connection -> error . "\n";
    exit;
  }

  $groupedResult = array();
  $i = 0;

  while ($res = mysqli_fetch_assoc($result_report)) {
     $groupedResult[(new DateTime($res['Data_Ora_Ritiro']))->format("Y-m-d")][$i] = $res;
     $i++;
  }

  ksort($groupedResult);

  if ($result_report -> num_rows === 0) {
    echo "<p>Non sono stati effettuati noleggi nel periodo selezionato.</p>";
  } else {
    echo "<div id='report'>\n";
    echo "<div><h3>" . $ut['Nome'] . " " . $ut['Cognome'] . "</h3></div>\n";
    foreach($groupedResult as $date => $group) {
      echo "<div class='daily-report'>\n";
      echo "<div><h4 class='header'>" . date('d-m-Y', strtotime($date)) . "</h4></div>\n";
      echo "<div>\n";
      echo "<table>\n<thead>\n<th>Tag RFID</th><th>Orario Ritiro</th><th>St. Ritiro</th><th>Orario Consegna</th><th>St. Consegna</th><th>Costo</th>\n</thead>\n<tbody>\n";
      foreach($group as $operation){
        echo "<tr " . ($operation['StazioneConsegna'] == "" ? "class='ongoing-op'" : "") . ">\n";
        echo "<td>" . $operation['tagRFID'] . "</td>";
        echo "<td>" . (new DateTime($operation['Data_Ora_Ritiro']))->format("H:i:s") . "</td>";
        echo "<td>" . $operation['StazioneRitiro'] . "</td>";
        echo "<td>" . (new DateTime($operation['Data_Ora_Consegna']))->format("H:i:s") . "</td>";
        echo "<td>" . $operation['StazioneConsegna'] . "</td>";
        echo "<td>" . ($operation['Costo'] == 0 ? "n.d." : $operation['Costo']) . "</td>\n";
        echo "</tr>\n";
      }
      echo "</tbody>\n</table>\n</div>\n</div>\n";
    }
    echo "</div>\n</div>\n";
  }
?>
