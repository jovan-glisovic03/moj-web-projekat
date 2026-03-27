<?php 
if(isset($_POST['submit'])){
	$unos = $_POST['analiticari'];

    $analiticari = explode(';', $unos);

    foreach ($analiticari as $osoba) {

        $osoba = trim($osoba);

        if ($osoba == '') continue;

        $delovi = explode(' ', $osoba);

        $ime = array_shift($delovi);

        $prezime = implode(' ', $delovi);
		$veza=new mysqli("localhost", "root", "", "izbori");
		$upit="INSERT INTO Korisnici SET Ime='$ime', Prezime='$prezime', Tip='1' ";
		$rez=$veza->query($upit);

    }
header("Location:dodaj_analiticara.php");
}
if(isset($_POST['reset'])){
	header("Location:dodaj_analiticara.php");
}
?>