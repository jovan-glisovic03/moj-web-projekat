<?php include ('provera_admin.php'); ?>

<!DOCTYPE html>
<html lang="sr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="stil.css">
    <title>Rezultati po gradovima i opštinama</title>
</head>

<?php include ('header.php'); ?>
<?php include ('nav_admin.php'); ?>

<?php
$veza = new mysqli("localhost", "root", "", "izbori");
$veza->set_charset("utf8");

$upit = "
    SELECT 
        r.IDOpstinaGrad,
        o.Naziv AS NazivOpstine,
        r.BrojGlasaca,
        r.BrojIzaslih,
        r.BrojNevazecihListica,
        r.BrojGlasovaPoPartijama
    FROM Rezultati r
    LEFT JOIN opstinegradovi o ON r.IDOpstinaGrad = o.IDOpstinaGrad
    WHERE r.IDOpstinaGrad IS NOT NULL
      AND r.BrojGlasaca > 0
    ORDER BY o.Naziv
";

$rez = $veza->query($upit);

$rezultati_po_opstinama = [];

while ($red = $rez->fetch_assoc()) {
    $id_opstine = $red['IDOpstinaGrad'];
    $naziv_opstine = $red['NazivOpstine'];

    if (!isset($rezultati_po_opstinama[$id_opstine])) {
        $rezultati_po_opstinama[$id_opstine] = [
            'NazivOpstine' => $naziv_opstine,
            'BrojGlasaca' => 0,
            'BrojIzaslih' => 0,
            'BrojNevazecihListica' => 0,
            'Partije' => []
        ];
    }

    $rezultati_po_opstinama[$id_opstine]['BrojGlasaca'] += (int)$red['BrojGlasaca'];
    $rezultati_po_opstinama[$id_opstine]['BrojIzaslih'] += (int)$red['BrojIzaslih'];
    $rezultati_po_opstinama[$id_opstine]['BrojNevazecihListica'] += (int)$red['BrojNevazecihListica'];

    $string_partija = $red['BrojGlasovaPoPartijama'];
    $parovi = explode(',', $string_partija);

    foreach ($parovi as $par) {
        $delovi = explode(':', $par);

        if (count($delovi) == 2) {
            $ime_partije = trim($delovi[0]);
            $broj_glasova = (int)trim($delovi[1]);

            if (!isset($rezultati_po_opstinama[$id_opstine]['Partije'][$ime_partije])) {
                $rezultati_po_opstinama[$id_opstine]['Partije'][$ime_partije] = 0;
            }

            $rezultati_po_opstinama[$id_opstine]['Partije'][$ime_partije] += $broj_glasova;
        }
    }
}
?>

<div class="form-wrapper1">
    <div class="form-box1">
        <h2>Rezultati po gradovima i opštinama</h2>

        <div class="content">
            <?php if (!empty($rezultati_po_opstinama)) { ?>
                <table border="1" cellpadding="8" cellspacing="0" style="width:100%; border-collapse:collapse;">
                    <tr>
                        <th>Grad/Opština</th>
                        <th>Broj glasača</th>
                        <th>Broj izašlih</th>
                        <th>Nevažeći</th>
                        <th>Glasovi po partijama</th>
                    </tr>

                    <?php foreach ($rezultati_po_opstinama as $opstina) { ?>
                        <tr>
                            <td><?php echo htmlspecialchars($opstina['NazivOpstine']); ?></td>
                            <td><?php echo (int)$opstina['BrojGlasaca']; ?></td>
                            <td><?php echo (int)$opstina['BrojIzaslih']; ?></td>
                            <td><?php echo (int)$opstina['BrojNevazecihListica']; ?></td>
                            <td>
                                <?php
                                $partije_tekst = [];
                                foreach ($opstina['Partije'] as $ime => $broj) {
                                    $partije_tekst[] = htmlspecialchars($ime) . ':' . $broj;
                                }
                                echo implode(', ', $partije_tekst);
                                ?>
                            </td>
                        </tr>
                    <?php } ?>
                </table>
            <?php } else { ?>
                <p>Nema rezultata po gradovima/opštinama.</p>
            <?php } ?>
        </div>
    </div>
</div>

<?php include ('footer.php'); ?>
</html>