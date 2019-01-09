<?php

namespace includes\interfaces;

interface ShortcodeInterface {
    public function register();
    public function render($attrs);
}