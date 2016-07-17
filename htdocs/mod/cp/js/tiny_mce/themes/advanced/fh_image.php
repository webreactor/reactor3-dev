<?php

$_SERVER['REQUEST_URI'] = '/';
require '../../../../../../bin/load_core.php';

$file_name = handleUploadedFile('file');
header("Location: image.htm?src=" . FILE_URL . $file_name);
