<?php

namespace App\Event;

use App\Character\Character;

class FightStartingEvent {
	public function __construct(public Character $palyer, public Character $ai) {
	}
}
