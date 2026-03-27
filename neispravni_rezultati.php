<?php include ('provera_admin.php'); ?>

<!DOCTYPE html>
<html lang="sr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="stil.css">
    <title>Neispravni rezultati</title>
</head>

<?php include ('header.php'); ?>
<?php include ('nav_admin.php'); ?>

<?php
$veza = new mysqli("localhost", "root", "", "izbori");
$veza->set_charset("utf8");

$upit = "
    SELECT 
        r.IDRezultata,
        r.Naziv,
        r.BrojGlasaca,
        r.BrojIzaslih,
        r.BrojNevazecihListica,
        r.BrojGlasovaPoPartijama,
        r.StatusRezultata,
        o.Naziv AS NazivOpstine,
        k.Ime,
        k.Prezime
    FROM Rezultati r
    LEFT JOIN opstinegradovi o ON r.IDOpstinaGrad = o.IDOpstinaGrad
    LEFT JOIN Korisnici k ON r.IDKontrolora = k.IDKorisnika
    WHERE r.StatusRezultata IN ('sumnjiv', 'nevazeci')
    ORDER BY r.IDRezultata DESC
";

$rez = $veza->query($upit);
?>

<div class="form-wrapper1">
    <div class="form-box1">
        <h2>Neispravni rezultati</h2>

        <div class="content">
            <?php if ($rez && $rez->num_rows > 0) { ?>
                <table border="1" cellpadding="8" cellspacing="0" style="width:100%; border-collapse:collapse;">
                    <tr>
                        <th>Izborno mesto</th>
                        <th>Grad/Opština</th>
                        <th>Kontrolor</th>
                        <th>Broj glasača</th>
                        <th>Broj izašlih</th>
                        <th>Nevažeći</th>
                        <th>Glasovi po partijama</th>
                        <th>Status</th>
                    </tr>

                    <?php while($red = $rez->fetch_assoc()) { ?>
                        <tr>
                            <td><?php echo htmlspecialchars($red['Naziv']); ?></td>
                            <td><?php echo htmlspecialchars($red['NazivOpstine']); ?></td>
                            <td>
                                <?php
                                if (!empty($red['Ime'])) {
                                    echo htmlspecialchars($red['Ime'] . ' ' . $red['Prezime']);
                                } else {
                                    echo '/';
                                }
                                ?>
                            </td>
                            <td><?php echo (int)$red['BrojGlasaca']; ?></td>
                            <td><?php echo (int)$red['BrojIzaslih']; ?></td>
                            <td><?php echo (int)$red['BrojNevazecihListica']; ?></td>
                            <td><?php echo htmlspecialchars($red['BrojGlasovaPoPartijama']); ?></td>
                            <td><?php echo htmlspecialchars($red['StatusRezultata']); ?></td>
                        </tr>
                    <?php } ?>
                </table>
            <?php } else { ?>
                <p>Nema neispravnih rezultata.</p>
            <?php } ?>
        </div>
    </div>
</div>

<?php include ('footer.php'); ?>
</html>