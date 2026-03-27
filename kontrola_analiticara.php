<?php include ('provera_analiticar.php'); ?>

<!DOCTYPE html>
<html lang="sr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="stil.css" />
    <title>Kontrola rezultata</title>
</head>

<?php include ('header.php'); ?>
<?php include ('nav_analiticar.php'); ?>

<?php
$veza = new mysqli("localhost", "root", "", "izbori");
$veza->set_charset("utf8");

$id_provere = isset($_GET['id']) ? (int)$_GET['id'] : 0;

$upit = "
    SELECT 
        p.IDProvere,
        p.IDRezultata,
        p.IDAnaliticara,
        p.BrojGlasaca,
        p.BrojIzaslih,
        p.BrojNevazecihListica,
        p.BrojGlasovaPoPartijama,
        p.Komentar,
        p.StatusProvere,
        r.Naziv AS NazivMesta,
        o.Naziv AS NazivOpstine,
        r.Zapisnik
    FROM provere_analiticara p
    JOIN rezultati r ON p.IDRezultata = r.IDRezultata
    LEFT JOIN opstinegradovi o ON r.IDOpstinaGrad = o.IDOpstinaGrad
    WHERE p.IDProvere = '$id_provere'
";

$rez = $veza->query($upit);
$podaci = $rez ? $rez->fetch_assoc() : null;

if (!$podaci) {
    echo "<div class='form-wrapper1'><div class='form-box1'><h2>Greška</h2><p>Provera nije pronađena.</p></div></div>";
    include ('footer.php');
    exit();
}

