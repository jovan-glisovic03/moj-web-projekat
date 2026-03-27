<?php
include('provera_admin.php');

$veza = new mysqli("localhost", "root", "", "izbori");
$veza->set_charset("utf8");

if (isset($_POST['stari_naziv']) && isset($_POST['novi_naziv'])) {
    $stari_naziv = trim($_POST['stari_naziv']);
    $novi_naziv = trim($_POST['novi_naziv']);

    if ($stari_naziv !== "" && $novi_naziv !== "") {

        // 1. OPSTINEGRADOVI
        $sveOpstine = $veza->query("SELECT IDOpstinaGrad, BrojGlasovaPoPartijama FROM opstinegradovi");

        while ($red = $sveOpstine->fetch_assoc()) {
            $id = $red['IDOpstinaGrad'];
            $nizPartija = explode(',', $red['BrojGlasovaPoPartijama']);
            $noviNiz = [];

            foreach ($nizPartija as $par) {
                $delovi = explode(':', $par);
                $ime = trim($delovi[0]);
                $glasovi = isset($delovi[1]) ? $delovi[1] : '0';

                if ($ime === $stari_naziv) {
                    $noviNiz[] = $novi_naziv . ':' . $glasovi;
                } else {
                    $noviNiz[] = $par;
                }
            }

            $noviString = implode(',', $noviNiz);
            $veza->query("UPDATE opstinegradovi SET BrojGlasovaPoPartijama = '$noviString' WHERE IDOpstinaGrad = $id");
        }

        // 2. REZULTATI
        $sviRezultati = $veza->query("SELECT IDRezultata, BrojGlasovaPoPartijama FROM rezultati");

        while ($redRez = $sviRezultati->fetch_assoc()) {
            $idRez = $redRez['IDRezultata'];
            $nizPartijaRez = explode(',', $redRez['BrojGlasovaPoPartijama']);
            $noviNizRez = [];

            foreach ($nizPartijaRez as $par) {
                $delovi = explode(':', $par);
                $ime = trim($delovi[0]);
                $glasovi = isset($delovi[1]) ? $delovi[1] : '0';

                if ($ime === $stari_naziv) {
                    $noviNizRez[] = $novi_naziv . ':' . $glasovi;
                } else {
                    $noviNizRez[] = $par;
                }
            }

            $noviStringRez = implode(',', $noviNizRez);
            $veza->query("UPDATE rezultati SET BrojGlasovaPoPartijama = '$noviStringRez' WHERE IDRezultata = $idRez");
        }
    }
}

header("Location: azuriraj_partije.php");
exit();
?>