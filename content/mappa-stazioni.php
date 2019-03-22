  <?php
    session_start();
    $con = mysqli_connect('localhost', 'simulazione_admin', 'simulazione', 'simulazione')
                  or die('Something went horribly wrong with the connection' . mysqli_connect_error());
// centriamo la mappa rispetto alle coordinate delle stazioni disponibili
    $sql="SELECT Max(Latitudine) as MaxLat, Min(Latitudine) as MinLat, Max(Longitudine) as MaxLon, Min(Longitudine) as MinLon FROM stazioni";
    $result = mysqli_query($con,$sql);
    $row=mysqli_fetch_array($result);
    $MedLon=($row['MaxLon']+$row['MinLon']) / 2;
    $MedLat=($row['MaxLat']+$row['MinLat']) / 2;
  ?>

  <div id = "map"></div>

  <script>
     // Creiamo le opzioni per la mappa, centrandola rispetto ai valori trovati con la query precedente
     var mapOptions = {
        center: [<?php echo $MedLat.",".$MedLon; ?>],
        zoom: 15
     }
     // Creiamo l'oggetto mappa
     var map = new L.map('map', mapOptions);

     // Creiamo un layer
     var layer = new L.TileLayer('http://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png');

     // Aggiungiamo il layer alla mappa
     map.addLayer(layer);

     <?php
        $sql="SELECT * FROM stazioni";

        $result = mysqli_query($con,$sql);
        $i=0;

        // Aggiungiamo un marker per ogni stazione
        while($row = mysqli_fetch_array($result))
        {
            echo "var marker$i = new L.Marker([".$row['Latitudine'].",".$row['Longitudine']."]).on('click', markerOnClick$i).addTo(map);\n";
            $i++;
        }

        mysqli_data_seek($result, 0); //Ripartiamo dall'inizio con un nuovo fetch

        // Per ogni stazione aggiungiamo un handler al click sul relativo marker
        $i=0;
        while($row = mysqli_fetch_array($result))
        {
            echo "function markerOnClick$i(e)\n{\n";
            // Se ci sono bici disponibili il numero sarà verde, rosso in caso contrario
            $msg="Stazione <b>".$row['Nome']."</b><br>".$row['Indirizzo']."<br>";
            if ($row['BiciDisponibili'] > 0)
                $msg.= "<span class=\"verde\">Bici disponibili: ".$row['BiciDisponibili']."</span>";
            else
                $msg.= "<span class=\"rosso\">Bici disponibili: ".$row['BiciDisponibili']."</span>";
            $SlotLiberi=50 - $row['BiciDisponibili'];
            if ($SlotLiberi > 0)
                $msg.= "<br><span class=\"verde\">Slot liberi per la riconsegna: ".$SlotLiberi."</span>";
            else
                $msg.= "<br><span class=\"rosso\">Slot liberi per la riconsegna: ".$SlotLiberi."</span>";
            echo "     bootbox.alert('$msg')";
            echo "}\n";
            $i++;
        }
     ?>
  </script>
