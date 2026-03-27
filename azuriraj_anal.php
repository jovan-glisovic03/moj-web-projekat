<?php 
include('provera_admin.php');
$veza = new mysqli("localhost", "root", "", "izbori");
$veza->set_charset("utf8");

if(isset($_GET['id'])){
    $id = (int)$_GET['id'];
    $rez = $veza->query("SELECT * FROM Korisnici WHERE IDKorisnika = $id");
    $podaci = $rez->fetch_assoc();
}

if (isset($_POST['sacuvaj'])) {
    $id = (int)$_POST['id_korisnika'];
    $ime = trim($_POST['novo_ime']);
    $prezime = trim($_POST['novo_prezime']);
    $telefon = trim($_POST['novi_telefon']);
    $email = trim($_POST['novi_email']);
    $sifra = trim($_POST['nova_sifra']);
    $adresa = trim($_POST['nova_adresa']);
    

    if ($telefon === '') {
        $telefon_sql = "NULL";
    } else {
        $telefon_sql = "'$telefon'";
    }

    $upit = "UPDATE Korisnici 
             SET Ime='$ime',
                 Prezime='$prezime',
                 Telefon=$telefon_sql,
                 Email='$email',
                 Sifra='$sifra',
                 Adresa='$adresa'
             WHERE IDKorisnika='$id'";

    if ($veza->query($upit)) {
        header("Location: azuriraj_analiticara.php");
        exit();
    } else {
        die("Greška pri ažuriranju: " . $veza->error);
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="stil.css">
    <title>Ažuriranje analitičara</title>
</head>
<?php include ('header.php'); ?>

    <?php include ('nav_admin.php'); ?>

    <div class="form-wrapper">
        <section class="form-box">
            <h2>Ažuriranje analitičara</h2>

            <div class="content">
                <form method="POST">
				<input type="hidden" name="id_korisnika" value="<?php echo $podaci['IDKorisnika']; ?>">

                    <label>Ime:</label>
                    <input type="text" name="novo_ime" value="<?php echo $podaci['Ime']; ?>">
                    <label>Prezime:</label>
                    <input type="text" name="novo_prezime" value="<?php echo $podaci['Prezime']; ?>">
                    <label>Telefon:</label>
                    <input type="text" name="novi_telefon" value="<?php echo htmlspecialchars($podaci['Telefon'] ?? ''); ?>">
                    <label>Email:</label>
                    <input type="text" name="novi_email" value="<?php echo $podaci['Email']; ?>">
					<label>Šifra:</label>
                    <input type="text" name="nova_sifra" value="<?php echo $podaci['Sifra']; ?>">
					<label>Adresa:</label>
                    <input type="text" name="nova_adresa" value="<?php echo $podaci['Adresa']; ?>">


                    <button type="submit" name="sacuvaj" class="submit-btn">Sačuvaj</button>

                </form>
            </div>
        </section>
    </div>

    <?php include ('footer.php'); ?>