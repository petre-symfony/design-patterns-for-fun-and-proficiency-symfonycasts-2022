<?php

namespace App\AttackType;

use App\Dice;

class FireboltType implements AttackType {
	public function performAttack(int $baseDamage): int {
		return Dice::roll(10) + Dice::roll(10) + Dice::roll(10);
	}

}
