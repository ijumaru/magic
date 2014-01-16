<?php
class GathererCard {
	private $multiverseid;
	private $type;
	private $cmc;

	public function __construct($multiverseid) {
		$this->multiverseid = $multiverseid;
	}

	public function read() {
		$cardPath = '.'.DIRECTORY_SEPARATOR.'card'.DIRECTORY_SEPARATOR.$this->multiverseid.".html";
		if (count(glob("./card/".$this->multiverseid.".html")) === 0) {
			if (!file_exists('.'.DIRECTORY_SEPARATOR.'card') || !is_dir('.'.DIRECTORY_SEPARATOR.'card')) {
				mkdir('.'.DIRECTORY_SEPARATOR.'card');
			}
			$contents =  file_get_contents("http://gatherer.wizards.com/Pages/Card/Details.aspx?multiverseid=".$this->multiverseid);
			file_put_contents($cardPath, $contents);
		}
		@$domdoc = DOMDocument::loadHTML(file_get_contents($cardPath));
		if ($domdoc) {
			$xpath = new DOMXPath($domdoc);
			$cmcItems = $xpath->query('//*[@id="ctl00_ctl00_ctl00_MainContent_SubContent_SubContent_cmcRow"]/div[2]');
			$typeItems = $xpath->query('//*[@id="ctl00_ctl00_ctl00_MainContent_SubContent_SubContent_typeRow"]/div[2]');
			$cmcItem = $cmcItems->item(0);
			if (isset($cmcItem)) {
				$this->cmc = trim($cmcItem->nodeValue);
			}
			$this->type = trim($typeItems->item(0)->nodeValue);
		}
	}

	public function getType() {
		return $this->type;
	}

	public function getCMC() {
		return $this->cmc;
	}
}
