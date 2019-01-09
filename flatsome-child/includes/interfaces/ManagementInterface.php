<?php
namespace includes\interfaces;

interface ManagementInterface {
	public static function init();
	public function registerHook();
	public function registerHelper();
}