if (isset($_POST['sacuvaj'])) {
    $broj_glasaca = (int)$_POST['broj_glasaca'];
    $broj_izaslih = (int)$_POST['broj_izaslih'];
    $nevazeci = (int)$_POST['nevazeci'];
    $glasovi_partije = trim($_POST['glasovi']);
    $komentar = trim($_POST['komentar']);

    $upit_update = "
        UPDATE provere_analiticara SET
            BrojGlasaca = '$broj_glasaca',
            BrojIzaslih = '$broj_izaslih',
            BrojNevazecihListica = '$nevazeci',
            BrojGlasovaPoPartijama = '$glasovi_partije',
            Komentar = '$komentar',
            StatusProvere = 'zavrseno'
        WHERE IDProvere = '$id_provere'
    ";

    if ($veza->query($upit_update)) {

        $id_rezultata = $podaci['IDRezultata'];

        $upit_provere = "
            SELECT BrojGlasaca, BrojIzaslih, BrojNevazecihListica, BrojGlasovaPoPartijama
            FROM provere_analiticara
            WHERE IDRezultata = '$id_rezultata' AND StatusProvere = 'zavrseno'
        ";

        $rez_provere = $veza->query($upit_provere);

        $kombinacije = [];
        $broj_zavrsenih = 0;

        while ($r = $rez_provere->fetch_assoc()) {
            $broj_zavrsenih++;

            $kljuc = $r['BrojGlasaca'] . "-" .
                     $r['BrojIzaslih'] . "-" .
                     $r['BrojNevazecihListica'] . "-" .
                     $r['BrojGlasovaPoPartijama'];

            if (!isset($kombinacije[$kljuc])) {
                $kombinacije[$kljuc] = 0;
            }

            $kombinacije[$kljuc]++;
        }

        $potvrdjeno = false;

        foreach ($kombinacije as $broj_istih) {
            if ($broj_istih >= 2) {
                $potvrdjeno = true;
                break;
            }
        }

        $status_rezultata = 'na_proveri';
$vecinska_provera = null;
$sve_provere = [];

// ponovo uzimamo završene provere da ih sačuvamo
$rez_provere2 = $veza->query("
    SELECT BrojGlasaca, BrojIzaslih, BrojNevazecihListica, BrojGlasovaPoPartijama
    FROM provere_analiticara
    WHERE IDRezultata = '$id_rezultata' AND StatusProvere = 'zavrseno'
");

$kombinacije_sa_podacima = [];
$broj_zavrsenih = 0;

while ($r = $rez_provere2->fetch_assoc()) {
    $broj_zavrsenih++;
    $sve_provere[] = $r;

    $kljuc = $r['BrojGlasaca'] . "|" .
             $r['BrojIzaslih'] . "|" .
             $r['BrojNevazecihListica'] . "|" .
             $r['BrojGlasovaPoPartijama'];

    if (!isset($kombinacije_sa_podacima[$kljuc])) {
        $kombinacije_sa_podacima[$kljuc] = 0;
    }

    $kombinacije_sa_podacima[$kljuc]++;
}

// prvo određujemo osnovni status
foreach ($kombinacije_sa_podacima as $kljuc => $broj_istih) {
    if ($broj_istih >= 2) {
        $status_rezultata = 'potvrdjen';

        $delovi = explode("|", $kljuc);
        $vecinska_provera = [
            'BrojGlasaca' => $delovi[0],
            'BrojIzaslih' => $delovi[1],
            'BrojNevazecihListica' => $delovi[2],
            'BrojGlasovaPoPartijama' => $delovi[3]
        ];
        break;
    }
}

if ($status_rezultata !== 'potvrdjen' && $broj_zavrsenih == 3) {
    $status_rezultata = 'sumnjiv';
}

// pomoćna funkcija za zbir partija
function zbir_partija($partije_string) {
    $zbir = 0;

    if (!empty($partije_string)) {
        $partije_niz = explode(',', $partije_string);

        foreach ($partije_niz as $p) {
            $delovi = explode(':', $p);
            if (isset($delovi[1])) {
                $zbir += (int)trim($delovi[1]);
            }
        }
    }

    return $zbir;
}

// sada proveravamo NEVAZEĆI iz analitičarskih unosa
if ($status_rezultata == 'potvrdjen' && $vecinska_provera !== null) {
    $zbir = zbir_partija($vecinska_provera['BrojGlasovaPoPartijama']);
    $izaslih = (int)$vecinska_provera['BrojIzaslih'];
    $nevazeci = (int)$vecinska_provera['BrojNevazecihListica'];

    if (($zbir + $nevazeci) != $izaslih) {
        $status_rezultata = 'nevazeci';
    }
}

if ($status_rezultata == 'sumnjiv') {
    foreach ($sve_provere as $provera) {
        $zbir = zbir_partija($provera['BrojGlasovaPoPartijama']);
        $izaslih = (int)$provera['BrojIzaslih'];
        $nevazeci = (int)$provera['BrojNevazecihListica'];

        if (($zbir + $nevazeci) != $izaslih) {
            $status_rezultata = 'nevazeci';
            break;
        }
    }
}

$veza->query("UPDATE rezultati SET StatusRezultata = '$status_rezultata' WHERE IDRezultata = '$id_rezultata'");

        header("Location: nove_provere.php");
        exit();
    }
}
?>

<div class="form-wrapper1">
    <div class="form-box1">
        <h2>Kontrola rezultata</h2>

        <div class="content">
            <p><strong>Izborno mesto:</strong> <?php echo htmlspecialchars($podaci['NazivMesta']); ?></p>
            <p><strong>Grad/Opština:</strong> <?php echo htmlspecialchars($podaci['NazivOpstine']); ?></p>
            <p><strong>Status provere:</strong> <?php echo htmlspecialchars($podaci['StatusProvere']); ?></p>

            <?php if (!empty($podaci['Zapisnik']) && $podaci['Zapisnik'] !== 'No file') { ?>
                <p><a href="<?php echo htmlspecialchars($podaci['Zapisnik']); ?>" target="_blank">Otvori zapisnik</a></p>
            <?php } ?>

            <form method="post">
                <label>Ukupan broj glasača:</label><br>
                <input type="number" name="broj_glasaca" value="<?php echo $podaci['BrojGlasaca']; ?>" required><br><br>

                <label>Ukupan broj izašlih:</label><br>
                <input type="number" name="broj_izaslih" value="<?php echo $podaci['BrojIzaslih']; ?>" required><br><br>

                <label>Broj nevažećih listića:</label><br>
                <input type="number" name="nevazeci" value="<?php echo $podaci['BrojNevazecihListica']; ?>" required><br><br>

                <label>Broj glasova po partijama:</label><br>
                <textarea name="glasovi" rows="4" cols="60" required><?php echo htmlspecialchars($podaci['BrojGlasovaPoPartijama']); ?></textarea><br><br>

                <label>Komentar:</label><br>
                <textarea name="komentar" rows="4" cols="60"><?php echo htmlspecialchars($podaci['Komentar']); ?></textarea><br><br>

                <button type="submit" name="sacuvaj">Sačuvaj proveru</button>
            </form>
        </div>
    </div>
</div>

<?php include ('footer.php'); ?>