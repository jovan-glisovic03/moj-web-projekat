
<?php include ('provera_admin.php') ?>

<!DOCTYPE html>
<html lang="sr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="stil.css">
    <title>Dodaj analitičara</title>
</head>
<?php include ('header.php'); ?>

    <?php include ('nav_admin.php'); ?>

    <div class="form-wrapper1">
        <div class="form-box1">
            <h2>Dodaj analitičare</h2>

            <div class="content">
                <form action="dodaj_anal.php" method="post">
                    <div class="form-row">
                        <label for="analiticari">Unesite analitičare (svaki sa ";" na kraju):</label>
                        <textarea name="analiticari" id="analiticari" rows="6"
                                  placeholder="Analitičar 1;Analitičar 2;Analitičar 3;..."></textarea>
                    </div>

                    <div class="buttons-row">
                        <button name="submit" type="submit">Sačuvaj</button>
                        <button name="reset" type="reset">Poništi</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <?php include ('footer.php'); ?>

    