<?php
include('provera_admin.php');
$veza = new mysqli("localhost", "root", "", "izbori");

if(isset($_GET['id'])){
    $id = $_GET['id'];
    $upit_korisnici = "DELETE FROM Korisnici WHERE IDKorisnika = $id";
    $veza->query($upit_korisnici);
	
	$upit_analiticari = "DELETE FROM provere_analiticara WHERE IDAnaliticara = $id";
    $veza->query($upit_analiticari);
	
	$upit_rezultati = "DELETE FROM Rezultati WHERE IDAnaliticara = $id";
    $veza->query($upit_rezultati);
}

header("Location: azuriraj_analiticara.php");
?>