<?php 
// 1. Inicijalizacija sesije i bezbednosna provera


// Uključujemo proveru koja dozvoljava pristup samo kontrolorima
include('provera_kontrolor.php'); 

$veza = new mysqli("localhost", "root", "", "izbori");
$veza->set_charset("utf8");

// 2. Pronalaženje ID-a ulogovanog kontrolora preko Email-a iz sesije
// Ovo povezuje tvoj login.php sa ovom stranicom
$email_korisnika = $_SESSION['email'];
$upit_k = $veza->query("SELECT IDKorisnika, Ime, Prezime FROM Korisnici WHERE Email = '$email_korisnika'");
$podaci_korisnika = $upit_k->fetch_assoc();
$moj_id = $podaci_korisnika['IDKorisnika'];

// 3. DOBAVLJANJE PODATAKA ZA FORMU (SELECT)
$podaci = null;

// Ako je prosleđen ID rezultata kroz URL (npr. ?id=5)
if(isset($_GET['id'])){
    $id_get = $_GET['id'];
    // Proveravamo da li taj rezultat pripada ulogovanom kontroloru (bezbednost)
    $rez = $veza->query("SELECT * FROM Rezultati WHERE IDRezultata = '$id_get' AND IDKontrolora = '$moj_id'");
    $podaci = $rez->fetch_assoc();
} else {
    // Ako nema ID-a u URL-u, automatski povuci prvo dostupno zaduženje
    $rez = $veza->query("SELECT * FROM Rezultati WHERE IDKontrolora = '$moj_id' LIMIT 1");
    $podaci = $rez->fetch_assoc();
}
if (isset($_GET['prazno'])) {
    // 1. Resetujemo glavne brojeve na nulu za prikaz u formi
	$podaci['BrojGlasaca'] = 0;
    $podaci['BrojIzaslih'] = 0;
    $podaci['BrojNevazecihListica'] = 0;
    $podaci['Neregularnost'] = "";
	$podaci['Zapisnik'] = "No file";
	$podaci['Prilozi'] = "No file";
    $podaci['Regularnost'] = 0;

    // 2. Resetujemo glasove partija na nulu
    if (!empty($podaci['BrojGlasovaPoPartijama'])) {
        $partije_niz = explode(',', $podaci['BrojGlasovaPoPartijama']);
        $resetovano = [];
        foreach($partije_niz as $p) {
            $delovi = explode(':', $p);
            $ime_partije = $delovi[0];
            $resetovano[] = $ime_partije . ":0"; // Svaku partiju spajamo sa nulom
        }
        $podaci['BrojGlasovaPoPartijama'] = implode(',', $resetovano);
    }
}

// Ako kontrolor nema nijedno zaduženje u tabeli Rezultati
if(!$podaci) {
    die("Sistem ne pronalazi zaduženje za vaš nalog. Proverite da li vam je Admin dodelio mesto.");
}

