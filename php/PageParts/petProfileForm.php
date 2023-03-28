<?php
require_once('./PageParts/databaseFunctions.php');


if (!function_exists('displayPetProfile')) {
    function displayPetProfile($conn, $username) {

        ConnectDatabase();
        $loginStatus = isLogged();

        if (isset($_POST['modification-pet-profile'])) {
            motificationProfile('animal');
        }

        if(isset($_POST['adopt'])) {
            adoptAnimal($username, $_COOKIE['username'], $conn);
        }

        /* DUPLICATED!!!! */
        if(isset($_POST['like']) && $loginStatus) likeMessage($_POST['like']);

        if(isset($_POST["submit"])) {
            include("./PageParts/sendingMessage.php");
            sendMessage($_POST["submit"]);
        }

        $username = SecurizeString_ForSQL($username);
        $query = "SELECT * FROM animal WHERE id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if($result && $result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $nom = $row["nom"];
            ?>
            <div style = "display : flex">
                <img class = "profile-picture-pet" src="data:image/jpeg;base64,<?php echo base64_encode(loadAvatar($username)); ?>"  alt="Photo de profil">
                <div style = "text-align: center">
                    <h4 style = "margin-bottom: 0.4vw">Maître</h4>
                    <a href = "./profile.php?username=<?php echo $row['maitre_username']?>"><img class = "profile-picture" style ="width:4.5vw; height: 4.5vw" src="data:image/jpeg;base64,<?php echo base64_encode(loadAvatar($row['maitre_username'])); ?>"  alt="Photo de profil maitre"></a>
                </div>
            </div>
             <?php
            echo "<h3 class = 'name-profile'>" . $nom . "</h3>";

            if($loginStatus) {
                if($_COOKIE['username'] == $row['maitre_username']) {?>
                    <button class = "button-modify-profile" onclick="openWindow('modification-pet-profile')">Editer le profil</button>
                    <?php
                }
                elseif (!checkFollow($username, 'animal')) { ?>
                    <form action="" method="post" class = "button-follow">
                        <button type = "submit" name="follow" class = "button-modify-profile">Suivre</button>
                    </form>

                <?php
                }
                else { ?>
                    <form action="" method="post" class = "button-follow">
                        <button type = "submit" name="follow" class = "button-following">Suivi</button>
                    </form>
                <?php }
                if($row['adopter'] == 1) {?>
                        <div style = "align-self: flex-end;">
                            <form action = "./profile.php?username=<?php echo $username?>" method = "post">
                                <input type = "submit" class = "add-pet" name = "adopt" value = "Adopter">
                            </form>
                        </div>
                    <?php
                }
            }


            echo "<h4>" ."@" . $username . "</h4>";
            if($row["caracteristiques"] != ("Bio" && null)) {
                echo'<div class = "bio"><p>' . $row["caracteristiques"].'</p></div>';
            }
            ?>
            <div style = "display: flex">
                <h4 style = "color: #3a3a3a"><?php echo numFollowers($username, "animal")." abonnés" ?></h4>
            </div>
<?php
        }
    }
}