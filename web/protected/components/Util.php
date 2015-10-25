<?php

/**
 * Do some global things
 */
class Util {
	static private $_time = 0;

    static public function time() {
        if (self::$_time == 0) {
            self::$_time = time();
        }
        return self::$_time;
    }
}