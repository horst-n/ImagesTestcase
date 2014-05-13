<?php
/**
* ----------------------------------------------------------------------------------------------------
*  $Source: /WEB/pw4/htdocs/site/templates/tests/scale/width.php,v $
*  $Id: width.php,v 1.1.2.2 2014/05/11 16:02:25 horst Exp $
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
        $options['appendix']  = ($coreImageSizer ? 'is' : '') . 'wsame';
        $width = $image->width;
        $img = $image->width($width, $options);

        $testOutPut .= "<div class='size up'>";
        #$testOutPut .= "<p>" . str_replace(array('-si', '_', '.', 'size', $width.'x0', 's1q90nu', 's1q90', 'same', '-', 'jpg', 'scale'), array('', ' ', ' ', ''), $img->name) . "<br />";
        $testOutPut .= "<p>";
        $testOutPut .= "<img src='{$img->url}' width='{$img->width}' height='{$img->height}' alt='{$img->name}' title='' /></p>";
        $testOutPut .= "<p" . hnCompareSize($img->width, $width, '==') . ">width from {$image->width} to $width = result: {$img->width}";
        $testOutPut .= "</span></p>";
        $testOutPut .= "</div>\n";
    }
    $testOutPut .= "<div style='clear:both !important;'>&nbsp;</div>";


    $testOutPut .= "<h4>downscale</h4>";
    foreach($images as $image) {
        set_time_limit(15);

        $options['upscaling'] = false;
        $options['appendix']  = ($coreImageSizer ? 'is' : '') . 'wdown';
        $width = intval($image->width / 2);
        $img = $image->width($width, $options);

        $testOutPut .= "<div class='size down'>";
        #$testOutPut .= "<p>" . str_replace(array('-si', '_', '.', 'size', $width.'x0', 's1q90nu', 's1q90', 'down', '-', 'jpg', 'scale'), array('', ' ', ' ', ''), $img->name) . "<br />";
        $testOutPut .= "<p>";
        $testOutPut .= "<img src='{$img->url}' width='{$img->width}' height='{$img->height}' alt='{$img->name}' title='' /></p>";
        $testOutPut .= "<p" . hnCompareSize($img->width, $width, '==') . ">width from {$image->width} to $width = result: {$img->width}";
        $testOutPut .= "</span></p>";
        $testOutPut .= "</div>\n";
    }
    $testOutPut .= "<div style='clear:both !important;'>&nbsp;</div>";


    $testOutPut .= "<h4>upscale true</h4>";
    foreach($images as $image) {
        set_time_limit(15);

        $options['upscaling'] = true;
        $options['appendix']  = ($coreImageSizer ? 'is' : '') . 'wup1';
        $width = $image->width + 10;
        $img = $image->width($width, $options);

        $testOutPut .= "<div class='size up'>";
        #$testOutPut .= "<p>" . str_replace(array('-si', '_', '.', 'size', $width.'x0', 's1q90nu', 's1q90', 'up1', '-', 'jpg', 'scale'), array('', ' ', ' ', ''), $img->name) . "<br />";
        $testOutPut .= "<p>";
        $testOutPut .= "<img src='{$img->url}' width='{$img->width}' height='{$img->height}' alt='{$img->name}' title='' /></p>";
        $testOutPut .= "<p" . hnCompareSize($img->width, $width, '==') . ">width from {$image->width} to $width = result: {$img->width}";
        $testOutPut .= "</span></p>";
        $testOutPut .= "</div>\n";
    }
    $testOutPut .= "<div style='clear:both !important;'>&nbsp;</div>";


    $testOutPut .= "<h4>upscale false</h4>";
    foreach($images as $image) {
        set_time_limit(15);

        $options['upscaling'] = false;
        $options['appendix']  = ($coreImageSizer ? 'is' : '') . 'wup0';
        $width = $image->width + 10;
        $img = $image->width($width, $options);

        $testOutPut .= "<div class='size up'>";
        #$testOutPut .= "<p>" . str_replace(array('-si', '_', '.', 'size', $width.'x0', 's1q90nu', 's1q90', 'up0', '-', 'jpg', 'scale'), array('', ' ', ' ', ''), $img->name) . "<br />";
        $testOutPut .= "<p>";
        $testOutPut .= "<img src='{$img->url}' width='{$img->width}' height='{$img->height}' alt='{$img->name}' title='' /></p>";
        $testOutPut .= "<p" . hnCompareSize($img->width, $image->width, '==') . ">width from {$image->width} to $width = result: {$img->width}";
        $testOutPut .= "</span></p>";
        $testOutPut .= "</div>\n";
    }
    $testOutPut .= "<div style='clear:both !important;'>&nbsp;</div>";
