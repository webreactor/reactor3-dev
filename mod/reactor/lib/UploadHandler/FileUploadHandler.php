<?php

namespace mod\reactor\lib\UploadHandler;

class FileUploadHandler extends UploadHandler
{
    function __construct($options = null, $initialize = true, $error_messages = null)
    {
        parent::__construct($options, false, $error_messages);
        unset($this->options['image_versions']['thumbnail']);
        $this->options['upload_dir'] = FILE_DIR;
        $this->options['upload_url'] = FILE_URL;
        if ($initialize) {
            $this->initialize();
        }
    }

    protected function get_download_url($file_name, $version = null, $direct = false)
    {
        $url = parent::get_download_url($file_name, $version, $direct);
        $encoded_file_name = rawurlencode($file_name);

        return str_replace($encoded_file_name, getRelativePath($file_name), $url);
    }

    protected function handle_file_upload(
        $uploaded_file,
        $name,
        $size,
        $type,
        $error,
        $index = null,
        $content_range = null
    ) {
        $file = parent::handle_file_upload($uploaded_file, $name, $size, $type, $error, $index, $content_range);
        if (property_exists($file, 'url')) {
            $file_path = $this->get_upload_path($file->name);
            $new_name = saveFile($file_path, false);
            $file->name = $new_name;
            $file->url = $this->get_download_url($new_name);
        }

        return $file;
    }
}