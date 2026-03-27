<?php
if(isset($_POST['submit'])){
    $veza = new mysqli("localhost", "root", "", "izbori");
    $veza->set_charset("utf8");

    $opstina = trim($_POST['opstina']);

    if($opstina != ""){
        $upit1="SELECT BrojGlasovaPoPartijama FROM opstinegradovi ORDER BY IDOpstinaGrad ASC LIMIT 1";
		$rez=$veza->query($upit1);
		$partije='';
		if($rez && $red=$rez->fetch_assoc()){
			$partije=$red['BrojGlasovaPoPartijama'];
		}
		

        $upit2 = "INSERT INTO opstinegradovi (Naziv, BrojGlasaca, BrojIzaslih, BrojNevazecihListica, BrojGlasovaPoPartijama, PrebrojaniGlasaci, PrebrojanaIzbornaMesta)
                 VALUES ('$opstina', 0, 0, 0, '$partije', 0.00, 0.00)";
        $veza->query($upit2);
    }

    header("Location: grad_opstina.php");
    exit();
}
?>