// 4. OBRADA FORME (UPDATE) - Kada se klikne na dugme "Sačuvaj"
if (isset($_POST['sacuvaj'])) {
    $id_rez_za_update = $_POST['id_rezultata'];
	$ukupan_br=$_POST['broj_glasaca'];
    $izasli = $_POST['broj_izaslih'];
    $nevazeci = $_POST['nevazeci'];
    $neregularnost = $_POST['komentari'];
    $regularnost = isset($_POST['potpisano']) ? 1 : 0;
    
    // Pakovanje glasova partija u string (format Partija1:Glasovi,Partija2:Glasovi...)
    $nizPartija = [];
    if (isset($_POST['glasovi'])) {
        foreach ($_POST['glasovi'] as $ime_partije => $broj_glasova) {
            $nizPartija[] = trim($ime_partije) . ":" . (int)$broj_glasova;
        }
    }
    $stringPartija = implode(',', $nizPartija);

    // Obrada slike zapisnika
    $putanja_slike = $_POST['stara_slika']; // Zadrži staru ako nema nove
    if(isset($_FILES['slika']) && $_FILES['slika']['error'] == 0){
        $ime_fajla = "zapisnik_" . time() . "_" . $_FILES['slika']['name'];
        $putanja_slike = "uploads/" . $ime_fajla;
        if (!is_dir('uploads')) mkdir('uploads');
        move_uploaded_file($_FILES['slika']['tmp_name'], $putanja_slike);
    }
	$putanja_slike2 = $_POST['stara_slika2'];
	if(isset($_FILES['slika2']) && $_FILES['slika2']['error'] == 0){
        $ime_fajla2 = "prilozi_" . time() . "_" . $_FILES['slika2']['name'];
        $putanja_slike2 = "uploads/" . $ime_fajla2;
        if (!is_dir('uploads')) mkdir('uploads');
        move_uploaded_file($_FILES['slika2']['tmp_name'], $putanja_slike2);
    }

    $upit_update = "UPDATE Rezultati SET 
                BrojGlasaca='$ukupan_br',
                BrojIzaslih = '$izasli', 
                BrojNevazecihListica = '$nevazeci', 
                BrojGlasovaPoPartijama = '$stringPartija', 
                Zapisnik = '$putanja_slike',
                Prilozi = '$putanja_slike2',
                Regularnost = '$regularnost', 
                Neregularnost = '$neregularnost',
                IDAnaliticara = 0,
                StatusRezultata = 'na_proveri'
                WHERE IDRezultata = '$id_rez_za_update' AND IDKontrolora = '$moj_id'";

    if($veza->query($upit_update)) {
    // 1. Obriši stare dodele za ovaj rezultat, ako postoje
    $veza->query("DELETE FROM provere_analiticara WHERE IDRezultata = '$id_rez_za_update'");

    // 2. Pronađi 3 analitičara sa najmanjim brojem otvorenih provera
    $upit_analiticari = "
        SELECT k.IDKorisnika, COUNT(p.IDProvere) AS BrojOtvorenih
        FROM korisnici k
        LEFT JOIN provere_analiticara p
            ON k.IDKorisnika = p.IDAnaliticara
            AND p.StatusProvere IN ('dodeljeno', 'u_toku')
        WHERE k.Tip = 1
        GROUP BY k.IDKorisnika
        ORDER BY BrojOtvorenih ASC, k.IDKorisnika ASC
        LIMIT 3
    ";

    $rez_analiticari = $veza->query($upit_analiticari);

    if (!$rez_analiticari) {
        die("Greška pri izboru analitičara: " . $veza->error);
    }

    $analiticari = [];
    while ($red_analiticar = $rez_analiticari->fetch_assoc()) {
        $analiticari[] = (int)$red_analiticar['IDKorisnika'];
    }

    if (count($analiticari) < 3) {
        die("Nema dovoljno analitičara za dodelu. Pronađeno: " . count($analiticari));
    }

    $id_izborno_mesto = $podaci['IDIzbornoMesto'];

    foreach ($analiticari as $id_analiticara) {
        $upit_dodela = "
            INSERT INTO provere_analiticara
            (IDRezultata, IDAnaliticara, IDIzbornoMesto, BrojGlasaca, BrojIzaslih, BrojNevazecihListica, BrojGlasovaPoPartijama, Komentar, StatusProvere)
            VALUES
            ('$id_rez_za_update', '$id_analiticara', '$id_izborno_mesto', '$ukupan_br', '$izasli', '$nevazeci', '$stringPartija', '$neregularnost', 'dodeljeno')
        ";

        if (!$veza->query($upit_dodela)) {
            die("Greška pri dodeli analitičaru $id_analiticara: " . $veza->error);
        }
    }

    header("Location: unos_rezultata.php?prazno=da");
    exit();
}
}
?>


<!DOCTYPE html>
<html lang="sr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
	
    <link rel="stylesheet" href="stil.css">
    <title>Unos rezultata</title>
