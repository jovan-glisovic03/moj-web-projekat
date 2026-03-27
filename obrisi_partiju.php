<?php
include('provera_admin.php');
$veza = new mysqli("localhost", "root", "", "izbori");

// Proveravamo da li je naziv stigao preko GET-a ili POST-a (prilagodi svom textarea-u)
if (isset($_REQUEST['naziv'])) {
    $zaBrisanje = trim($_REQUEST['naziv']);

    // 1. Ažuriranje tabele OPSTINEGRADOVI
    $sveOpstine = $veza->query("SELECT IDOpstinaGrad, BrojGlasovaPoPartijama FROM opstinegradovi");
    while ($red = $sveOpstine->fetch_assoc()) {
        $id = $red['IDOpstinaGrad'];
        $nizPartija = explode(',', $red['BrojGlasovaPoPartijama']);
        $noviNiz = [];

        foreach ($nizPartija as $par) {
            $ime = explode(':', $par)[0];
            if (trim($ime) !== $zaBrisanje) {
                $noviNiz[] = $par;
            }
        }

        $noviString = implode(',', $noviNiz);
        $veza->query("UPDATE opstinegradovi SET BrojGlasovaPoPartijama = '$noviString' WHERE IDOpstinaGrad = $id");
    }

    // 2. Ažuriranje tabele REZULTATI (ovde dodajemo brisanje)
    // Uzimamo sve redove iz rezultata jer svaka opština može imati svoj unos
    $sviRezultati = $veza->query("SELECT IDRezultata, BrojGlasovaPoPartijama FROM rezultati");
    
    while ($redRez = $sviRezultati->fetch_assoc()) {
        $idRez = $redRez['IDRezultata'];
        $nizPartijaRez = explode(',', $redRez['BrojGlasovaPoPartijama']);
        $noviNizRez = [];

        foreach ($nizPartijaRez as $par) {
            $delovi = explode(':', $par);
            $ime = $delovi[0];
            
            // Zadržavamo partiju samo ako NIJE ona koju brišemo
            if (trim($ime) !== $zaBrisanje) {
                $noviNizRez[] = $par;
            }
        }

        $noviStringRez = implode(',', $noviNizRez);
        $veza->query("UPDATE rezultati SET BrojGlasovaPoPartijama = '$noviStringRez' WHERE IDRezultata = $idRez");
    }
}

header("Location: azuriraj_partije.php");
exit();
?>