<?php 
if(isset($_POST['dodaj'])){
    $veza = new mysqli("localhost", "root", "", "izbori");
    $veza->set_charset("utf8");

    $unos = trim($_POST['nova_mesta']);
    $id_opstine = isset($_POST['grad_opstina']) ? (int)$_POST['grad_opstina'] : 0;

    if ($id_opstine <= 0 || $unos === "") {
        header("Location: dodaj_izb_mesta.php");
        exit();
    }

$ref_upit = $veza->query("SELECT BrojGlasovaPoPartijama FROM opstinegradovi WHERE IDOpstinaGrad = '$id_opstine' LIMIT 1");
$ref_red = $ref_upit->fetch_assoc();
$string_partija = ($ref_red) ? $ref_red['BrojGlasovaPoPartijama'] : '';

    $mesta = explode(';', $unos);

    foreach ($mesta as $mesto) {
        $mesto = trim($mesto);

        if (!empty($mesto)) {
            // Provera da li mesto već postoji
            $provera = $veza->query("SELECT IDIzbornoMesto FROM Rezultati WHERE Naziv = '$mesto' AND IDOpstinaGrad = '$id_opstine' LIMIT 1");

            if ($provera && $provera->num_rows == 0) {
                // Ako mesto NE POSTOJI, tek onda tražimo novi ID i ubacujemo
                $max_upit = $veza->query("SELECT MAX(IDIzbornoMesto) AS max_id FROM Rezultati");
                $max_red = $max_upit->fetch_assoc();
                $id_mesta_za_upis = ((int)$max_red['max_id']) + 1;

                $upit = "
                    INSERT INTO Rezultati 
                    (IDIzbornoMesto, IDOpstinaGrad, Naziv, BrojGlasaca, BrojIzaslih, 
                     BrojNevazecihListica, BrojGlasovaPoPartijama, Regularnost, 
                     Neregularnost, IDKontrolora, IDAnaliticara, StatusRezultata) 
                    VALUES 
                    ('$id_mesta_za_upis', '$id_opstine', '$mesto', 0, 0, 
                     0, '$string_partija', 0, 
                     '', 0, 0, 'na_proveri')
                ";

                $veza->query($upit);
            } else {
                // Ako mesto POSTOJI, ovde možeš dodati UPDATE ako želiš nešto da promeniš,
                // ali pošto ne diraš glasove, verovatno je najbolje ne raditi ništa.
            }
        }
    }

    header("Location: dodaj_izb_mesta.php");
    exit();
}
?>