</head>
<body>
    <?php include ('header.php'); ?>
    <?php include ('nav_kontrolor.php'); ?>

    <div class="form-wrapper1">
        <section class="form-box1">
            <h2>Formular za unos rezultata</h2>
            

            <div class="content">
                <form method="POST" enctype="multipart/form-data">
					<input type="hidden" name="sacuvaj" value="1">
                    <input type="hidden" name="id_rezultata" value="<?php echo $podaci['IDRezultata']; ?>">
                    <input type="hidden" name="stara_slika" value="<?php echo $podaci['Zapisnik']; ?>">
					<input type="hidden" name="stara_slika2" value="<?php echo $podaci['Prilozi']; ?>">


                    <label>Ukupan broj glasača:</label>
                    <input type="number" id="broj_glasaca" name="broj_glasaca" value="<?php echo $podaci['BrojGlasaca']; ?>" required min="0">


                    <label>Ukupan broj glasača koji su izašli:</label>
                    <input type="number" id="broj_izaslih" name="broj_izaslih" value="<?php echo $podaci['BrojIzaslih']; ?>" required min="0">

                    <label>Broj glasova po izbornim listama:</label>
                    <div class="liste-kontejner">
                        <?php 
    $partije = explode(',', $podaci['BrojGlasovaPoPartijama']);
    $i = 0; // Dodajemo brojač
    foreach($partije as $p) {
        $delovi = explode(':', trim($p));
        $naziv_liste = $delovi[0];
        $trenutni_glasovi = isset($delovi[1]) ? $delovi[1] : 0;
        
        echo "<div>";
        echo "<span>" . htmlspecialchars($naziv_liste) . ":</span>";
        // DODAJEMO: id='partija_$i' i class='klasa-glas'
        echo "<input type='number' id='partija_$i' name='glasovi[" . htmlspecialchars($naziv_liste) . "]' value='$trenutni_glasovi' min='0' class='klasa-glas'>";
        echo "</div>";
        $i++; // Povećavamo brojač
    }
    ?>
                    </div>

                    <label>Broj nevažećih listića:</label>
                    <input type="number" id="nevazeci" name="nevazeci" value="<?php echo $podaci['BrojNevazecihListica']; ?>" required min="0">

                    <label>Primedbe i komentari (Neregularnosti):</label>
                    <textarea name="komentari" rows="4" placeholder="Unesite zapažanja sa biračkog mesta..."><?php echo $podaci['Neregularnost']; ?></textarea>

                    <label>
                        <input type="checkbox" name="potpisano" <?php echo ($podaci['Regularnost'] == 1) ? 'checked' : ''; ?>> 
                        <strong>Potvrđujem da je zapisnik uredno potpisan</strong>
                    </label>

                    <label>Priloži fotografiju zapisnika:</label>
                    <input type="file" name="slika" accept="image/*">
					<?php if(!empty($podaci['Zapisnik'])): ?>
                        <p><small>Trenutni fajl u bazi: <em><?php echo $podaci['Zapisnik']; ?></em></small></p>
                    <?php endif; ?>
					
					<label>Prilozi:</label>
                    <input type="file" name="slika2" accept="image/*">
					<?php if(!empty($podaci['Prilozi'])): ?>
                        <p><small>Trenutni fajl u bazi: <em><?php echo $podaci['Prilozi']; ?></em></small></p>
                    <?php endif; ?>

                    <button type="button" onclick="proveriSve(event)" name="sacuvaj" class="submit-btn">
                        SAČUVAJ I POŠALJI REZULTATE
                    </button>
                </form>
            </div>
        </section>
    </div>

    <?php include ('footer.php'); ?>
	<script>
function proveriSve(event) {
    if (event) event.preventDefault();
    
    // 1. Sabiramo partije preko klase i ID-a
    let svePartije = document.getElementsByClassName('klasa-glas');
    let zbirPartija = 0;

    for (let j = 0; j < svePartije.length; j++) {
        let inputPartija = document.getElementById('partija_' + j);
        if (inputPartija) {
            zbirPartija += Number(inputPartija.value) || 0;
        }
    }

    // 2. Uzimamo ostale vrednosti
    let izasli = Number(document.getElementById('broj_izaslih').value) || 0;
    let nevazeci = Number(document.getElementById('nevazeci').value) || 0;
    
    let ukupno = zbirPartija + nevazeci;

    // 3. Provera matematike
    if (izasli !== ukupno) {
        alert("GREŠKA!\n\nUneto da je izašlo: " + izasli + "\nZbir listića u kutiji: " + ukupno + " (Glasovi + Nevažeći)\n\nBrojevi se moraju slagati!");
    } else {
        // Ako je sve u redu, ručno šaljemo formu
        event.target.closest('form').submit();
    }
}
</script>
