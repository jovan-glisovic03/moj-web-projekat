<?php 
    if(isset($_POST['submit'])){
    $veza=new mysqli("localhost", "root", "", "izbori");
    $naziv=$_POST['opstina'];
    $upit="INSERT INTO OpstineGradovi SET Naziv='$naziv' ";
    $rez=$veza->query($upit);
    header("Location:grad_opstina.php");

}
?>
