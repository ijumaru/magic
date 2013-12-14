<?php
require_once("DraftFileReader.php");
$dir = "C:\Users\\nr-mc\Documents\Games\Magic The Gathering Online\Drafts";
$dfr = new DraftFileReader($dir."\ult-6467181-12-14-2013.txt");
$dfr->read();
var_dump($dfr);