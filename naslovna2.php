<?php
$veza = new mysqli("localhost", "root", "", "izbori");
$veza->set_charset("utf8");

$upit1 = "
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

$rez1 = $veza->query($upit1);

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

$upit3 = "
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
    WHERE r.StatusRezultata = 'potvrdjen'
    ORDER BY r.IDRezultata DESC
";

$rez3 = $veza->query($upit3);
?>




<div class="wrapper">

    <section class="box">
        <h2>Izborna mesta sa greškom</h2>
        <div class="content">
            <ul>
				<?php while($red1=$rez1->fetch_assoc()){?>
					
                <li><?php echo $red1['NazivOpstine'] ?> - <?php echo $red1['Naziv']  ?> - <?php echo $red1['StatusRezultata']  ?> - <?php echo $red1['BrojGlasovaPoPartijama']  ?></li>
                <?php } ?>
            </ul>
        </div>
    </section>

    <section class="box">
        <h2>Rezultati po gradovima i opštinama</h2>
        <div class="content">
            <ul>
                <?php foreach ($rezultati_po_opstinama as $opstina) { ?>
                            <li><?php echo $opstina['NazivOpstine'] ?> - <?php
                                $partije_tekst = [];
                                foreach ($opstina['Partije'] as $ime => $broj) {
                                    $partije_tekst[] = htmlspecialchars($ime) . ':' . $broj;
                                }
                                echo implode(', ', $partije_tekst);
                                ?></li>
                <?php } ?>
            </ul>
        </div>
    </section>

    <section class="box">
        <h2>Ispravni rezultati po izbornim mestima</h2>
        <div class="content">
            <ul>
                <?php while($red3=$rez3->fetch_assoc()){?>
					
                <li><?php echo $red3['NazivOpstine']  ?> - <?php echo $red3['Naziv']  ?> - <?php echo $red3['StatusRezultata']?> - <?php echo $red3['BrojGlasovaPoPartijama']  ?></li>
                <?php } ?>
            </ul>
        </div>
    </section>

</div>