<?php
//ver 1.2

function createPreview($file_src, $file_dest, $size_x, $size_y = 0, $ex = 'out') {
    global $_error;
    $t = getimagesize($file_src);
    $us = 0;

    if ($t[2] == 1) {
        $us = @imagecreatefromgif($file_src);
    }
    if ($t[2] == 2) {
        $us = @imagecreatefromjpeg($file_src);
    }
    if ($t[2] == 3) {
        $us = @imagecreatefrompng($file_src);
    }
    if ($t[2] == 6) {
        $us = @r_imagecreatefrombmp($file_src);
    }

    if (!$us) {
        $_error['image'] = 1;

        return 0;
    }

    $x = imagesx($us);
    $y = imagesy($us);
    $f_src = $x / $y;

    if ($ex == 'bylong') {
        if ($x > $y) {
            $size_y = $size_x / $f_src;
        } else {
            $size_x = $size_y * $f_src;
        }
    }

    if ($size_y == 0) {
        $size_y = $size_x / $f_src;
    }
    if ($size_x == 0) {
        $size_x = $size_y * $f_src;
    }

    $f_dest = $size_x / $size_y;

    if ($size_x > $x && $size_y > $y) {
        copy($file_src, $file_dest);

        return 0;
    }

    if ($ex == 'cut')//inner cut
    {
        $f_dest = $size_x / $size_y;
        if ($f_dest > $f_src) {
            $new_w = $x;
            $new_h = $x / $f_dest;
            $new_x = 0;
            $new_y = ($y - $new_h) / 2;
        } else {
            $new_w = $y * $f_dest;
            $new_h = $y;
            $new_x = ($x - $new_w) / 2;
            $new_y = 0;
        }

        $rez = imagecreatetruecolor($size_x, $size_y);
        imagecopyresampled($rez, $us, 0, 0, $new_x, $new_y, $size_x, $size_y, $new_w, $new_h);
    }

    if ($ex == 'out' || $ex == 'bylong')//outer
    {

        if ($x > $y) {
            $new_w = $size_x;
            $new_h = $new_w / $f_src;
            $new_x = 0;
            $new_y = ($size_y - $new_h) / 2;
        } else {
            $new_h = $size_y;
            $new_w = $new_h * $f_src;
            $new_y = 0;
            $new_x = ($size_x - $new_w) / 2;
        }

        $rez = imagecreatetruecolor($new_w, $new_h);

        $bg = imagecolorallocate($rez, 255, 255, 255);
        imagefill($rez, 1, 1, $bg);

        imagecopyresampled($rez, $us, 0, 0, 0, 0, $new_w, $new_h, $x, $y);
    }

    if ($ex[0] == '#')//outer fill
    {

        if ($x > $y) {
            $new_w = $size_x;
            $new_h = $new_w / $f_src;
            $new_x = 0;
            $new_y = ($size_y - $new_h) / 2;
        } else {
            $new_h = $size_y;
            $new_w = $new_h * $f_src;
            $new_y = 0;
            $new_x = ($size_x - $new_w) / 2;
        }

        $rez = imagecreatetruecolor($size_x, $size_y);
        $color = $ex . 'FFFFFF';
        $fill_r = hexdec($color[1] . $color[2]);
        $fill_g = hexdec($color[3] . $color[4]);
        $fill_b = hexdec($color[5] . $color[6]);

        $bg = imagecolorallocate($rez, $fill_r, $fill_g, $fill_b);
        imagefill($rez, 1, 1, $bg);
        imagecopyresampled($rez, $us, $new_x, $new_y, 0, 0, $new_w, $new_h, $x, $y);
    }

    imagejpeg($rez, $file_dest, 80);

    return 1;
}

function r_imagecreatefrombmp($p_sFile) {
    //    Load the image into a string
    $file = fopen($p_sFile, "rb");
    $read = fread($file, 10);
    while (!feof($file) && ($read <> "")) {
        $read .= fread($file, 1024);
    }

    $temp = unpack("H*", $read);
    $hex = $temp[1];
    $header = substr($hex, 0, 108);

    //    Process the header
    //    Structure: http://www.fastgraph.com/help/bmp_header_format.html
    if (substr($header, 0, 4) == "424d") {
        //    Cut it in parts of 2 bytes
        $header_parts = str_split($header, 2);

        //    Get the width        4 bytes
        $width = hexdec($header_parts[19] . $header_parts[18]);

        //    Get the height        4 bytes
        $height = hexdec($header_parts[23] . $header_parts[22]);

        //    Unset the header params
        unset($header_parts);
    }

    //    Define starting X and Y
    $x = 0;
    $y = 1;

    //    Create newimage
    $image = imagecreatetruecolor($width, $height);

    //    Grab the body from the image
    $body = substr($hex, 108);

    //    Calculate if padding at the end-line is needed
    //    Divided by two to keep overview.
    //    1 byte = 2 HEX-chars
    $body_size = (strlen($body) / 2);
    $header_size = ($width * $height);

    //    Use end-line padding? Only when needed
    $usePadding = ($body_size > ($header_size * 3) + 4);

    //    Using a for-loop with index-calculation instaid of str_split to avoid large memory consumption
    //    Calculate the next DWORD-position in the body
    for ($i = 0; $i < $body_size; $i += 3) {
        //    Calculate line-ending and padding
        if ($x >= $width) {
            //    If padding needed, ignore image-padding
            //    Shift i to the ending of the current 32-bit-block
            if ($usePadding) {
                $i += $width % 4;
            }

            //    Reset horizontal position
            $x = 0;

            //    Raise the height-position (bottom-up)
            $y++;

            //    Reached the image-height? Break the for-loop
            if ($y > $height) {
                break;
            }
        }

        //    Calculation of the RGB-pixel (defined as BGR in image-data)
        //    Define $i_pos as absolute position in the body
        $i_pos = $i * 2;
        $r = hexdec($body[$i_pos + 4] . $body[$i_pos + 5]);
        $g = hexdec($body[$i_pos + 2] . $body[$i_pos + 3]);
        $b = hexdec($body[$i_pos] . $body[$i_pos + 1]);

        //    Calculate and draw the pixel
        $color = imagecolorallocate($image, $r, $g, $b);
        imagesetpixel($image, $x, $height - $y, $color);

        //    Raise the horizontal position
        $x++;
    }

    //    Unset the body / free the memory
    unset($body);

    //    Return image-object
    return $image;
}

?>