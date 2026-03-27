
<?php include ('provera_admin.php') ?>


<!DOCTYPE html>
<html lang="sr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="stil.css">
    <title>Ažuriraj kontrolore</title>
</head>
<?php include ('header.php'); ?>

    <?php include ('nav_admin.php'); ?>

    <div class="form-wrapper1">
        <div class="form-box1">
            <h2>Ažuriraj kontrolora</h2>

            <div class="content">

                <ul class="partija-list">
					<?php
                    $veza = new mysqli("localhost", "root", "", "izbori");
					$upit="SELECT IDKorisnika, Ime, Prezime, Tip FROM Korisnici WHERE Tip='2'";
					$rez=$veza->query($upit);
					while ($red=$rez->fetch_assoc()){
					$id = $red['IDKorisnika'];
					$imePrezime = $red['Ime'] . " " . $red['Prezime'];
			
					?>
					<li>
                <span><?php echo $imePrezime; ?></span>
                <div class="buttons-row">
					<a href="azuriraj_kon.php?id=<?php echo $id; ?>"><button>Ažuriraj</button></a>
					<a href="obrisi_kon.php?id=<?php echo $id; ?>" onclick="return confirm('Sigurno?')"><button>Obriši</button></a>
                </div>
            </li>
			<?php } ?>
                </ul>

            </div>
        </div>
    </div>

    <?php include ('footer.php'); ?>