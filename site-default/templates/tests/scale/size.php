<?php
/**
* ----------------------------------------------------------------------------------------------------
*  $Source: /WEB/pw4/htdocs/site/templates/tests/scale/size.php,v $
*  $Id: size.php,v 1.1.2.2 2014/05/11 16:02:25 horst Exp $
* ----------------------------------------------------------------------------------------------------
*/

    $options['sharpening'] = 'soft';
    $options['quality']  = 90;
    $options['upscaling'] = false;
    $options['cropping']  = false;
    $testOutPut .= displayOptions($options, array('upscaling'));


    $testOutPut .= "<h4>upscale true</h4>";
    foreach($images as $image) {
        set_time_limit(15);

        $options['upscaling'] = true;
        $options['appendix']  = ($coreImageSizer ? 'is' : '') . 'sup1';
        $width = intval($image->width / 18 * 19);
        $height = intval($image->height / 18 * 19);
        $img = $image->size($width, $height, $options);

        $testOutPut .= "<div class='size up'>";
        #$testOutPut .= "<p>" . str_replace(array('-si', '_', '.', 'size', $width.'x0', 's1q90nu', 's1q90', 'up1', '-', 'jpg', 'scale'), array('', ' ', ' ', ''), $img->name) . "<br />";
        $testOutPut .= "<p>";
        $testOutPut .= "<img src='{$img->url}' width='{$img->width}' height='{$img->height}' alt='{$img->name}' title='' /></p>";
        $testOutPut .= "<p" . hnCompareSize($img->width, $width, '==') . ">width from {$image->width} to $width = result: {$img->width}";
        $testOutPut .= "<br />height from {$image->height} to $height = result: {$img->height}";
        $testOutPut .= "</span></p>";
        $testOutPut .= "</div>\n";
    }
    $testOutPut .= "<div style='clear:both !important;'>&nbsp;</div>";


    $testOutPut .= "<h4>upscale false</h4>";
    foreach($images as $image) {
        set_time_limit(15);

        $options['upscaling'] = false;
        $options['appendix']  = ($coreImageSizer ? 'is' : '') . 'sup0';
        $width = intval($image->width / 18 * 19);
        $height = intval($image->height / 18 * 19);
        $img = $image->size($width, $height, $options);

        $testOutPut .= "<div class='size up'>";
        #$testOutPut .= "<p>" . str_replace(array('-si', '_', '.', 'size', $width.'x0', 's1q90nu', 's1q90', 'up0', '-', 'jpg', 'scale'), array('', ' ', ' ', ''), $img->name) . "<br />";
        $testOutPut .= "<p>";
        $testOutPut .= "<img src='{$img->url}' width='{$img->width}' height='{$img->height}' alt='{$img->name}' title='' /></p>";
        $testOutPut .= "<p" . hnCompareSize($img->width, $width, '<=') . ">width from {$image->width} to $width = result: {$img->width}";
        $testOutPut .= "<br />height from {$image->height} to $height = result: {$img->height}";
        $testOutPut .= "</span></p>";
        $testOutPut .= "</div>\n";
    }
    $testOutPut .= "<div style='clear:both !important;'>&nbsp;</div>";


    $testOutPut .= "<h4>downscale proportional</h4>";
    foreach($images as $image) {
        set_time_limit(15);

        $options['upscaling'] = false;
        $options['appendix']  = ($coreImageSizer ? 'is' : '') . 'sdown';
        $width = intval($image->width / 2);
        $height = intval($image->height / 2);
        $img = $image->size($width, $height, $options);

        $testOutPut .= "<div class='size down'>";
        #$testOutPut .= "<p>" . str_replace(array('-si', '_', '.', 'size', $width.'x0', 's1q90nu', 's1q90', 'down', '-', 'jpg', 'scale'), array('', ' ', ' ', ''), $img->name) . "<br />";
        $testOutPut .= "<p>";
        $testOutPut .= "<img src='{$img->url}' width='{$img->width}' height='{$img->height}' alt='{$img->name}' title='' /></p>";
        $testOutPut .= "<p" . hnCompareSize($img->width, $width, '==') . ">width from {$image->width} to $width = result: {$img->width}";
        $testOutPut .= "<br />height from {$image->height} to $height = result: {$img->height}";
        $testOutPut .= "</span></p>";
        $testOutPut .= "</div>\n";
    }
    $testOutPut .= "<div style='clear:both !important;'>&nbsp;</div>";


    $testOutPut .= "<h4>downscale unproportional cropping true</h4>";
    foreach($images as $image) {
        set_time_limit(15);

        $options['cropping'] = true;
        $options['upscaling'] = false;
        $options['appendix']  = ($coreImageSizer ? 'is' : '') . 'scrop1';
        $width = intval($image->height / 2);
        $height = intval($image->width / 2);
        $img = $image->size($width, $height, $options);

        $testOutPut .= "<div class='size up'>";
        #$testOutPut .= "<p>" . str_replace(array('-si', '_', '.', 'size', $width.'x0', 's1q90nu', 's1q90', 'up0', '-', 'jpg', 'scale'), array('', ' ', ' ', ''), $img->name) . "<br />";
        $testOutPut .= "<p>";
        $testOutPut .= "<img src='{$img->url}' width='{$img->width}' height='{$img->height}' alt='{$img->name}' title='' /></p>";
        $compare = $image->width > $image->height ? 'width' : 'height';
        $testOutPut .= "<p" . hnCompareSize($img->$compare, $$compare, '==') . ">width from {$image->width} to $width = result: {$img->width}";
        $testOutPut .= "<br />height from {$image->height} to $height = result: {$img->height}";
        $testOutPut .= "</span></p>";
        $testOutPut .= "</div>\n";
    }
    $testOutPut .= "<div style='clear:both !important;'>&nbsp;</div>";


    $testOutPut .= "<h4>downscale unproportional cropping false</h4>";
    foreach($images as $image) {
        set_time_limit(15);

        $options['cropping'] = false;
        $options['upscaling'] = false;
        $options['appendix']  = ($coreImageSizer ? 'is' : '') . 'scrop0';
        $width = intval($image->height / 2);
        $height = intval($image->width / 2);
        $img = $image->size($width, $height, $options);

        $testOutPut .= "<div class='size up'>";
        #$testOutPut .= "<p>" . str_replace(array('-si', '_', '.', 'size', $width.'x0', 's1q90nu', 's1q90', 'up0', '-', 'jpg', 'scale'), array('', ' ', ' ', ''), $img->name) . "<br />";
        $testOutPut .= "<p>";
        $testOutPut .= "<img src='{$img->url}' width='{$img->width}' height='{$img->height}' alt='{$img->name}' title='' /></p>";
        $compare = $image->width > $image->height ? 'width' : 'height';
        $testOutPut .= "<p" . hnCompareSize($img->$compare, $$compare, '==') . ">width from {$image->width} to $width = result: {$img->width}";
        $testOutPut .= "<br />height from {$image->height} to $height = result: {$img->height}";
        $testOutPut .= "</span></p>";
        $testOutPut .= "</div>\n";
    }
    $testOutPut .= "<div style='clear:both !important;'>&nbsp;</div>";



    $testOutPut .= "<h4>same size</h4>";
    foreach($images as $image) {
        set_time_limit(15);

        $options['upscaling'] = false;
        $options['appendix']  = ($coreImageSizer ? 'is' : '') . 'ssame';
        $width = $image->width;
        $height = $image->height;
        $img = $image->size($width, $height, $options);

        $testOutPut .= "<div class='size up'>";
        #$testOutPut .= "<p>" . str_replace(array('-si', '_', '.', 'size', $width.'x0', 's1q90nu', 's1q90', 'same', '-', 'jpg', 'scale'), array('', ' ', ' ', ''), $img->name) . "<br />";
        $testOutPut .= "<p>";
        $testOutPut .= "<img src='{$img->url}' width='{$img->width}' height='{$img->height}' alt='{$img->name}' title='' /></p>";
        $testOutPut .= "<p" . hnCompareSize($img->width, $width, '==') . ">width from {$image->width} to $width = result: {$img->width}";
        $testOutPut .= "<br />height from {$image->height} to $height = result: {$img->height}";
        $testOutPut .= "</span></p>";
        $testOutPut .= "</div>\n";
    }
    $testOutPut .= "<div style='clear:both !important;'>&nbsp;</div>";

