<?php include ('provera_admin.php'); ?>

<!DOCTYPE html>
<html lang="sr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="stil.css" />
    <title>Izmeni partiju</title>
</head>

<?php include ('header.php'); ?>
<?php include ('nav_admin.php'); ?>

<?php
$stari_naziv = isset($_GET['naziv']) ? $_GET['naziv'] : '';
?>

<div class="form-wrapper1">
    <div class="form-box1">
        <h2>Izmena partije</h2>
        <div class="content">
            <form action="izmeni_partiju.php" method="post">
                <input type="hidden" name="stari_naziv" value="<?php echo htmlspecialchars($stari_naziv); ?>">

                <label>Stari naziv partije:</label><br>
                <input type="text" value="<?php echo htmlspecialchars($stari_naziv); ?>" disabled><br><br>

                <label>Novi naziv partije:</label><br>
                <input type="text" name="novi_naziv" required><br><br>

                <button type="submit">Sačuvaj izmene</button>
            </form>
        </div>
    </div>
</div>

<?php include ('footer.php'); ?>