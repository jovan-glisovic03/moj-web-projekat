<?php
session_start();
$_SESSION['ulogovan']='ne';

if (isset($_POST['email'], $_POST['sifra'])){
    $veza = new mysqli("localhost", "root", "", "izbori");
    $veza->set_charset("utf8");
    
    $upit = "SELECT Email, Sifra, Tip FROM Korisnici WHERE Email='{$_POST['email']}'";
    $rez = $veza->query($upit);
    
    if ($red = $rez->fetch_assoc()){
        if ($red['Email'] == $_POST['email'] && $red['Sifra'] == $_POST['sifra']){
            $_SESSION['ulogovan'] = 'da';
            $_SESSION['email'] = $red['Email'];
            
            if ($red['Tip'] == 0){
                $_SESSION['Tip'] = 'admin';
                header("Location:administrator.php");
                exit();
            }
            else if ($red['Tip'] == 1){
                $_SESSION['Tip'] = 'analiticar';
                header("Location:analiticar.php");
                exit();
            }
            else {
                $_SESSION['Tip'] = 'kontrolor';
                header("Location:kontrolor.php");
                exit();
            }
        }
    }
}
header("Location:ulogujse2.php");
exit();
?>