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

if(isset($_POST['submit']))
{
    $pseudoconnect = htmlspecialchars($_POST['pseudoconnect']);
    $password = sha1($_POST['password']);

    if(!empty($pseudoconnect) AND !empty($password))
    {
        $requser = $bdd->prepare("SELECT * FROM users WHERE nom=? AND mdp=?");
        $requser->execute(array($pseudoconnect, $password));
        $userexist = $requser->rowCount();
        if($userexist==1)
        {
            $userinfo = $requser->fetch();
            $_SESSION['id'] = $userinfo['id'];
            $_SESSION['nom'] = $userinfo['nom'];
            $_SESSION['mail'] = $userinfo['mail'];
            header("Location: index.php?id=".$_SESSION['id']);
        }
        else
        {
            $erreur = "Mauvais nom ou mot de passe.";
        }
    }
    else
    {
        $erreur = "Remplissez tous les champs";
    }
}

?>

<html>
    <head>
        <title>Connexion</title>
        <meta charset="utf-8" />
    </head>
    <body>
        <div align="center">
        <h2>Connexion</h2>
        <form method="POST" action="">
            <input type="text" name="pseudoconnect" placeholder="Nom"/>
            <input type="password" name="password" placeholder="Mot de passe"/>
            <input type="submit" name="submit" value="Se connecter" />
        </form>
        <?php
        if (isset($erreur))
        {
            echo '<font color="red">' . $erreur . '</font>';
        }
        ?>
        <a href="inscription.php">S'inscrire</a>
        </div>
    </body>
<html>   
