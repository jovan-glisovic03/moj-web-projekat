
<?php include ('provera_admin.php') ?>


<!DOCTYPE html>
<html lang="sr">
	<head>
		<meta charset="UTF-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<link rel="stylesheet" type="text/css" href="stil.css" />
		<title>Ažuriraj partije</title>
	</head>
	<?php include ('header.php'); ?>

		<?php include ('nav_admin.php'); ?>

			<div class="form-wrapper1">
				<div class="form-box1">
					<h2>Ažuriranje registrovanih partija</h2>
					<div class="content">
						
                        <ul class="partija-list">
							<?php
							$veza = new mysqli("localhost", "root", "", "izbori");
							
							// Uzimamo string iz bilo kog reda (npr. prva opština)
							$rezultat = $veza->query("SELECT BrojGlasovaPoPartijama FROM opstinegradovi LIMIT 1");
							$red = $rezultat->fetch_assoc();
							$stringPartija = $red['BrojGlasovaPoPartijama'];

							if (!empty($stringPartija)) {
								// Razbijamo string na partije (razdvojene su zarezom)
								$partijeNiz = explode(',', $stringPartija);

								foreach ($partijeNiz as $par) {
									// Razbijamo "ImePartije:0" da dobijemo samo "ImePartije"
									$delovi = explode(':', $par);
									$imePartije = trim($delovi[0]);

									if ($imePartije != "") { ?>
										<li>
											<span><?php echo $imePartije; ?></span>
											<div class="buttons-row">
												<a href="izmeni_partiju_forma.php?naziv=<?php echo urlencode($imePartije); ?>">
													<button type="button">Ažuriraj</button>
												</a>
												<a href="obrisi_partiju.php?naziv=<?php echo urlencode($imePartije); ?>" 
												   onclick="return confirm('Da li ste sigurni da želite da obrišete partiju: <?php echo $imePartije; ?>?')">
													<button type="button">Obriši</button>
												</a>
											</div>
										</li>
									<?php }
								}
							} else {
								echo "<li>Nema registrovanih partija.</li>";
							}
							?>
						</ul>
                        </div>
				</div>
			</div>

			<?php include ('footer.php'); ?>