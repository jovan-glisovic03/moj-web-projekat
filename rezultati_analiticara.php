<?php include ('provera_admin.php'); ?>

<!DOCTYPE html>
<html lang="sr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="stil.css" />
    <title>Rezultati analitičara</title>
</head>

<?php include ('header.php'); ?>
<?php include ('nav_admin.php'); ?>

<?php
$veza = new mysqli("localhost", "root", "", "izbori");
$veza->set_charset("utf8");

$upit = "
    SELECT 
        p.IDProvere,
        p.BrojGlasaca,
        p.BrojIzaslih,
        p.BrojNevazecihListica,
        p.BrojGlasovaPoPartijama,
        p.Komentar,
        p.StatusProvere,
        p.DatumProvere,
        r.Naziv AS NazivMesta,
        o.Naziv AS NazivOpstine,
        k.Ime,
        k.Prezime
    FROM provere_analiticara p
    LEFT JOIN Rezultati r ON p.IDRezultata = r.IDRezultata
    LEFT JOIN opstinegradovi o ON r.IDOpstinaGrad = o.IDOpstinaGrad
    LEFT JOIN Korisnici k ON p.IDAnaliticara = k.IDKorisnika
    ORDER BY p.IDProvere DESC
";

$rez = $veza->query($upit);
?>

<div class="form-wrapper1">
    <div class="form-box1">
        <h2>Rezultati analitičara</h2>

        <div class="content">
            <?php if ($rez && $rez->num_rows > 0) { ?>
                <table border="1" cellpadding="8" cellspacing="0" style="width:100%; border-collapse:collapse;">
                    <tr>
                        <th>Analitičar</th>
                        <th>Izborno mesto</th>
                        <th>Grad/Opština</th>
                        <th>Broj glasača</th>
                        <th>Broj izašlih</th>
                        <th>Nevažeći</th>
                        <th>Glasovi po partijama</th>
                        <th>Status provere</th>
                        <th>Datum</th>
                    </tr>

                    <?php while($red = $rez->fetch_assoc()) { ?>
                        <tr>
                            <td>
                                <?php
                                if (!empty($red['Ime'])) {
                                    echo htmlspecialchars($red['Ime'] . ' ' . $red['Prezime']);
                                } else {
                                    echo '/';
                                }
                                ?>
                            </td>
                            <td><?php echo htmlspecialchars($red['NazivMesta']); ?></td>
                            <td><?php echo htmlspecialchars($red['NazivOpstine']); ?></td>
                            <td><?php echo (int)$red['BrojGlasaca']; ?></td>
                            <td><?php echo (int)$red['BrojIzaslih']; ?></td>
                            <td><?php echo (int)$red['BrojNevazecihListica']; ?></td>
                            <td><?php echo htmlspecialchars($red['BrojGlasovaPoPartijama']); ?></td>
                            <td><?php echo htmlspecialchars($red['StatusProvere']); ?></td>
                            <td><?php echo htmlspecialchars($red['DatumProvere']); ?></td>
                        </tr>
                    <?php } ?>
                </table>
            <?php } else { ?>
                <p>Nema rezultata analitičara.</p>
            <?php } ?>
        </div>
    </div>
</div>

<?php include ('footer.php'); ?>
</html>