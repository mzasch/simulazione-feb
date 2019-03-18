<?php
  session_start();
  $connection = mysqli_connect('localhost', 'simulazione_admin', 'simulazione', 'simulazione')
                or die('Something went horribly wrong with the connection' . mysqli_connect_error());

  $query = "SELECT s.idStazione, s.Nome, s.Indirizzo, s.BiciDisponibili, s.Latitudine, s.Longitudine
              FROM stazioni s";

  if(!$result = $connection -> query($query)) {
    echo "Something went horribly wrong with the query";
    echo "Errno: " . $connection -> errno . "\n";
    echo "Error: " . $connection -> error . "\n";
    exit;
  }

  if ($result -> num_rows === 0) {
    echo "Something went horribly wrong with the query";
    echo "Errno: " . $connection -> errno . "\n";
    echo "Error: " . $connection -> error . "\n";
    exit;
  }
?>

<h3>Scegli la stazione</h3>
<div class='form-container'>
  <form action="<?php config('site_url') . '/' ?>stato-stazione" method="POST">
    <select name="stazione">
      <?php
        while ($op = $result -> fetch_assoc()) {
          echo "<option value='" . $op['idStazione'] . "'>Stazione " . $op['Nome'] . "</option>\n";
        }
      ?>
    </select>
    <input type="submit" value="Cerca" />
  </form>
</div>
