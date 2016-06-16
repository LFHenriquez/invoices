<html>
	<head>
		<meta content="text/html; charset=UTF-8" http-equiv="content-type">
		<style type="text/css">
			.invoice-box{
				max-width:800px;
				margin:auto;
				padding:30px;
				border:1px solid #eee;
				box-shadow:0 0 10px rgba(0, 0, 0, .15);
				font-size:16px;
				line-height:24px;
				font-family:'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;
				color:#555;
			}

			.invoice-box table{
				width:100%;
				line-height:inherit;
				text-align:left;
			}

			.invoice-box table td{
				padding:5px;
				vertical-align:top;
			}

			.invoice-box table tr td:nth-child(2){
				text-align:right;
			}

			.invoice-box table tr.top table td{
				padding-bottom:20px;
			}

			.invoice-box table tr.top table td.title{
				font-size:45px;
				line-height:45px;
				color:#333;
			}

			.invoice-box table tr.information table td{
				padding-bottom:40px;
			}

			.invoice-box table tr.heading td{
				background:#eee;
				border-bottom:1px solid #ddd;
				font-weight:bold;
			}

			.invoice-box table tr.details td{
				padding-bottom:20px;
			}

			.invoice-box table tr.item td{
				border-bottom:1px solid #eee;
			}

			.invoice-box table tr.item.last td{
				border-bottom:none;
			}

			.invoice-box table tr.total td:nth-child(2){
				border-top:2px solid #eee;
				font-weight:bold;
			}

			@media only screen and (max-width: 600px) {
				.invoice-box table tr.top table td{
					width:100%;
					display:block;
					text-align:center;
				}

				.invoice-box table tr.information table td{
					width:100%;
					display:block;
					text-align:center;
				}
			}

			.mini {
				font-size: xx-small;
				font-style: italic;
			}

			.left {
				text-align: left;
			}
		</style>
	</head>
	<body>
		<div class="invoice-box">
			<table cellpadding="0" cellspacing="0">
				<tr class="information">
					<td colspan="2">
						<?= (isset($firstname) && isset($lastname))? $firstname . " " . $lastname : "?" ?><br>
						<?= (isset($adress))? $adress : "?" ?><br>
						<?= $zip . " " . $city ?><br>
						SIRET: <?= $siren ?>
					</td>
					<td colspan="2">
						Blackbird SAS<br>
						22 rue du Faubourg Saint-Martin<br>
						75010 Paris<br>
						SIRET: 750 286 874 00011
					</td>
				</tr>
				<tr class="details">
				<td colspan="4">
					<p class="mini">Dispensé  d'immatriculation au Registre du Commerce et des
						Sociétés et au Répertoire des Métiers</p>
					</td>
				</tr>
				<tr>
					<td colspan="4">
						<p>Facture n°<?= (isset($invoice_id))? $invoice_id : "?" ?><br>
							Date :<?= (isset($date))? $date : "?" ?><br>
							Durée : 1 mois
						</p>
					</td>
				</tr>

				<tr class="heading">
					<td>
						Quantité
					</td>
					<td>
						Prestation
					</td>
					<td>
						Prix Unitaire
					</td>
					<td>
						Total HT
					</td>
				</tr>

				<tr class="item">
					<td>
						<?= (isset($messages_count))? $messages_count : "?" ?>
					</td>
					<td>
						SMS
					</td>
					<td>
						<?= (isset($unitary_price))? $unitary_price : "?" ?>€
					</td>
					<td>
						<?= (isset($price))? $price : "?" ?>€
					</td>
				</tr>

				<tr class="total">
					<td colspan="3" class='mini'>
						TVA non applicable, art. 293B du CGI
					</td>

					<td>
						Total: <?= (isset($price))? $price : "?" ?>€
					</td>
				</tr>
				<tr>
					<td colspan="4">
						<br>
						<p>Date de livraison : <?= (isset($date))? $date : "?" ?><br>
							Date limite de règlement : <?= (isset($payment_date))? $payment_date : "?" ?><br>
							Mode de règlement : Virement<br>
							Nom du titulaire du compte bancaire : <?= (isset($bank_account_name))? $bank_account_name : "?" ?><br>
							IBAN : <?= (isset($iban))? $iban : "?" ?>
						</p>
						<p class="mini">
							Taux des pénalités en l'absence de paiement : taux légal en vigueur à la date d'émission de la facture<br>
							Escompte en cas de paiement anticipé : aucun
						</p>
					</td>
				</tr>
			</table>
		</div>
	</body>
</html>