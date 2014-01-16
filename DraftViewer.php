<html>
<head>
<style type="text/css">
.cardimg {
	height: 136px;
	width: 98px;
	margin: 3px;
}
.pickTd {
	background-color: red;
}
</style>
</head>
<body>
<?php
require_once("DraftFileReader.php");
if (isset($_GET["file"])) {
	$file = $_GET["file"];
}
if (empty($file)) {
	die();
}
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
$nextPick = $pick + 1;
if ($nextPick > 15) {
	$nextPack = $pack + 1;
	$nextPick = 1;
} else {
	$nextPack = $pack;
}
$shouldDisplayPick = false;
if (isset($_GET["shouldDisplayPick"])) {
	$shouldDisplayPick = $_GET["shouldDisplayPick"];
}
$shouldDisplayPickChecked = "";
if ($shouldDisplayPick) {
	$shouldDisplayPickChecked = "checked";
}
?>
<form action="DraftViewer.php?pack=<?=$pack ?>&pick=<?=$pick ?>" method="get">
<input type="number" name="pack" value="<?=$pack ?>">
<input type="number" name="pick" value="<?=$pick ?>">
<input type="checkbox" name="shouldDisplayPick" value="true" <?=$shouldDisplayPickChecked ?>>ピックを表示
<input type="submit" name="submit">
<input type="hidden" name="file" value="<?=$file ?>">
</form>
<a href="DraftViewer.php?file=<?=$file ?>&pack=<?=$nextPack ?>&pick=<?=$nextPick ?>">-> NEXT</a>
<?php
if (!$shouldDisplayPick) {
?>
<a href="DraftViewer.php?file=<?=$file ?>&pack=<?=$pack ?>&pick=<?=$pick ?>&shouldDisplayPick=true">-> PICK</a>
<?php
}
$dfr = new DraftFileReader($file);
$dfr->read();
$candidates = $dfr->getPickCandidates($pack, $pick);
if (count($candidates) > 0) {
?>
<table>
<tr>
<?php
	$rowCount = 0;
	foreach ($candidates as $cardName => $f) {
		if ($shouldDisplayPick && $f) {
			$cardTdClass = "pickTd";
		} else {
			$cardTdClass = "noPickTd";
		}
?>
<td class="<?=$cardTdClass ?>">
<img class="cardimg" src="http://gatherer.wizards.com/Handlers/Image.ashx?size=small&type=card&name=<?=$cardName ?>">
</td>
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