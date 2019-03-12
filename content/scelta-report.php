<?php
  session_start();
  $connection = mysqli_connect('localhost', 'simulazione_admin', 'simulazione', 'simulazione')
                or die('Something went horribly wrong with the connection' . mysqli_connect_error());

  $query_utenti = "SELECT u.idUtente, u.Nome, u.Cognome FROM utenti u";

  if(!$result = mysqli_query($connection,$query_utenti)) {
    echo "Something went horribly wrong with the query\n";
    echo "Errno: " . $connection -> errno . "\n";
    echo "Error: " . $connection -> error . "\n";
    exit;
  }

  if ($result -> num_rows === 0) {
    echo "Something went horribly wrong with the query\n";
    echo "Errno: " . $connection -> errno . "\n";
    echo "Error: " . $connection -> error . "\n";
    exit;
  }
?>

<h3>Scegli la data di inizio e di fine del report</h3>
<div class='form-container'>
  <form action="<?php config('site_url') . '/' ?>report-utente" method="post">
    <div class="row">
      <div class="col-25">
        <label for="sUtente">Utente:</label>
      </div>
      <div class="col-75">
        <select id='sUtente' name="utente">
          <?php
            while($res = mysqli_fetch_assoc($result)) {
              echo "<option value='" . $res['idUtente'] . "'>" . $res['Nome'] . " " . $res['Cognome'] . "</option>\n";
            }
          ?>
        </select>
      </div>
    </div>

    <div class="row">
      <div class="col-25">
        <label for="dInizio">Inizio:</label>
      </div>
      <div class="col-75">
        <input id='dInizio' type='date' name="datainizio" />
      </div>
    </div>

    <div class="row">
      <div class="col-25">
        <label for="dFine">Fine:</label>
      </div>
      <div class="col-75">
        <input id='dFine' type='date' name="datafine" />
      </div>
    </div>

    <div class='row'>
      <input type="submit" value='Genera report' />
    </div>
  </form>
</div>
