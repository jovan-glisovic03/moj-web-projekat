
<?php include ('provera_admin.php') ?>

<!DOCTYPE html>
<html lang="sr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="stil.css" />
    <title>Ažuriraj izborna mesta</title>
</head>
<?php include ('header.php'); ?>

    <?php include ('nav_admin.php'); ?>

    <div class="form-wrapper1">
        <div class="form-box1">
            <h2>Ažuriranje izbornih mesta</h2>

            <div class="content">
                <form action="azuriraj_izborno.php" method="post">
                    
                    <div class="form-row">
                        <label for="pretraga">Pretraga izbornih mesta:</label>
                        <input type="text" id="pretraga" name="pretraga" placeholder="Unesite naziv izbornog mesta" />
                    </div>

                    <div class="buttons-row">
                        <button type="submit" name="azuriraj" value="azuriraj">Ažuriraj</button>
                        <button type="submit" name="obrisi" value="obrisi">Obriši</button>
                    </div>
					<div class="content">

                <ul class="partija-list">
					<?php
                    $veza = new mysqli("localhost", "root", "", "izbori");
					$upit="SELECT DISTINCT Naziv FROM Rezultati ORDER BY Naziv ASC";
					$rez=$veza->query($upit);
					
					if ($rez->num_rows > 0) {
            while ($red = $rez->fetch_assoc()) {
                $naziv = $red['Naziv'];
                ?>
                <li>
                    <span><?php echo htmlspecialchars($naziv); ?></span>
                </li>
                <?php 
            }
        } else {
            echo "<li>Nema pronađenih izbornih mesta.</li>";
        }
        
        $veza->close();
        ?>
		</ul>
            </div>

                </form>
            </div>
        </div>
    </div>

    <?php include ('footer.php'); ?>

    