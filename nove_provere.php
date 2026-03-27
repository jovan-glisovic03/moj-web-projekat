<?php include ('provera_analiticar.php') ?>

<!DOCTYPE html>
<html lang="sr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="stil.css" />
    <title>Nove provere</title>
</head>

<?php include ('header.php'); ?>
<?php include ('nav_analiticar.php'); ?>

<?php
$veza = new mysqli("localhost", "root", "", "izbori");
$veza->set_charset("utf8");

$email_korisnika = $_SESSION['email'];
$upit_korisnika = "SELECT IDKorisnika FROM korisnici WHERE Email = '$email_korisnika'";
$podaci_korisnika = $veza->query($upit_korisnika);
$moj_id = 0;

if ($podaci_korisnika && $red_korisnika = $podaci_korisnika->fetch_assoc()) {
    $moj_id = $red_korisnika['IDKorisnika'];
}

$upit_provere = "
    SELECT 
        p.IDProvere,
        p.IDRezultata,
        p.StatusProvere,
        r.Naziv,
        r.BrojGlasaca,
        r.BrojIzaslih,
        r.BrojNevazecihListica,
        r.BrojGlasovaPoPartijama,
        r.Zapisnik
    FROM provere_analiticara p
    JOIN rezultati r ON p.IDRezultata = r.IDRezultata
    WHERE p.IDAnaliticara = '$moj_id'
    ORDER BY p.IDProvere ASC
";
$rez_provere = $veza->query($upit_provere);
?>

<div class="form-wrapper1">
    <div class="form-box1">
        <h2>Nova izborna mesta za proveru</h2>

        <div class="panel-container">
            <div class="panel left">
                <h3>Dodeljene provere</h3>
                <ul class="partija-list">
                    <?php if ($rez_provere && $rez_provere->num_rows > 0) { ?>
                        <?php while($red = $rez_provere->fetch_assoc()) { ?>
                            <li style="margin-bottom: 15px;">
                                <strong><?php echo htmlspecialchars($red['Naziv']); ?></strong><br>
                                Broj glasača: <?php echo $red['BrojGlasaca']; ?><br>
                                Broj izašlih: <?php echo $red['BrojIzaslih']; ?><br>
                                Broj nevažećih: <?php echo $red['BrojNevazecihListica']; ?><br>
                                Status: <?php echo htmlspecialchars($red['StatusProvere']); ?><br>
                                <a href="kontrola_analiticara.php?id=<?php echo $red['IDProvere']; ?>">Proveri rezultat</a>
                            </li>
                        <?php } ?>
                    <?php } else { ?>
                        <li>Nema dodeljenih provera.</li>
                    <?php } ?>
                </ul>
            </div>
        </div>
    </div>
</div>

<?php include ('footer.php'); ?>