
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
echo 'Quel projet modifier ?</br>';
echo '<form method="POST" action="modifier.php">';
while ($donnees = $req->fetch()){
    ?>    
    <p>        
        <input type="radio" name="modif" value="<?php echo $donnees['id'];?>" id="<?php echo $donnees['id'];?>" /> <?php echo $donnees['nom_projet'];?></br>
    </p>
    
<?php
}
?>

<input type="submit" name="submit" value="Modifier" />

</form>

<?php
if (isset($_POST['submit'])){
    $id = $_POST['modif'];
    
    $req = $bdd->query('SELECT * FROM projet WHERE id="'.$id.'"');
    
    $donnees = $req->fetch();
    
    ?>
        <form method="POST" action="modifier_projet.php">
            <fieldset>
                <legend>Modification de projet</legend>
                    <label for="">Nom du projet:</label>
                    <input type="text" name="nom" value="<?php echo $donnees['nom_projet'];?>" required/>
                    </br></br>
    
                    <label for="">Date de début:</label>
                    <input type="date" name="date_deb" value="<?php echo $donnees['date_deb'];?>"/>
                    </br></br>
    
                    <label for="">Date de fin:</label>
                    <input type="date" name="date_fin" value="<?php echo $donnees['date_fin'];?>"/>
                    </br></br>
    
                    <label for="">Commentaire:</label>
                    <input type="textarea" name="comm" value="<?php echo $donnees['commentaire'];?>" required/>
                    </br></br>
    
                    <input type="submit" name='chgmt' value="Modifier" />
            </fieldset>
        </form>
    
    <?php
        
        if (isset($_POST['chgmt'])){
            

            $nom = $_POST['nom'];
            $datedeb = $_POST['date_deb'];
            $datefin = $_POST['date_fin'];
            $comm = $_POST['comm'];
    
    
            $req = $bdd->query('UPDATE projet SET nom_projet = "'.$nom.'" , date_deb = "'.$datedeb.'", date_fin = "'.$datefin.'", commentaire = "'.$comm.'" WHERE id="'.$id.'"');
            
            header("Location: index.php");
        }
    }
    ?>







</body>
</head>
</html>