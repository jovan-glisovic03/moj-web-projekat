<?php 
if(isset($_POST['submit'])){
	$unos = $_POST['kontrolori'];

    $kontrolori = explode(';', $unos);

    foreach ($kontrolori as $osoba) {

        $osoba = trim($osoba);

        if ($osoba == '') continue;

        $delovi = explode(' ', $osoba);

        $ime = array_shift($delovi);

        $prezime = implode(' ', $delovi);
		$veza=new mysqli("localhost", "root", "", "izbori");
		$upit="INSERT INTO Korisnici SET Ime='$ime', Prezime='$prezime', Tip='2' ";
		$rez=$veza->query($upit);

    }
header("Location:dodaj_kontrolora.php");
}
if(isset($_POST['reset'])){
	header("Location:dodaj_kontrolora.php");
}
?>