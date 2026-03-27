<?php
include('provera_admin.php');
$veza = new mysqli("localhost", "root", "", "izbori");
$veza->set_charset("utf8");

if (isset($_POST['sacuvaj_izmene'])) {
    $id_mesta = $_POST['id_izborno_mesto'];
    $novi_naziv = trim($_POST['novi_naziv']);
    $id_opstine = (int)$_POST['id_opstine'];
    $lista = trim($_POST['kontrolori_lista']);

    // Uzmi šablon partija iz izabrane opštine
    $upit_partije = $veza->query("SELECT BrojGlasovaPoPartijama FROM opstinegradovi WHERE IDOpstinaGrad = '$id_opstine' LIMIT 1");
    $partije = $upit_partije->fetch_assoc();
    $string_partija = $partije['BrojGlasovaPoPartijama'];

    // Ako lista kontrolora nije prazna, radi refresh kontrolora
    if ($lista !== '') {
        $kontrolori = explode(';', $lista);

        // Prvo pronađi validne kontrolore
        $validni_kontrolori = [];

        foreach ($kontrolori as $k_ime) {
            $puno_ime = trim($k_ime);
            if (empty($puno_ime)) continue;

            $delovi = explode(' ', $puno_ime, 2);
            $ime = trim($delovi[0]);
            $prezime = isset($delovi[1]) ? trim($delovi[1]) : '';

            $upit_k = "SELECT IDKorisnika FROM Korisnici WHERE Ime = '$ime' AND Prezime = '$prezime' LIMIT 1";
            $rez_k = $veza->query($upit_k);

            if ($rez_k && $rez_k->num_rows > 0) {
                $korisnik = $rez_k->fetch_assoc();
                $validni_kontrolori[] = $korisnik['IDKorisnika'];
            }
        }

        // Ako postoji bar jedan validan kontrolor, onda briši stare i ubaci nove
        if (!empty($validni_kontrolori)) {
            $veza->query("DELETE FROM Rezultati WHERE IDIzbornoMesto = '$id_mesta'");

            foreach ($validni_kontrolori as $id_kontrolora) {
                $veza->query("
                    INSERT INTO Rezultati 
                    (IDIzbornoMesto, IDOpstinaGrad, Naziv, IDKontrolora, BrojGlasovaPoPartijama, BrojGlasaca, BrojIzaslih, BrojNevazecihListica, Regularnost, Neregularnost, IDAnaliticara, StatusRezultata)
                    VALUES
                    ('$id_mesta', '$id_opstine', '$novi_naziv', '$id_kontrolora', '$string_partija', 0, 0, 0, 0, '', 0, 'na_proveri')
                ");
            }
        } else {
            // Ako nema validnih kontrolora, samo promeni naziv i opštinu na postojećim redovima
            $veza->query("UPDATE Rezultati 
                          SET Naziv = '$novi_naziv', IDOpstinaGrad = '$id_opstine'
                          WHERE IDIzbornoMesto = '$id_mesta'");
        }
    } else {
        // Ako nema liste kontrolora, samo ažuriraj postojeće redove
        $veza->query("UPDATE Rezultati 
                      SET Naziv = '$novi_naziv', IDOpstinaGrad = '$id_opstine'
                      WHERE IDIzbornoMesto = '$id_mesta'");
    }

    header("Location: azuriraj_izb_mesta.php");
    exit();
}
?>