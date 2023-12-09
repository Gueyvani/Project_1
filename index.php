<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Maquette</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        header {
            background-color:chartreuse;
            color: seagreen;
            padding: 10px;
            text-align: center;
        }

        section {
            margin: 20px;
        }

        form {
            width: 50%;
            margin: 0 auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        table {
            width: 50%;
            margin: 20px auto;
            border-collapse: collapse;
            background-color: #fff;
        }

        table, th, td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: center;
        }

        th {
            background-color: rebeccapurple;
            color: #ffd;
        }

        a {
            text-decoration: none;
            color: #213;
        }

        a:hover {
            color: #ff4500;
        }

        footer {
            background-color: darkorange;
            color: #fff;
            text-align: center;
            padding: 10px;
            position: fixed;
            bottom: 0;
            width: 100%;
        }
    </style>
</head>
<body>

<header>
    <h1>Gestion des Joueurs</h1>
</header>

<section>
    <form method="post" action="index.php">
        <!-- ... Votre formulaire d'édition ... -->
    </form>

    <hr>

    <form method="post" action="index.php">
        <!-- ... Votre formulaire d'ajout ... -->
    </form>


</section>

<footer>
    &copy; 2023 Votre Société
</footer>

</body>
</html>


<?php
$con = mysqli_connect("localhost", "root", "", "ffrim");

// EDITER LE JOUEUR
$editer = isset($_GET['e']) ? $_GET['e'] : null;

if (!empty($editer)) {
    $edition = mysqli_query($con, "SELECT * FROM joueur WHERE id='$editer'");
    $edit = mysqli_fetch_array($edition);
}

if (isset($_POST['modifier'])) {
    $xx = $_POST['id'];
    $n = $_POST['nom'];
    $p = $_POST['prenom'];
    $date = $_POST['datenais'];

    if ($date != "") {
        $modification = mysqli_query($con, "UPDATE joueur SET nom='$n',prenom='$p',datenais='$date' WHERE id='$xx'");
    } else {
        $modification = mysqli_query($con, "UPDATE joueur SET nom='$n',prenom='$p' WHERE id='$xx'");
    }
}

// Requete Insert
if (isset($_POST['aj'])) {
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $datenais = $_POST['datenais'];

    $inser = mysqli_query($con, "INSERT INTO joueur (nom,prenom,datenais) values ('$nom','$prenom','$datenais')");
}
?>

<form method="post" action="index.php">
    <table align="center" width="50%">
        <tr>
            <td></td>
            <td><input required type="hidden" name="id" value="<?php echo isset($edit['id']) ? $edit['id'] : ''; ?>"></td>
        </tr>
        <tr>
            <td>NOM:</td>
            <td><input required type="text" name="nom" value="<?php echo @$edit['nom']; ?>"></td>
        </tr>
        <tr>
            <td>PRENOM:</td>
            <td><input required type="text" name="prenom" value="<?php echo @$edit['prenom']; ?>"></td>
        </tr>
        <tr>
            <td>DATE DE NAISSANCE:</td>
            <td><input type="date" name="datenais" value="<?php echo @$edit['datenais']; ?>"></td>
        </tr>
        <tr>
            <td></td>
            <td><input type="submit" name="modifier" value="MODIFIER"></td>
        </tr>
    </table>
</form>

<hr>
<hr>

<form method="post" action="index.php">
    <table align="center" width="50%">
        <tr>
            <td>NOM:</td>
            <td><input required type="text" name="nom" value=""></td>
        </tr>
        <tr>
            <td>PRENOM:</td>
            <td><input required type="text" name="prenom" value=""></td>
        </tr>
        <tr>
            <td>DATE DE NAISSANCE:</td>
            <td><input required type="date" name="datenais" value=""></td>
        </tr>
        <tr>
            <td></td>
            <td><input type="submit" name="aj" value="AJOUTER"></td>
        </tr>
    </table>
</form>

<table width="50%" align="center" border="1">
    <tr>
        <th>ID</th><th>NOM & PRENOM</th><th>DATE DE NAISSANCE</th><th>OPERATION</th>
    </tr>

    <?php
    $affiche = mysqli_query($con, "SELECT *, DATE_FORMAT(datenais, '%d/%m/%Y') AS date_formatee FROM joueur");
    $m = 0;

    while ($r = mysqli_fetch_array($affiche)) {
    $m += 1;
    ?>
    <tr>
        <td><?php echo $m; ?></td>
        <td><?php echo strtoupper($r['prenom'] . " " . $r['nom']); ?></td>
        <td><?php echo $r['date_formatee']; ?></td>
        <td><a href="index.php?e=<?php echo $r['id'] ?>" class="btn btn-warning">Editer</a>
        <a href="index.php?id=<?php echo $r['id'] ?>" class="btn btn-danger">Supprimer</a></td>
    </tr>

    <?php } ?>
</table>

<?php
// Vérifier si l'ID à supprimer est défini dans l'URL
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id_a_supprimer = $_GET['id'];

    // Requête de suppression
    $suppression_query = "DELETE FROM joueur WHERE id = $id_a_supprimer";

    // Exécution de la requête
    if (mysqli_query($con, $suppression_query)) {
        echo "Enregistrement supprimé avec succès.";
    } else {
        echo "Erreur lors de la suppression de l'enregistrement : " . mysqli_error($con);
    }
} else {
    echo "";
}

// Fermer la connexion à la base de données
mysqli_close($con);
?>
