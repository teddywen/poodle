<?php

/**
 * Do some global things
 */
class Util {
	static private $_time = 0;
    private static $_c_data_formatter;

    static public function time() {
        if (self::$_time == 0) {
            self::$_time = time();
        }
        return self::$_time;
    }

    public static function getCDataFormatter() {
        $locale = CLocale::getInstance('zh');

        if(!(self::$_c_data_formatter instanceof CDateFormatter)) {
            self::$_c_data_formatter = new CDateFormatter($locale);
        }
        return self::$_c_data_formatter;
    }

    public static function timestamp2date($timestamp) {
        $df = self::getCDataFormatter();
        return $df->formatDateTime($timestamp);
    }


}