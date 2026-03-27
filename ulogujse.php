<!DOCTYPE html>
<html lang="sr">
	<head>
		<meta charset="UTF-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<link rel="stylesheet" type="text/css" href="stil.css" />
		<title>Uloguj se</title>
	</head>


	<?php include ('header.php'); ?>

			<?php include ('nav_naslovna.php'); ?>
			<div class="form-wrapper1">
				<div class="form-box1">
					<h2>Uloguj se</h2>
					<div class="content">
						
						<form action="log_provera.php" method="post">
							
							<div class="form-row">
								<label for="email">Email:</label>
								<input type="text" id="email" name="email" />
							</div>
							
							<div class="form-row">
								<label for="sifra">Šifra:</label>
								<input type="text" id="sifra" name="sifra" />
							</div>
							<input type="submit"value="Uloguj se"/>
						</form>

					</div>
				</div>
			</div>

			<?php include ('footer.php'); ?>