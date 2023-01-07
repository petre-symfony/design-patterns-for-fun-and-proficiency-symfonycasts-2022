<?php

namespace App\Builder;

use Psr\Log\LoggerInterface;

class CharacterBuilderFactory {
	public function __construct(private LoggerInterface $logger) {
	}

	public function createBuilder(): CharacterBuilder{
		if ($this->someConfig) {
			return new CharacterBuilder($this->logger);
		} else {
			// other character builder
		}
	}
}
