<?php
/* if ($lJourTravaille == false){
    echo "<td class='weekend'>  </td>"."\n";

    $lJourTravaille = true;
  }
else{ */
  //connexion base de données
try
{
    $bdd = new PDO('mysql:host=localhost;dbname=calendrier;charset=utf8', 'root', 'root');
}
catch (Exception $e)
{
    die('Erreur : ' . $e->getMessage());
}

//requête
$req = $bdd->query('SELECT date_deb, date_fin FROM projet');


while ($donnees = $req->fetch()){
  $explode_deb = explode("-", $donnees['date_deb']);
  $explode_fin = explode("-", $donnees['date_fin']);


  if ($lNumeroDuJour >= $explode_fin[2] AND $lNumeroDuJour <= $explode_deb[2] AND $lMoisCourant >= $explode_fin[1] AND $lMoisCourant <= $explode_deb[1] AND $lAnneeCourante >= $explode_fin[0] AND $lAnneeCourante <= $explode_deb[0])
    {
      echo "<td class='projet'>  </td>"."\n";
    }
    else 
    {
      echo "<td>  </td>";
    }  
  
}
/* $date = array($lAnneeCourante, $lMoisCourant, $lNumeroDuJour);
$implode_date = implode("-", $date);

while($donnees = $req->fetch()){

  if ($implode_date >= $donnees['date_deb'] AND $implode_date <= $donnees['date_fin'])
  {
    echo "<td class='projet'>  </td>"."\n";
  }
  else{
    echo "<td>  </td>";
  }
} */


$req->closeCursor();

?>
