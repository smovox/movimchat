<?php

namespace moxl;

class MoxlLogger {    
    public static function log($message, $priority = '') 
    {
        openlog('moxl', LOG_NDELAY, LOG_USER);
        $errlines = explode("\n",$message);
        foreach ($errlines as $txt) { syslog(LOG_DEBUG, $txt); } 
        closelog();
    }
}
