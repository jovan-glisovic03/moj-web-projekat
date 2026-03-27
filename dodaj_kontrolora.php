<?php include ('provera_admin.php') ?>

<!DOCTYPE html>
<html lang="sr">
	<head>
		<meta charset="UTF-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<link rel="stylesheet" type="text/css" href="stil.css" />
		<script src="select_pretraga.js"></script>
		<title>Dodaj kontrolora</title>
	</head>
	<?php include ('header.php'); ?>

		<?php include ('nav_admin.php'); ?>

			<div class="form-wrapper1">
				<div class="form-box1">
					<h2>Odabir kontrolora</h2>
					<div class="content">
						
						<form action="dodaj_kontrol.php" method="post">
							
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
								<label for="nove_partije">Unesite kontrolora (svaki sa ";" na kraju):</label>
								<textarea name="kontrolori" rows="6" placeholder="Kontrolor1;Kontrolor2;Kontrolor3;..."></textarea>
							</div>

							<div class="buttons-row">
								<button name="submit" type="submit">Potvrdi Odabir</button>
								<button name="reset" type="reset">Poništi</button>
							</div>
						</form>

					</div>
				</div>
			</div>

			<?php include ('footer.php'); ?>
