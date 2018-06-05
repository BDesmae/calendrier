<?php
session_start();

try
{
    $bdd = new PDO('mysql:host=localhost;dbname=calendrier;charset=utf8', 'root', 'root');
}
catch (Exception $e)
{
    die('Erreur : ' . $e->getMessage());
}

if(isset($_SESSION['id']) AND $_SESSION['id'] > 0)
{
    $getid = intval($_SESSION['id']);
    $requser = $bdd->prepare("SELECT * FROM users WHERE id=?");
    $requser->execute(array($getid));
    $userinfo = $requser->fetch();

  

?>

<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" type="text/css" href="style.php"/>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>


</head>
<body>
<h2>Liste des projets prévus: </h2>

<div class="container">
  <table class="table table-striped">
    <thead>
      <tr>
        <th>Nom du projet</th>
        <th>Date de début</th>
        <th>Date de fin</th>
        <th>Commentaire</th>
        <th>Couleur</th>
      </tr>
    </thead>
    <tbody>

<?php

$requete = $bdd->query('SELECT * from projet ORDER BY date_deb');
while ($liste_projet = $requete->fetch()){?>

      <tr>
        <td><?php echo $liste_projet['nom_projet'] ?></td>
        <td><?php echo $liste_projet['date_deb'] ?></td>
        <td><?php echo $liste_projet['date_fin'] ?></td>
        <td><?php echo $liste_projet['commentaire'] ?> </td>
        <td><?php echo $liste_projet['color'] ?> </td>
      </tr>
<?php
}
echo '
    </tbody>
  </table>
</div>';





define('NB_MOIS', '12');
define('NB_JOURS_MAX', '31');
//const NB_MOIS = 12;
//const NB_JOURS_MAX = 31;

function DessinerCalendrier(){
  
  $lNombreDeJourDuMois = 0;
  $lNumeroDuJour = 0;
  $lMoisCourant = 0;
  $lAnneeCourante = date('Y');
  $lNbLignes = 0;
  $lDernierJourDuMois = 0;
  $lJourTravaille = true;
  $lNumSemMoisCourant = 0;
  $lNumeroSemaine = 0;
  $lDateDebut = null;
  $lDateFin = null;
  $lNomPremierJourduMois = null;
  $lNomJour = null;
      
  $lNomMois = array ('JANVIER',
  'FEVRIER',
  'MARS',
  'AVRIL',
  'MAI',
  'JUIN',
  'JUILLET',
  'AOUT',
  'SEPTEMBRE',
  'OCTOBRE',
  'NOVEMBRE',
  'DECEMBRE');

  $lJoursWeekEndDuMois = array();
  $lLargeurColonneSemaine = array();

  setlocale(LC_TIME,'fra_fra');

  for ($lMoisCourant = 1; $lMoisCourant <= NB_MOIS; $lMoisCourant++){
   
    echo "<table>"."\n";
    echo "<tr class='semaine'>"."\n";
    echo "<td colspan='1' class='semaine'></td>"."\n";

    $lNumSemMoisCourant = (int)date("W", strtotime("{$lAnneeCourante}-{$lMoisCourant}-01"));
     
    $lNombreDeJourDuMois = cal_days_in_month(CAL_GREGORIAN,$lMoisCourant,$lAnneeCourante);
  
    for ($lNumeroDuJour = 1; $lNumeroDuJour <= $lNombreDeJourDuMois ; $lNumeroDuJour++) {
      // Tous les premiers du mois courant on recupre le nom abregé du jour
      // puis on déduit le nombre de jour necessaire pour aller jusqu'a la fin de la première semaine de chaque mois (1/xx/18),
      // que l'on mémorise dans l'array lLargeurColonneSemaine[], 
      // puis on avance l'index de traitement des jours lNumeroDuJour pour traiter la semaine suivante à partir du lundi.
      if ($lNumeroDuJour == 1){
        
        $lNomPremierJourduMois = strftime("%a", strtotime("{$lAnneeCourante}-{$lMoisCourant}-1"));

        switch ($lNomPremierJourduMois) {

          case "lun.":
            $lLargeurColonneSemaine[]= 7; 
            $lNumeroDuJour = 7; 
          break;

          case "mar.":
            $lLargeurColonneSemaine[]= 6;
            $lNumeroDuJour = 6; 
          break;

          case "mer.":
            $lLargeurColonneSemaine[]= 5;
            $lNumeroDuJour = 5;  
          break;

          case "jeu.":
            $lLargeurColonneSemaine[]= 4; 
            $lNumeroDuJour = 4;
          break;

          case "ven.":
            $lLargeurColonneSemaine[]= 3;
            $lNumeroDuJour = 3; 
          break;

          case "sam.":
            $lLargeurColonneSemaine[]= 2;
            $lNumeroDuJour = 2;   
          break;

          case "dim.":
            $lLargeurColonneSemaine[]= 1;
            $lNumeroDuJour = 1; 
          break;
          
          default:
            
            break;
        }   
      
      }else{
        
        //Ici on calcule la largeur "colspan" pour chaque semaine du mois courant.
        $lNomJour = strftime("%a", strtotime("{$lAnneeCourante}-{$lMoisCourant}-{$lNumeroDuJour}"));

        // On test si le jour est un Lundi.
        if ($lNomJour == "lun."){
          //On mémorise la date de début de semaine.
          $lDateDebut = strtotime("{$lAnneeCourante}-{$lMoisCourant}-{$lNumeroDuJour}");

        }

        // On test si le jour est un Dimanche.
        if($lNomJour == "dim."){
          //On mémorise la date de fin de semaine dans lDateFin.   
          $lDateFin = strtotime("{$lAnneeCourante}-{$lMoisCourant}-{$lNumeroDuJour}");
          
          // Puis on compare date de début et date de fin,
          // pour en déduire le nombre de jour necessaire pour représenter la semaine traitée (le colspan).
          // On enregistre la largeur de "colspan" de la semaine calculée dans un array "lLargeurColonneSemaine".
          $lLargeurColonneSemaine[] = (($lDateFin-$lDateDebut)/86400)+1;
          
          // Calcul du nombre de jours entre 2 dates : 60*60*24 = 86400
          // exemple ("1/01/18"-"2/01/18")/86400 donne 1 ce qui représente l'écart entre les deux dates données                  
          // le + 1 à la fin est necessaire,
          // car pour représenter un écart minimum de 1 pour un "colspan" de une semaine a afficher en début ou fin de mois
          // il faut fusionner au minimum 2 cellules de largeur Jour 1 et Jour 2.
          // l'Exemple donne ceci : 
          //  |~~Semaine X~~| --> colspan (ou cellules fusionnées) de 2 (ecart entre Jour 1 et Jour 2 + 1) pour la semaine n°X
          //  |Jour 1|Jour 2|
          
        }

        // On test si on arrive à la fin du mois
        if($lNumeroDuJour == $lNombreDeJourDuMois){
          // On vérifie que le dernier jour du mois n'est pas un dimanche if($lNomJour != "dim.") pour traiter le cas d'une semmaine incomplète en fin de mois
          // dans le cas contraire, pas de traitement particulier à faire car le traitement à été fait par le test précédent du
          // if($lNomJour == "dim.") qui nous à donné une semaine de 7 jours pour représenter la dernière semaine du mois courant.
          if($lNomJour != "dim."){
            // On compare date de début et date en cours de traitement (que l'on met dans lDateFin),
            // date qui correspond à la date de fin car le mois est terminé
            $lDateFin = strtotime("{$lAnneeCourante}-{$lMoisCourant}-{$lNumeroDuJour}");         
            // Même calcule du nombre de jours et on sauvegarde de la valeur pour la dernière semaine traitée du mois courant
            $lLargeurColonneSemaine[] = (($lDateFin-$lDateDebut)/86400)+1;

          }

        }
      } 
    }

    // La boucle pour dessiner les semaines calculées précédement, mois par mois.
    for ($lNumeroSemaine = 1; $lNumeroSemaine <= count($lLargeurColonneSemaine) ; $lNumeroSemaine++, $lNumSemMoisCourant++) {
      
      // Necessaire car un array est à l'indice 0 pour la première valeur
      // ce qui n'est pas le cas pour les numéros semaines et mois qui commencent toujours à 1
      $lNumeroSemaine--;
      echo "<td colspan='{$lLargeurColonneSemaine[$lNumeroSemaine]}'>S{$lNumSemMoisCourant}</td>"."\n";
      $lNumeroSemaine++;

    }

    // On efface l'array des semaines calculées (colspan) pour traiter le mois suivant au prochain tour de boucle
    unset($lLargeurColonneSemaine);

    echo "</tr>"."\n";
    
    echo "<tr>"."\n";

    // Necessaire car un array est à l'indice 0 pour la première valeur à extraire
    // ce qui n'est pas le cas pour les numéros semaines et mois qui commencent toujours à 1
    $lMoisCourant--;
    echo "<td rowspan='2' class='mois'> $lNomMois[$lMoisCourant] </td>"."\n";
    $lMoisCourant++;
    //On récupère ne nombre de jours du mois courant.
    $lNombreDeJourDuMois = cal_days_in_month(CAL_GREGORIAN,$lMoisCourant,$lAnneeCourante);
    //On parcours tous les jours du mois courant.
    for ($lNumeroDuJour = 1; $lNumeroDuJour <= $lNombreDeJourDuMois; $lNumeroDuJour++) {
        //On récupere le nom du jour.    
        $lNomJour = strftime("%a", strtotime("{$lAnneeCourante}-{$lMoisCourant}-{$lNumeroDuJour}"));

        if ($lNomJour == "sam." || $lNomJour == "dim."){
          //On affiche le nom du jour avec le numéro et on applique le style Weekend.
          echo "<td class='weekend'> {$lNomJour} {$lNumeroDuJour} </td>"."\n";
          //Ici on récupère dans une array tous les WeekEnds détectés, samedi et dimanche.
          $lJoursWeekEndDuMois[] = $lNumeroDuJour;

        }else{
          //On affiche le nom du jour avec le numéro et on applique le style jour
          echo "<td class='jour'> {$lNomJour} {$lNumeroDuJour} </td>"."\n";

        }
        
    }
    //On mémorise / sauvegarde dans lDernierJourDuMois le dernier numéro du jour du mois en cours de traitement
    //pour le reutiliser dans la boucle suivante.
    $lDernierJourDuMois = $lNumeroDuJour;
    //On dessine l'espace vide restant entre la dernière case du mois et la largeur max du tableau en partant du lDernierJourDuMois mémorisé.
    for ($lNumeroDuJour = $lDernierJourDuMois; $lNumeroDuJour <= NB_JOURS_MAX; $lNumeroDuJour++) { 

      echo "<td class='vide'>  </td>"."\n";

    }

    echo "</tr>"."\n";
    //On dessine les lignes projet
     for ($lNbLignes = 1; $lNbLignes <=1; $lNbLignes++){

      echo "<tr>"."\n";
      
      //On colorie les cases WeekEnd pour chaque ligne projet
      for ($lNumeroDuJour = 1; $lNumeroDuJour <= NB_JOURS_MAX; $lNumeroDuJour++) {
          
          foreach ($lJoursWeekEndDuMois as $lJourNonTravaille) {
            //On test si le lNumeroDuJour correspond à un jour non travaillé (Samedi ou Dimanche)
            if ($lNumeroDuJour == $lJourNonTravaille){
              
              $lJourTravaille = false;

            }
            
          }
          
          //Si un jour non travaillé est détecté (drapeau lJourTravaille à FAUX), alors on applique la class weekend.
          if ($lJourTravaille == true){

            
            include 'afficher_projet.php';

            
          } else{
              
            echo "<td class='weekend'>  </td>"."\n";

            // On reinitialise le drapeau à VRAI pour pouvoir redetecter un jour non travaillé
            // au prochain tour de boucle
            $lJourTravaille = true; 

          }
        

      }

      echo "</tr>"."\n";

    }

    //On efface l'array lJoursWeekEndDuMois pour le prochain tour de boucle 
    unset($lJoursWeekEndDuMois);

    echo "</table>"."\n";

  }

}



        
DessinerCalendrier();

?>
<form action="creer.php">
  <input type="submit" value="Créer" />
</form>
<form action="modifier.php">
  <input type="submit" value="Modifier" />
</form>
<form action="supprimer.php">
  <input type="submit" value="Supprimer" />
</form>
<?php
if($userinfo['id'] == $_SESSION['id'])
        {
            ?>

            <a href="deconnexion.php">Se déconnecter</a>
            <?php
        }
        ?>
<br><br><br>

</body>
</html>

<?php
}
else header('Location:connexion.php')
?>