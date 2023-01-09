<?php

namespace App\Event;

use App\Character\Character;

class FightStartingEvent {
	public $shouldBattle = true;

	public function __construct(public Character $palyer, public Character $ai) {
	}
}
