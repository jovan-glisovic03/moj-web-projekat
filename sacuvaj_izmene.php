<?php
if (isset($_POST['sacuvaj'])) {
    $veza = new mysqli("localhost", "root", "", "izbori");

    $id = $_POST['id_korisnika'];
    $ime = $_POST['novo_ime'];
    $prezime = $_POST['novo_prezime'];
	$telefon = $_POST['novi_telefon'];
    $email = $_POST['novi_email'];
	$sifra = $_POST['nova_sifra'];
	$adresa = $_POST['nova_adresa'];
	$slika = $_POST['nova_slika'];

    
    $upit="UPDATE Korisnici SET Ime='$ime', Prezime='$prezime', Telefon='$telefon', Email='$email', Sifra='$sifra', Adresa='$adresa', Slika='$slika' WHERE IDKorisnika='$id'";
	$rez=$veza->query($upit);
	header("Location:azuriraj_kontrolora.php");
    
    }
?>