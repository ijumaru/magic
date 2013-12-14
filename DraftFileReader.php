<?php
class draftFileReader {
	private $filePath;
	private $eventNo;
	private $time;
	private $players = array();
	private $drafter;
	private $packs = array();
	private $isPlayerZone = false;
	private $pickTitlePattern = "/^Pack (\d) pick (\d+):$/";
	private $currentPack;
	private $currentPick;

	/**
	 * pack, pick, card, f
	 * @var unknown
	 */
	private $picks;

	public function __construct($filePath) {
		$this->filePath = $filePath;
	}

	public function read() {
		$fo = new SplFileObject($this->filePath);
		$fo->setFlags(SplFileObject::DROP_NEW_LINE);
		while (!$fo->eof()) {
			$line = trim($fo->fgets());
			if (empty($line)) {
				continue;
			}
			if (strpos($line, "Event #:") === 0) {
				$this->eventNo = trim(str_replace("Event #:", "", $line));
				continue;
			} else if (strpos($line, "Time:") === 0) {
				$this->time = trim(str_replace("Time:", "", $line));
				continue;
			} else if (strpos($line, "Players:") === 0) {
				$this->isPlayerZone = true;
				continue;
			} else if (strpos($line, "------") === 0) {
				$this->packs[] = trim(str_replace("------", "", $line));
				if ($this->isPlayerZone) {
					$this->isPlayerZone = false;
				}
				continue;
			} else if (preg_match($this->pickTitlePattern, $line, $matches)) {
				$this->currentPack = $matches[1];
				$this->currentPick = $matches[2];
				continue;
			}
			if ($this->isPlayerZone) {
				if (strpos($line, "-->") === 0) {
					$this->drafter = trim(str_replace("-->", "", $line));
					$this->players[] = $this->drafter;
				} else {
					$this->players[] = $line;
				}
			} else {
				if (strpos($line, "-->") === 0) {
					$cardName = trim(str_replace("-->", "", $line));
					$f = true;
				} else {
					$cardName = $line;
					$f = false;
				}
				$this->picks[] = array("pack" => $this->currentPack, "pick" => $this->currentPick, "card_name" => $cardName, "f" => $f);
			}
		}
	}
}