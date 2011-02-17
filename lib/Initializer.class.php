<?php
/**
 *   @copyright Copyright (c) 2007 Quality Unit s.r.o.
 *   @author Juraj Simon
 *   @package WpPostAffiliateProPlugin
 *   @version 1.0.0
 *
 *   Licensed under GPL2
 */

include WP_PLUGIN_DIR . '/postaffiliatepro/lib/LibraryInitializationExceptiopn.class.php';

if (!class_exists('postaffiliatepro_lib_Initializer')) {
    class postaffiliatepro_lib_Initializer {
        public function initLibraries() {
            $this->initLib(WP_PLUGIN_DIR . '/postaffiliatepro/lib/forms/class.htmlform.php');
        }

        private function initLib($pathToFile) {
            if (!file_exists($pathToFile)) {
                throw new LibraryInitializationExceptiopn('File ' . $pathToFile . ' do NOT exist! Can not continue.');
            }
            require_once $pathToFile;
        }
    }
}
?>