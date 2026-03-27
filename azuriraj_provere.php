<?php include ('provera_analiticar.php'); ?>

<!DOCTYPE html>
<html lang="sr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="stil.css" />
    <title>Ažuriraj provere</title>
</head>

<?php include ('header.php'); ?>
<?php include ('nav_analiticar.php'); ?>

<?php
$veza = new mysqli("localhost", "root", "", "izbori");
$veza->set_charset("utf8");

$email_korisnika = $_SESSION['email'];
$upit_korisnika = "SELECT IDKorisnika FROM korisnici WHERE Email = '$email_korisnika' LIMIT 1";
$rez_korisnika = $veza->query($upit_korisnika);

$moj_id = 0;
if ($rez_korisnika && $rez_korisnika->num_rows > 0) {
    $red_korisnika = $rez_korisnika->fetch_assoc();
    $moj_id = (int)$red_korisnika['IDKorisnika'];
}

$upit = "
    SELECT 
        p.IDProvere,
        p.StatusProvere,
        p.BrojGlasaca,
        p.BrojIzaslih,
        p.BrojNevazecihListica,
        p.BrojGlasovaPoPartijama,
        p.Komentar,
        p.DatumProvere,
        r.IDRezultata,
        r.Naziv,
        r.Zapisnik,
        o.Naziv AS NazivOpstine
    FROM provere_analiticara p
    JOIN rezultati r ON p.IDRezultata = r.IDRezultata
    LEFT JOIN opstinegradovi o ON r.IDOpstinaGrad = o.IDOpstinaGrad
    WHERE p.IDAnaliticara = '$moj_id' AND p.StatusProvere = 'zavrseno'
    ORDER BY p.DatumProvere DESC, p.IDProvere DESC
";

$rez = $veza->query($upit);
?>

<div class="form-wrapper1">
    <div class="form-box1">
        <h2>Izborna mesta koja su proverena</h2>

        <div class="content">
            <?php if ($rez && $rez->num_rows > 0) { ?>
                <table border="1" cellpadding="8" cellspacing="0" style="width:100%; border-collapse:collapse;">
                    <tr>
                        <th>Izborno mesto</th>
                        <th>Grad/Opština</th>
                        <th>Broj glasača</th>
                        <th>Broj izašlih</th>
                        <th>Nevažeći</th>
                        <th>Status</th>
                        <th>Datum</th>
                        <th>Rezultat</th>
                        <th>Zapisnik</th>
                    </tr>

                    <?php while ($red = $rez->fetch_assoc()) { ?>
                        <tr>
                            <td><?php echo htmlspecialchars($red['Naziv']); ?></td>
                            <td><?php echo htmlspecialchars($red['NazivOpstine']); ?></td>
                            <td><?php echo (int)$red['BrojGlasaca']; ?></td>
                            <td><?php echo (int)$red['BrojIzaslih']; ?></td>
                            <td><?php echo (int)$red['BrojNevazecihListica']; ?></td>
                            <td><?php echo htmlspecialchars($red['StatusProvere']); ?></td>
                            <td><?php echo htmlspecialchars($red['DatumProvere']); ?></td>
                            <td>
                                <a href="kontrola_analiticara.php?id=<?php echo $red['IDProvere']; ?>">
                                    Prikaži rezultat
                                </a>
                            </td>
                            <td>
                                <?php if (!empty($red['Zapisnik']) && $red['Zapisnik'] !== 'No file') { ?>
                                    <a href="<?php echo htmlspecialchars($red['Zapisnik']); ?>" target="_blank">
                                        Otvori zapisnik
                                    </a>
                                <?php } else { ?>
                                    /
                                <?php } ?>
                            </td>
                        </tr>
                    <?php } ?>
                </table>
            <?php } else { ?>
                <p>Nema završenih provera.</p>
            <?php } ?>
        </div>
    </div>
</div>

<?php include ('footer.php'); ?>