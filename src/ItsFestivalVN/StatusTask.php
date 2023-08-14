<?php

declare(strict_types=1);

namespace ItsFestivalVN;

use pocketmine\scheduler\Task;
use ItsFestivalVN\DailyAttendance;

class StatusTask extends Task {

	private DailyAttendance $plugin;

	public function __construct(DailyAttendance $plugin){
		$this->plugin = $plugin;
	}

	public function onRun() : void {
		$this->plugin->reset();
	}
}