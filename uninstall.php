<?php
if (!defined('WP_UNINSTALL_PLUGIN')) exit;
require('helpers/Handler.php');
\HTTPS_Direct\Helpers\Handler::Instance()->uninstall();
