<?php include ('provera_admin.php'); ?>

<!DOCTYPE html>
<html lang="sr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="stil.css" />
    <title>Kontrola analitičara</title>
</head>
<body>
<div class="page-container">

    <?php include ('header.php'); ?>
    <?php include ('nav_admin.php'); ?>

    <?php
    $veza = new mysqli("localhost", "root", "", "izbori");
    $veza->set_charset("utf8");

    // Brisanje analitičara
    if (isset($_GET['obrisi'])) {
        $id_brisanje = (int)$_GET['obrisi'];

        $veza->query("DELETE FROM provere_analiticara WHERE IDAnaliticara = '$id_brisanje'");
        $veza->query("DELETE FROM korisnici WHERE IDKorisnika = '$id_brisanje' AND Tip = 1");

        header("Location: kontrola_analiticara_admin.php");
        exit();
    }

    $izabrani_analiticar = isset($_GET['id']) ? (int)$_GET['id'] : 0;

    // Analitičari koji su grešili:
    // uzimamo analitičare koji imaju završene provere nad rezultatima koji su završili kao sumnjiv ili nevazeci
    $upit_analiticari = "
        SELECT DISTINCT k.IDKorisnika, k.Ime, k.Prezime
        FROM korisnici k
        JOIN provere_analiticara p ON k.IDKorisnika = p.IDAnaliticara
        JOIN rezultati r ON p.IDRezultata = r.IDRezultata
        WHERE k.Tip = 1
          AND p.StatusProvere = 'zavrseno'
          AND r.StatusRezultata IN ('sumnjiv', 'nevazeci')
        ORDER BY k.Ime, k.Prezime
    ";

    $rez_analiticari = $veza->query($upit_analiticari);
    ?>

    <div class="form-wrapper1">
        <div class="form-box1">
            <h2>Kontrola analitičara</h2>
            <div class="content">

                <p><strong>Analitičari sa greškama</strong></p>

                <ul class="partija-list">
                    <?php if ($rez_analiticari && $rez_analiticari->num_rows > 0) { ?>
                        <?php while ($red = $rez_analiticari->fetch_assoc()) { ?>
                            <li>
                                <span>
                                    <a href="kontrola_analiticara_admin.php?id=<?php echo $red['IDKorisnika']; ?>">
                                        <?php echo htmlspecialchars($red['Ime'] . ' ' . $red['Prezime']); ?>
                                    </a>
                                </span>

                                <div class="buttons-row">
                                    <a href="kontrola_analiticara_admin.php?obrisi=<?php echo $red['IDKorisnika']; ?>"
                                       onclick="return confirm('Obrisati analitičara?')">
                                        <button type="button">Obriši</button>
                                    </a>
                                </div>
                            </li>
                        <?php } ?>
                    <?php } else { ?>
                        <li>Nema analitičara sa greškama.</li>
                    <?php } ?>
                </ul>

                <div class="analiza-rezultata">
                    <?php
                    if ($izabrani_analiticar > 0) {
                        $upit_unosi = "
                            SELECT 
                                p.IDProvere,
                                r.Naziv AS NazivMesta,
                                o.Naziv AS NazivOpstine,
                                p.BrojGlasaca,
                                p.BrojIzaslih,
                                p.BrojNevazecihListica,
                                p.BrojGlasovaPoPartijama,
                                p.Komentar,
                                r.StatusRezultata
                            FROM provere_analiticara p
                            JOIN rezultati r ON p.IDRezultata = r.IDRezultata
                            LEFT JOIN opstinegradovi o ON r.IDOpstinaGrad = o.IDOpstinaGrad
                            WHERE p.IDAnaliticara = '$izabrani_analiticar'
                             AND p.StatusProvere = 'zavrseno'
                             AND r.StatusRezultata IN ('sumnjiv', 'nevazeci')
                            ORDER BY p.IDProvere DESC
";

                        $rez_unosi = $veza->query($upit_unosi);

                        if ($rez_unosi && $rez_unosi->num_rows > 0) {
                            echo "<div class='rez' style='display:block;'>";
                            echo "<h3>Pogrešni unosi izabranog analitičara</h3>";

                            while ($unos = $rez_unosi->fetch_assoc()) {
                                echo "<div style='margin-bottom:20px; padding:10px; border:1px solid #ccc; border-radius:8px;'>";
                                echo "<p><strong>Izborno mesto:</strong> " . htmlspecialchars($unos['NazivMesta']) . "</p>";
                                echo "<p><strong>Grad/Opština:</strong> " . htmlspecialchars($unos['NazivOpstine']) . "</p>";
                                echo "<p><strong>Broj glasača:</strong> " . $unos['BrojGlasaca'] . "</p>";
                                echo "<p><strong>Broj izašlih:</strong> " . $unos['BrojIzaslih'] . "</p>";
                                echo "<p><strong>Nevažeći listići:</strong> " . $unos['BrojNevazecihListica'] . "</p>";
                                echo "<p><strong>Glasovi po partijama:</strong> " . htmlspecialchars($unos['BrojGlasovaPoPartijama']) . "</p>";
                                echo "<p><strong>Komentar:</strong> " . htmlspecialchars($unos['Komentar']) . "</p>";
                                echo "<p><strong>Status rezultata:</strong> " . htmlspecialchars($unos['StatusRezultata']) . "</p>";
                                echo "</div>";
                            }

                            echo "</div>";
                        } else {
                            echo "<div class='rez' style='display:block;'><p>Nema pogrešnih unosa za ovog analitičara.</p></div>";
                        }
                    }
                    ?>
                </div>

            </div>
        </div>
    </div>

    <?php include ('footer.php'); ?>

</div>
</body>
</html>