<?php

function saveFile($file_path, $with_dir)
{
    $new_file = getNewFileName();

    $new_file_path = FILE_DIR . $new_file['path'];

    $tmp_dir = ini_get('upload_tmp_dir');

    if (empty($tmp_dir)) {
        $tmp_dir = sys_get_temp_dir();
    }

    if (strncmp($file_path, $tmp_dir, strlen($tmp_dir)) === 0) {
        $result = move_uploaded_file($file_path, $new_file_path);
    } else {
        $result = rename($file_path, $new_file_path);
    }

    if (!$result) {
        return 0;
    }

    return ($with_dir ? $new_file['path'] : $new_file['name']);
}

function getRelativePath($filename, $encode = true)
{
    $encoded_file_name = $encode ? rawurlencode($filename) : $filename;

    return getDirForFileName($filename) . '/' . $encoded_file_name;
}

function getDirForFileName($filename)
{
    if (strlen($filename) < 11) {
        return '';
    }

    $dir = $filename[7] . $filename[8];

    if (!is_dir(FILE_DIR . $dir)) {
        mkdir(FILE_DIR . $dir);
    }

    $dir .= '/' . $filename[9] . $filename[10];

    if (!is_dir(FILE_DIR . $dir)) {
        mkdir(FILE_DIR . $dir);
    }

    return $dir;
}

function getNewFileName()
{
    $_newname = str_replace('.', '', uniqid('', true));

    $dir = getDirForFileName($_newname);

    return array('path' => $dir . '/' . $_newname, 'name' => $_newname);
}

function handleUploadedFile($_file)
{
    if (!isset($_FILES[$_file])) {
        return 0;
    }

    if ($_FILES[$_file]['size'] == 0) {
        return 0;
    }

    return saveFile($_FILES[$_file]['tmp_name'], true);
}
