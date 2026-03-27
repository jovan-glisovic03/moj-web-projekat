<?php 
$veza = new mysqli("localhost", "root", "", "izbori");

$upit = "SELECT IDOpstinaGrad, Naziv FROM opstinegradovi ORDER BY Naziv";
$rez = $veza->query($upit);

while ($red = $rez->fetch_assoc()){
    echo '<option value="' . $red['IDOpstinaGrad'] . '">' . $red['Naziv'] . '</option>';
}
?>