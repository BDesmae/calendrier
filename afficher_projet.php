<?php

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
$req = $bdd->query('SELECT date_deb, date_fin, color FROM projet ORDER BY date_deb');

$projet_off = true;

while ($donnees = $req->fetch()){
  $explode_deb = explode("-", $donnees['date_deb']);
  $explode_fin = explode("-", $donnees['date_fin']);

  $jour_deb = $explode_deb[2];
  $mois_deb = $explode_deb[1];
  $annee_deb = $explode_deb[0];

  $jour_fin = $explode_fin[2];
  $mois_fin = $explode_fin[1];
  $annee_fin = $explode_fin[0];

  $color = $donnees['color'];
  
if ($annee_deb == $lAnneeCourante AND $annee_fin == $lAnneeCourante){

  if($lMoisCourant == $mois_deb AND $lMoisCourant != $mois_fin){

    if ($lNumeroDuJour >= $jour_deb)
      {
        echo "<td style='background: ". $color . " '>  </td>"."\n";
        
      }
      else 
      {
        continue;
      }
      $projet_off = false;
      
    }

    
    
 
    elseif($lMoisCourant == $mois_fin AND $lMoisCourant != $mois_deb){

      if ($lNumeroDuJour <= $jour_fin)
        {
          echo "<td style='background: ". $color . " '>  </td>"."\n";
          
        }
        else 
        {
          continue;
        }
        $projet_off = false;
        
      }

    elseif($lMoisCourant == $mois_deb AND $lMoisCourant == $mois_fin)
    {
      if ($lNumeroDuJour >= $jour_deb AND $lNumeroDuJour <= $jour_fin)
      {
        echo "<td style='background: ". $color . " '>  </td>"."\n";
        
      }
      else 
      {
        continue;    
      }
      $projet_off = false;
      
    }

    elseif($mois_deb < $lMoisCourant AND $lMoisCourant < $mois_fin)
    {
      echo "<td style='background: ". $color . " '>  </td>"."\n";
      $projet_off = false;
      continue;
    }
    
    if ($projet_off == false)
    {
      break;
    }
 }
}
/* if ($lNumeroDuJour == $jour_fin AND $lMoisCourant == $mois_fin)
{
  continue;
} */
if($projet_off == true){
  echo "<td>  </td>"."\n";
}




$req->closeCursor();

?>
