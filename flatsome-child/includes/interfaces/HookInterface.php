<?php
namespace includes\interfaces;

interface HookInterface {
	public function init();
	public function registerAction();
	public function registerFilter();
	public function registerAsset();
}