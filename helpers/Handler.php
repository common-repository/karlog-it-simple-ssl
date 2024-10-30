<?php

namespace HTTPS_Direct\Helpers;

class Handler
{
    public static $status = [false, false];
    //_ PRIVATE  _//
    private static $_instance = null;

    private $htaccessPath;

    private $htaccessStr =
        '
# BEGIN HTTPS_DIRECT
<IfModule mod_rewrite.c>
RewriteEngine On
RewriteCond %{HTTPS} !=on
RewriteRule ^(.*)$ https://%{HTTP_HOST}%{REQUEST_URI} [L,R=301,NE]
</IfModule>
# END HTTPS_DIRECT';

    private function __construct()
    {
        add_action('wp_ajax_https_enable', [$this, 'https_enable']);
        add_action('wp_ajax_https_disable', [$this, 'https_disable']);
        $this->htaccessPath = ABSPATH . '.htaccess';
        $this->_setStatus();
    }

    public static function Instance()
    {
        if (is_null(self::$_instance)) {
            self::$_instance = new self();
        }

        return self::$_instance;
    }

    public static function getStatus()
    {
        return self::$status;
    }

    public function uninstall()
    {
        $this->_setStatus('r');
    }

    private function _setStatus($edit = null, $index = null, $value = null)
    {
        $get_file = file_get_contents($this->htaccessPath);
        $urls = [explode('://', get_option('home')), explode('://', get_option('siteurl'))];

        if (!is_null($edit)) {
            if ($edit == 'a') {
                $match = preg_match('/# BEGIN HTTPS_DIRECT\n([^#]*)# END HTTPS_DIRECT/m', $get_file);
                if ($match == 0) {
                    file_put_contents($this->htaccessPath, $this->remove_whitespace($this->htaccessStr), FILE_APPEND);
                    $this->_setStatus(null, true);
                    update_option('home', 'https://' . $urls[0][1]);
                    update_option('siteurl', 'https://' . $urls[1][1]);
                    $this->_setStatus(null, 1, true);
                }
            } else if ($edit == 'r') {
                $match = preg_replace('/# BEGIN HTTPS_DIRECT\n([^#]*)# END HTTPS_DIRECT/m', '', $get_file);
                //Remove from htaccess
                file_put_contents($this->htaccessPath, $this->remove_whitespace($match));
                $this->_setStatus(null, 0, false);
                update_option('home', 'http://' . $urls[0][1]);
                update_option('siteurl', 'http://' . $urls[1][1]);
                $this->_setStatus(null, 1, false);
            }
            return;
        } else {
            if (is_null($index) and is_null($value)) {
                // File
                $match = preg_match('/# BEGIN HTTPS_DIRECT\n([^#]*)# END HTTPS_DIRECT/m', $get_file);

                if ($match) {
                    $this->_setStatus(null, 0, true);
                } else {
                    $this->_setStatus(null, 0, false);
                }

                //Options
                if ($urls[0][0] == 'https' and $urls[1][0] == 'https') {
                    $this->_setStatus(null, 1, true);
                } else {
                    $this->_setStatus(null, 1, false);
                }
                return;
            }
        }
        self::$status[$index] = $value;
    }

    function remove_whitespace($string){
        return preg_replace("/(^[\r\n]*|[\r\n]+)[\s\t]*[\r\n]+/", "\n", $string);
    }


    function https_enable()
    {
        $this->_setStatus('a');
        die();
    }

    function https_disable()
    {
        $this->_setStatus('r');
        die();
    }
}