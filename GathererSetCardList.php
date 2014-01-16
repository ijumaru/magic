<?php
class GathererSetCardList {
	private $set = "";
	private $url = "";
	private $isTest = false;
	private $list = array();
	private $cardUrl = "../Card/Details.aspx?multiverseid=";
	private $nameListMap;

	public function __construct($set) {
		$this->set = $set;
		$this->url = "http://gatherer.wizards.com/Pages/Search/Default.aspx?output=checklist&set=%5b%22".$this->set."%22%5d";
	}

	public function read() {
		if ($this->isTest) {
			$this->url = 'test.html';
		}
		if (count(glob('./set/'.$this->set.".html") > 0)) {
			$this->copyToLocal();
		}
		$this->url = './set/'.$this->set.".html";
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
					if (isset($this->list[$index]["name"])) {
						$this->nameListMap[$this->list[$index]["name"]] = $this->list[$index];
					}
				}
			}
		}
	}

	public function copyToLocal() {
		if (!file_exists('.'.DIRECTORY_SEPARATOR.'set') || !is_dir('.'.DIRECTORY_SEPARATOR.'set')) {
			mkdir('.'.DIRECTORY_SEPARATOR.'set');
		}
		file_put_contents('.'.DIRECTORY_SEPARATOR.'set'.DIRECTORY_SEPARATOR.$this->set.".html", file_get_contents($this->url));
	}

	public function setIsTest($isTest) {
		$this->isTest = $isTest;
	}

	public function getList() {
		return $this->list;
	}

	public function getNameListMap() {
		return $this->nameListMap;
	}
}
