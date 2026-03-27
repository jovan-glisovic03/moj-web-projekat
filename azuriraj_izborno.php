<?php
include('provera_admin.php');
$veza = new mysqli("localhost", "root", "", "izbori");
$veza->set_charset("utf8");

// --- DEO ZA BRISANJE ---
if (isset($_POST['obrisi'])) {
    $naziv_za_brisanje = $_POST['pretraga'];
	
	$upit_id = "SELECT IDIzbornoMesto FROM Rezultati WHERE Naziv = '$naziv_za_brisanje'";
    $rez_id = $veza->query($upit_id);
    
    if ($rez_id->num_rows > 0) {
        while ($red = $rez_id->fetch_assoc()) {
            $id = $red['IDIzbornoMesto'];
            
            // 2. Brišemo iz provere_analiticara koristeći taj ID
            $veza->query("DELETE FROM provere_analiticara WHERE IDIzbornoMesto = $id");
        }
    }
    
    // Brišemo sve redove iz Rezultata koji imaju taj naziv
    $upit_brisanje = "DELETE FROM Rezultati WHERE Naziv = '$naziv_za_brisanje'";
    
    if ($veza->query($upit_brisanje)) {
        // Vraćamo se nazad na pretragu sa porukom o uspehu
        header("Location: azuriraj_izb_mesta.php");
        exit();
    }
}

// --- DEO ZA AZURIRANJE (Prikaz forme) ---
$podaci = null;
$imena_kontrolora = "";

if (isset($_POST['azuriraj'])) {
    $naziv = $_POST['pretraga'];
    
    // Tražimo podatke o mestu za popunjavanje forme
    $rez = $veza->query("SELECT * FROM Rezultati WHERE Naziv = '$naziv' LIMIT 1");
    
    if ($rez->num_rows > 0) {
        $podaci = $rez->fetch_assoc();
        $id_mesta = $podaci['IDIzbornoMesto'];
        
        // Izvlačimo imena kontrolora za textarea
        $kontrolori_upit = "SELECT k.Ime, k.Prezime FROM Rezultati r 
                            JOIN Korisnici k ON r.IDKontrolora = k.IDKorisnika 
                            WHERE r.IDIzbornoMesto = '$id_mesta'";
        $rez_k = $veza->query($kontrolori_upit);
        
        $niz = [];
        while($k_red = $rez_k->fetch_assoc()) {
            $niz[] = $k_red['Ime'] . " " . $k_red['Prezime'];
        }
        $imena_kontrolora = implode('; ', $niz);
    } else {
        echo "<script>alert('Mesto nije pronađeno!'); window.location.href='azuriraj_izb_mesta.php';</script>";
        exit();
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="stil.css">
    <script src="select_pretraga.js"></script>
    <title>Uređivanje mesta</title>
</head>
<body>
    <?php include ('header.php'); include ('nav_admin.php'); ?>

    <div class="form-wrapper">
        <section class="form-box">
            <h2>Uredi izborno mesto i kontrolore</h2>
            <form action="sacuvaj_izmene_mesta.php" method="POST">
                <input type="hidden" name="id_izborno_mesto" value="<?php echo $podaci['IDIzbornoMesto']; ?>">
                <input type="hidden" name="stari_naziv" value="<?php echo $podaci['Naziv']; ?>">

                <label>Naziv izbornog mesta:</label>
                <input type="text" name="novi_naziv" value="<?php echo $podaci['Naziv']; ?>">

                <label for="pretraga_opstine_edit">Pretraga opštine/grada:</label>
<input type="text"
       id="pretraga_opstine_edit"
       onkeyup="filtrirajSelect('pretraga_opstine_edit', 'id_opstine')"
       placeholder="Počni da kucaš naziv...">

<label for="id_opstine">Opština/Grad:</label>
<select id="id_opstine" name="id_opstine">
    <option value="">-- Odaberite opciju --</option>
    <?php
    $rez_opstine = $veza->query("SELECT IDOpstinaGrad, Naziv FROM opstinegradovi ORDER BY Naziv");
    while ($opstina = $rez_opstine->fetch_assoc()) {
        $selected = ($opstina['IDOpstinaGrad'] == $podaci['IDOpstinaGrad']) ? 'selected' : '';
        echo '<option value="' . $opstina['IDOpstinaGrad'] . '" ' . $selected . '>' . htmlspecialchars($opstina['Naziv']) . '</option>';
    }
    ?>
</select>

                <label>Kontrolori (Ime Prezime; Ime Prezime...):</label>
                <textarea name="kontrolori_lista" rows="5" placeholder="Petar Petrović; Marko Marković"><?php echo $imena_kontrolora; ?></textarea>
                <small>Obavezno koristite format "Ime Prezime" razdvojeno tačka-zarezom.</small>

                <button type="submit" name="sacuvaj_izmene" class="submit-btn">Sačuvaj sve izmene</button>
            </form>
        </section>
    </div>
	<?php include ('footer.php'); ?>