<?php
include("./PageParts/databaseFunctions.php");
ConnectDatabase();
$loginStatus = CheckLogin();

if(!$loginStatus[0]) {
    $newAccountStatus = CheckNewAccountForm();
}
?>


<!DOCTYPE html>

<html lang = "fr">

<head>
    <meta charset = "utf-8">
    <link rel = "stylesheet" href = "./css/stylesheet.css">
    <title>Connexion</title>
    <link rel="shortcut icon" href="./favicon.ico">

    <?php include("./PageParts/windows.php");?>

</head>

<body>
<div class = "Container">
	<?php include("./PageParts/navigation.php")?>


	<div class = "MainContainer">

        <h2>Connexion à Twitturtle</h2>

        <?php if(!$loginStatus[0]) {?>
        <div class = "center">
            <button class = "connexion-button" onclick="openWindow('connexion')">Connexion</button>
            <button class = "inscription-button" onclick="openWindow('inscription')">Inscription</button>
        </div>

        <?php }
        elseif ($loginStatus[0]) {
            header("Location: index.php");
            exit();
        }
        if($loginStatus[2] != NULL) {
            ?>
            <script>
                // Ouverture automatique de la fenêtre erreur-connexion
                window.onload = function() {
                    openWindow('erreur-connexion');
                }
            </script>
        <?php
            }
        ?>

	</div>

    <div id="connexion" class="window-background">
        <div class="window-content">
            <span class="close" onclick="closeWindow('connexion')">&times;</span>
            <h2 class = "window-title">Connexion</h2>
            <?php include("./PageParts/loginForm.php"); ?>
        </div>
    </div>

    <div id="inscription" class="window-background">
        <div class="window-content">
            <span class="close" onclick="closeWindow('inscription')">&times;</span>
            <h2 class = "window-title">Inscription</h2>
            <?php include("./PageParts/newLoginForm.php"); ?>
        </div>
    </div>

    <div id="erreur-connexion" class="window-background">
        <div class="window-content">
            <div><h2 class = "window-title">Erreur de connexion</h2></div>
            <br>
            <div>
                <label>Nom d'utilisateur ou mot de passe incorrect</label>
            </div>
            <br><div>
                <label>Veuillez réessayer</label>
            </div>
            <br>
            <br>
            <button class = "form-button" onclick="closeWindow('erreur-connexion')">D'accord</button>
        </div>
    </div>

    <?php include("./PageParts/trends.php"); ?>
</div>
</body>

