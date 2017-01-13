<?php

abstract class BatchAbstract {
	function __construct() {

	}
	abstract function process();

	protected function preProcess() {
		Log::info([get_class($this), 'execute: start' ]);
	}
	function execute() {
		$this->preProcess();
		$this->process();
		$this->postProcess();
	}
	protected function postProcess() {
		Log::info([get_class($this), 'execute: finish' ]);
	}

	function __destruct() {

	}
}