<?php 
include('provera_admin.php');
$veza = new mysqli("localhost", "root", "", "izbori");

if(isset($_GET['id'])){
    $id = $_GET['id'];
    $rez = $veza->query("SELECT * FROM Korisnici WHERE IDKorisnika = $id");
    $podaci = $rez->fetch_assoc();
}

if (isset($_POST['sacuvaj'])) {
    $veza = new mysqli("localhost", "root", "", "izbori");

    $id = $_POST['id_korisnika'];
    $ime = $_POST['novo_ime'];
    $prezime = $_POST['novo_prezime'];
	$telefon = trim($_POST['novi_telefon']);

if ($telefon == '') {
    $telefon_sql = "NULL";
} else {
    $telefon_sql = "'$telefon'";
}
    $email = $_POST['novi_email'];
	$sifra = $_POST['nova_sifra'];
	$adresa = $_POST['nova_adresa'];
	$slika = $_POST['nova_slika'];

    $upit="UPDATE Korisnici 
    SET Ime='$ime',
    Prezime='$prezime',
    Telefon=$telefon_sql,
    Email='$email',
    Sifra='$sifra',
    Adresa='$adresa'
    WHERE IDKorisnika='$id'";
   
	if($veza->query($upit)){
    header("Location:azuriraj_kontrolora.php");
    exit();
}else{
    die("SQL greska: ".$veza->error);
}
    
    }
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="stil.css">
    <title>Ažuriranje kontrolora</title>
</head>
<?php include ('header.php'); ?>

    <?php include ('nav_admin.php'); ?>

    <div class="form-wrapper">
        <section class="form-box">
            <h2>Ažuriranje kontrolora</h2>

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
                    <label>Slika:</label>


                    <button type="submit" name="sacuvaj" class="submit-btn">Sačuvaj</button>

                </form>
            </div>
        </section>
    </div>

    <?php include ('footer.php'); ?>