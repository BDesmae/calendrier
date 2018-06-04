<html>
<head>
<body>
<?php
try
{
    $bdd = new PDO('mysql:host=localhost;dbname=calendrier;charset=utf8', 'root', 'root');
}
catch (Exception $e)
{
    die('Erreur : ' . $e->getMessage());
}


$req = $bdd->query('SELECT * FROM projet');
echo 'Quel projet supprimer ?</br>';
echo '<form method="POST" action="supprimer_projet.php">';
while ($donnees = $req->fetch()){
    ?>    
    <p>        
        <input type="radio" name="suppr" value="<?php echo $donnees['id'];?>" id="<?php echo $donnees['id'];?>" /> <?php echo $donnees['nom_projet'];?></br>
    </p>
    
<?php
}
?>

<input type="submit" name="submit" value="Supprimer" />

</form>

<?php


?>
</body>
</head>
</html>