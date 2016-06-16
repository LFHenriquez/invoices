<h2>Validation</h2>
<?php
$translate = [
		'date' => 'date',
		'signed' => 'signature',
		'integrity' => 'intégrité',
		];
foreach($validation as $key => $value)
	echo "<p>", $translate[$key], " : ", ($value)? "OK": "non valide", "</p>";
	echo "<br><p>", $message, "</p>";
?>