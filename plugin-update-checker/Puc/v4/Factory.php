<?php
class Puc_v4_Factory {
    public static function buildUpdateChecker($url, $pluginFile, $slug) {
        return new self();
    }
    public function getVcsApi() {
        return new class {
            function enableReleaseAssets() {}
        };
    }
}