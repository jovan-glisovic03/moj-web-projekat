
<?php include ('provera_admin.php') ?>

<!DOCTYPE html>
<html lang="sr">
	<head>
		<meta charset="UTF-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<link rel="stylesheet" type="text/css" href="stil.css" />
		<title>Dodaj Opštinu</title>
	</head>
	<?php include ('header.php'); ?>

		<?php include ('nav_admin.php'); ?>

			<div class="form-wrapper1">
				<div class="form-box1">
				<h2>Dodavanje Opštine/Grada</h2>
					<div class="content">
						
						<form action="dodaj_opstinu.php" method="post">
							
							<div class="form-row">
								<label for="pretraga">Dodaj Grad/Opštinu:</label>
								<input type="text" id="opstina" name="opstina"/>
							</div>
							<div class="buttons-row">
								<button type="submit" name="submit" value="submit">Dodaj</button>
							</div>
						</form>

					</div>
				</div>
			</div>

			<?php include ('footer.php'); ?>
