<?php

namespace mod\cp;

use mod\reactor\lib\UploadHandler\FileUploadHandler;

require_once(MOD_DIR . '/reactor/lib/UploadHandler/UploadHandler.php');
require_once(MOD_DIR . '/reactor/lib/UploadHandler/FileUploadHandler.php');

class UploadController {
    public function uploadImage() {
        $upload_handler = new FileUploadHandler();
        die();
    }
}
