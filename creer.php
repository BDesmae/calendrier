<html>
<head>
<body>
<form method="POST" action="creer.php">
<fieldset>
<legend>Nouveau projet</legend>
    <label for="">Nom du projet:</label>
    <input type="text" name="nom" required/>
    </br></br>

    <label for="">Date de début:</label>
    <input type="date" name="date_deb"/>
    </br></br>

    <label for="">Date de fin:</label>
    <input type="date" name="date_fin"/>
    </br></br>

    <label for="">Commentaire:</label>
    <input type="textarea" name="comm" required/>
    </br></br>

    <input type="submit" name='submit' value="Créer" />
</fieldset>

    
    
</form>

<?php
if (isset($_POST['submit'])){
try
{
    $bdd = new PDO('mysql:host=localhost;dbname=calendrier;charset=utf8', 'root', 'root');
}
catch (Exception $e)
{
    die('Erreur : ' . $e->getMessage());
}


    if (isset($_POST['nom']) AND isset($_POST['date_deb']) AND isset($_POST['date_fin']) AND isset($_POST['comm'])){

        $nom=$_POST['nom'];
        $deb=$_POST['date_deb'];
        $fin=$_POST['date_fin'];
        $com=$_POST['comm'];
        
        $req = $bdd->prepare('
                INSERT INTO projet (nom_projet, date_deb, date_fin, commentaire) 
                VALUES (:nom, :deb, :fin, :comm)');
        $req->execute(array('nom'=>$nom, 'deb'=>$deb, 'fin'=>$fin, 'comm'=>$com));

    }
    header("Location: index.php");
}




?>
</body>
</head>
</html>