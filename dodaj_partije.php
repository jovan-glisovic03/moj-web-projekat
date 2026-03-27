<?php include ('provera_admin.php') ?>

<!DOCTYPE html>
<html lang="sr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="stil.css" />
    <title>Dodaj partije</title>
</head>

<?php include ('header.php'); ?>

<?php include ('nav_admin.php'); ?>

<div class="form-wrapper1">
    <div class="form-box1">
        <h2>Dodavanje Partija</h2> 
        <div class="content">
            <form action="dodaj_part.php" method="post">
                <div class="form-row">
                    <label for="nove_partije">Unesite nove Partije (svaku odvojenu ";"):</label>
                    <textarea id="nove_partije" 
                              name="nove_partije" 
                              rows="6" 
                              cols="40" 
                              placeholder="Partija 1;Partija 2;Partija 3;..."></textarea>
                </div>

                <div class="buttons-row">
                    <button type="submit">Dodaj Partije</button>
                    <button type="reset">Poništi</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php include ('footer.php'); ?>