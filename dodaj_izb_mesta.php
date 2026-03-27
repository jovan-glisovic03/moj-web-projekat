<?php include ('provera_admin.php') ?>

<!DOCTYPE html>
<html lang="sr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="select_pretraga.js"></script>
    <link rel="stylesheet" type="text/css" href="stil.css" />
    <title>Dodaj izborna mesta</title>
</head>

<?php include ('header.php'); ?>
<?php include ('nav_admin.php'); ?>

<div class="form-wrapper1">
    <div class="form-box1">
        <h2>Odabir izbornih mesta</h2>

        <div class="content">
            <form action="dodaj_izborno.php" method="post">

                <div class="form-row">
                    <label for="pretraga_grad_opstina">Pretraga grada/opštine:</label>
                    <input type="text"
                           id="pretraga_grad_opstina"
                           onkeyup="filtrirajSelect('pretraga_grad_opstina', 'grad_opstina')"
                           placeholder="Počni da kucaš naziv...">
                </div>

                <div class="form-row">
                    <label for="grad_opstina">Izaberite Grad/Opštinu:</label>
                    <select id="grad_opstina" name="grad_opstina">
                        <option value="">-- Odaberite opciju --</option>
                        <?php include ('odaberi_opciju.php'); ?>
                    </select>
                </div>

                <div class="form-row">
                    <label for="nova_mesta">Unesite izborna mesta(Svako odvojeno ";"):</label>
                    <textarea id="nova_mesta" name="nova_mesta" rows="6"
                    placeholder="Izborno mesto 1;Izborno mesto 2;Izborno mesto 3;...."></textarea>
                </div>

                <div class="buttons-row">
                    <button type="submit" name="dodaj">Dodaj</button>
                </div>

            </form>
        </div>
    </div>
</div>

<?php include ('footer.php'); ?>
<script>
</script>
</html>