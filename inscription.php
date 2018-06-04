<?php
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
    if(!empty($_POST['username']) AND !empty($_POST['password']) AND !empty($_POST['repeatpassword']) AND !empty($_POST['mail']))
    {
        $username = htmlspecialchars($_POST['username']);
        $password = sha1($_POST['password']);
        $repeatpassword = sha1($_POST['repeatpassword']);
        $mail = htmlspecialchars($_POST['mail']);
        $repeatmail = htmlspecialchars($_POST['repeatmail']);

        $usernamelenght = strlen($username);
        if ($usernamelenght <= 255)
        {
            if($mail==$repeatmail)
            {
                $reqmail = $bdd->prepare("SELECT * FROM users WHERE mail = ?");
                $reqmail->execute(array($mail));
                $mailexist = $reqmail->rowCount();
                if($mailexist == 0)
                {
                    $requser = $bdd->prepare("SELECT * FROM users WHERE nom = ?");
                    $requser->execute(array($username));
                    $userexist = $requser->rowCount();
                    if($userexist == 0)
                    {
                        if(filter_var($mail, FILTER_VALIDATE_EMAIL))
                        {
                            if($password==$repeatpassword)
                            {
                                $insert = $bdd->prepare("INSERT INTO users(nom, mdp, mail) VALUES (?, ?, ?)");
                                $insert->execute(array($username, $password, $mail));
                                $erreur = "Votre compte a bien été créé ! <a href='connexion.php'>Me connecter</a>";
                            }
                            else
                            {
                                $erreur = "Vos mots de passe de correspondent pas";
                            }
                        }
                        else
                        {
                            echo "Votre adresse mail n'est pas valide";
                        }
                    }
                }
                else
                {
                    echo "Adresse mail déjà utilisée";
                }
            }
            else
            {
                $erreur = "Vos adresses mails ne correspondent pas";
            }
        }
        else
        {
            $erreur = "Votre pseudo ne doit pas dépasser 255 caractères.";
        }
    }
    else
    {
        $erreur = "Tous les champs doivent être complétés.";
    }
}

?>

<html>
    <head>
        <title>Inscription</title>
        <meta charset="utf-8" />
    </head>
    <body>
    <form method="POST" action="">
        <table><tr>
            <td align="right">
                <label>Pseudo: </label>
            </td>
            <td>
                <input type="text" name="username">
            </td>
            </tr>
            <tr>
            <td align="right">
                <label>Mot de passe: </label>
            </td>
            <td>
                <input type="password" name="password">
            </td>
            </tr>
            <tr>
            <td align="right">
                <label>Répéter le mot de passe: </label>
            </td>
            <td>
                <input type="password" name="repeatpassword">
            </td>
            </tr>
            <tr>
            <td align="right">
                <label>Mail: </label>
            </td>
            <td>
                <input type="email" name="mail">
            </td>
            </tr>
            <tr>
            <td align="right">
                <label>Répéter le mail: </label>
            </td>
            <td>
                <input type="email" name="repeatmail">
            </td>
            </tr>
            <tr>
            <td></td>
            <td>
                <input type="submit" value="S'inscrire" name="submit">
            </td>
        </tr></table>
        
        </form>
<?php
if (isset($erreur))
{
    echo '<font color="red">' . $erreur . '</font>';
}
?>
    </body>
<html>   