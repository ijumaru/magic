<html>
<head>
<style type="text/css">
</style>
</head>
<body>
<?php
require_once("DraftFileReader.php");

$file = $_GET["file"];
$dfr = new DraftFileReader($file);
$dfr->read();
if (isset($_GET["pack"])) {
	$pack = $_GET["pack"];
} else {
	$pack = 1;
}
if (isset($_GET["pick"])) {
	$pick = $_GET["pick"];
} else {
	$pick = 1;
}
$candidates = $dfr->getPickCandidates($pack, $pick);
$nextPick = $pick + 1;
if ($nextPick > 15) {
	$nextPack = $pack + 1;
	$nextPick = 1;
} else {
	$nextPack = $pack;
}
?>
<a href="DraftViewer.php?file=<?=$file ?>&pack=<?=$nextPack ?>&pick=<?=$nextPick ?>">-> NEXT</a>
<?php
if (count($candidates) > 0) {
?>
<table>
<tr>
<?php
	$rowCount = 0;
	foreach ($candidates as $cardName => $f) {
?>
<td><img style="height:136px;width:98px;" src="http://gatherer.wizards.com/Handlers/Image.ashx?size=small&type=card&name=<?=$cardName ?>"></td>
<?php
		$rowCount++;
		if ($rowCount == 5) {
?>
</tr><tr>
<?php
			$rowCount = 0;
		}
	}
	for ($i = $rowCount; $i < 5; $i++) {
?>
<td></td>
<?php
	}
}
?>
</tr>
</table>
</body>
</html>