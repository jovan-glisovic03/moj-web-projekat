<?php
include('provera_admin.php');
$veza = new mysqli("localhost", "root", "", "izbori");

if (isset($_POST['nove_partije'])) {
    $unos = $_POST['nove_partije'];
    $delovi = explode(';', $unos);
    
    $noveZaDodavanje = [];
    foreach ($delovi as $p) {
        $p = trim($p);
        if (!empty($p)) {
            $noveZaDodavanje[] = $p; // Samo imena npr. ["NovaPartija", "DrugaPartija"]
        }
    }

    if (!empty($noveZaDodavanje)) {
        // 1. Prvo ažuriramo tabelu OPSTINEGRADOVI (šabloni)
        $sveOpstine = $veza->query("SELECT IDOpstinaGrad, BrojGlasovaPoPartijama FROM opstinegradovi");

        while ($red = $sveOpstine->fetch_assoc()) {
            $id = $red['IDOpstinaGrad'];
            $stariPodaci = trim($red['BrojGlasovaPoPartijama']);
            
            // Provera duplikata
            $noveFormatirane = [];
            foreach ($noveZaDodavanje as $ime) {
                if (strpos($stariPodaci, $ime . ":") === false) {
                    $noveFormatirane[] = $ime . ":0";
                }
            }

            if (!empty($noveFormatirane)) {
                $dodatak = implode(",", $noveFormatirane);
                $finalniString = empty($stariPodaci) ? $dodatak : $stariPodaci . "," . $dodatak;
                
                // Ažuriraj šablon u opštinama
                $veza->query("UPDATE opstinegradovi SET BrojGlasovaPoPartijama = '$finalniString' WHERE IDOpstinaGrad = $id");

                // 2. KLJUČNI DEO: Ažuriraj tabelu REZULTATI bez resetovanja starih glasova
                // Koristimo MySQL CONCAT funkciju da samo zalepimo novu partiju na kraj postojećeg stringa
                foreach ($noveFormatirane as $formatirana) {
                    // Dodajemo zarez samo ako polje nije prazno
                    $sql_update_rez = "UPDATE rezultati SET 
                        BrojGlasovaPoPartijama = CASE 
                            WHEN BrojGlasovaPoPartijama = '' OR BrojGlasovaPoPartijama IS NULL THEN '$formatirana'
                            WHEN BrojGlasovaPoPartijama NOT LIKE '%$ime:%' THEN CONCAT(BrojGlasovaPoPartijama, ',$formatirana')
                            ELSE BrojGlasovaPoPartijama 
                        END
                        WHERE IDOpstinaGrad = $id";
                    
                    $veza->query($sql_update_rez);
                }
            }
        }
    }
    header("Location: dodaj_partije.php");
    exit();
}
?>