<?php
/**
* ----------------------------------------------------------------------------------------------------
*  $Source: /WEB/pw4/htdocs/site/templates/tests/scale/height.php,v $
*  $Id: height.php,v 1.1.2.2 2014/05/11 16:02:25 horst Exp $
* ----------------------------------------------------------------------------------------------------
*/

    $options['sharpening'] = 'soft';
    $options['quality']  = 90;
    $options['upscaling'] = false;
    $options['cropping']  = false;
    $testOutPut .= displayOptions($options, array('upscaling'));


    $testOutPut .= "<h4>same size</h4>";
    foreach($images as $image) {
        set_time_limit(15);

        $options['upscaling'] = false;
        $options['appendix']  = ($coreImageSizer ? 'is' : '') . 'hsame';
        $height = $image->height;
        $img = $image->height($height, $options);

        $testOutPut .= "<div class='size up'>";
        #$testOutPut .= "<p>" . str_replace(array('-si', '_', '.', 'size', $height.'x0', 's1q90nu', 's1q90', 'same', '-', 'jpg', 'scale'), array('', ' ', ' ', ''), $img->name) . "<br />";
        $testOutPut .= "<p>";
        $testOutPut .= "<img src='{$img->url}' width='{$img->width}' height='{$img->height}' alt='{$img->name}' title='' /></p>";
        $testOutPut .= "<p" . hnCompareSize($img->height, $height, '==') . ">height from {$image->height} to $height = result: {$img->height}";
        $testOutPut .= "</span></p>";
        $testOutPut .= "</div>\n";
    }
    $testOutPut .= "<div style='clear:both !important;'>&nbsp;</div>";


    $testOutPut .= "<h4>downscale</h4>";
    foreach($images as $image) {
        set_time_limit(15);

        $options['upscaling'] = false;
        $options['appendix']  = ($coreImageSizer ? 'is' : '') . 'hdown';
        $height = intval($image->height / 2);
        $img = $image->height($height, $options);

        $testOutPut .= "<div class='size down'>";
        #$testOutPut .= "<p>" . str_replace(array('-si', '_', '.', 'size', $height.'x0', 's1q90nu', 's1q90', 'down', '-', 'jpg', 'scale'), array('', ' ', ' ', ''), $img->name) . "<br />";
        $testOutPut .= "<p>";
        $testOutPut .= "<img src='{$img->url}' width='{$img->width}' height='{$img->height}' alt='{$img->name}' title='' /></p>";
        $testOutPut .= "<p" . hnCompareSize($img->height, $height, '==') . ">height from {$image->height} to $height = result: {$img->height}";
        $testOutPut .= "</span></p>";
        $testOutPut .= "</div>\n";
    }
    $testOutPut .= "<div style='clear:both !important;'>&nbsp;</div>";


    $testOutPut .= "<h4>upscale true</h4>";
    foreach($images as $image) {
        set_time_limit(15);

        $options['upscaling'] = true;
        $options['appendix']  = ($coreImageSizer ? 'is' : '') . 'hup1';
        $height = $image->height + 10;
        $img = $image->height($height, $options);

        $testOutPut .= "<div class='size up'>";
        #$testOutPut .= "<p>" . str_replace(array('-si', '_', '.', 'size', $height.'x0', 's1q90nu', 's1q90', 'up1', '-', 'jpg', 'scale'), array('', ' ', ' ', ''), $img->name) . "<br />";
        $testOutPut .= "<p>";
        $testOutPut .= "<img src='{$img->url}' width='{$img->width}' height='{$img->height}' alt='{$img->name}' title='' /></p>";
        $testOutPut .= "<p" . hnCompareSize($img->height, $height, '==') . ">height from {$image->height} to $height = result: {$img->height}";
        $testOutPut .= "</span></p>";
        $testOutPut .= "</div>\n";
    }
    $testOutPut .= "<div style='clear:both !important;'>&nbsp;</div>";


    $testOutPut .= "<h4>upscale false</h4>";
    foreach($images as $image) {
        set_time_limit(15);

        $options['upscaling'] = false;
        $options['appendix']  = ($coreImageSizer ? 'is' : '') . 'hup0';
        $height = $image->height + 10;
        $img = $image->height($height, $options);

        $testOutPut .= "<div class='size up'>";
        #$testOutPut .= "<p>" . str_replace(array('-si', '_', '.', 'size', $height.'x0', 's1q90nu', 's1q90', 'up0', '-', 'jpg', 'scale'), array('', ' ', ' ', ''), $img->name) . "<br />";
        $testOutPut .= "<p>";
        $testOutPut .= "<img src='{$img->url}' width='{$img->width}' height='{$img->height}' alt='{$img->name}' title='' /></p>";
        $testOutPut .= "<p" . hnCompareSize($img->height, $image->height, '==') . ">height from {$image->height} to $height = result: {$img->height}";
        $testOutPut .= "</span></p>";
        $testOutPut .= "</div>\n";
    }
    $testOutPut .= "<div style='clear:both !important;'>&nbsp;</div>";


