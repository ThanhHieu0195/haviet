<?php
namespace includes\interfaces;

interface PostTypeInterface {
	public static function className();
	public function registerPostType();
	public function getName();
	public function getConfigs();
	public function getAttributes();
}