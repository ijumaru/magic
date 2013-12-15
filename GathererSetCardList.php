<?php
class GathererSetCardList {
	private $set = "";
	private $url = "";
	private $isTest = false;
	private $list = array();
	private $cardUrl = "../Card/Details.aspx?multiverseid=";

	public function __construct($set) {
		$this->set = $set;
		$this->url = "http://gatherer.wizards.com/Pages/Search/Default.aspx?output=checklist&set=%5b%22".$this->set."%22%5d";
	}

	public function read() {
		if ($this->isTest) {
			$this->url = 'test.html';
		}
		@$domdoc = DOMDocument::loadHTML(file_get_contents($this->url));
		if ($domdoc) {
			$xpath = new DOMXPath($domdoc);
			$cardItems = $xpath->query('//tr[@class="cardItem"]');
			foreach ($cardItems as $item) {
				$trs = $item->getElementsByTagName("td");
				$index = count($this->list);
				for ($i = 0; $i < $trs->length; $i++) {
					$attr = $trs->item($i)->getAttribute("class");
					if ($attr === "name") {
						$a = $trs->item($i)->getElementsByTagName("a");
						$href = $a->item(0)->getAttribute("href");
						$this->list[$index]["multiverseid"] = str_replace($this->cardUrl, "", $href);
					}
					$this->list[$index][$attr] = $trs->item($i)->nodeValue;
				}
			}
		}
	}

	public function copyToLocal() {
		file_put_contents('test.html', file_get_contents($this->url));
	}

	public function setIsTest($isTest) {
		$this->isTest = $isTest;
	}
}
