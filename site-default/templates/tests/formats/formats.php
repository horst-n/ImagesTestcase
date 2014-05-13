<?php
/**
* ----------------------------------------------------------------------------------------------------
*  $Source: /WEB/pw4/htdocs/site/templates/tests/formats/formats.php,v $
*  $Id: formats.php,v 1.1.2.3 2014/05/11 16:02:25 horst Exp $
* ----------------------------------------------------------------------------------------------------
*/

    $options['sharpening'] = 'soft';
    $options['quality']  = 90;
    $options['upscaling'] = false;
    $options['cropping']  = false;
    $testOutPut .= displayOptions($options, array());

    foreach($images as $image) {
        set_time_limit(15);

        $width = intval($image->width / 2);
        $img = $image->width($width, $options);

        $testOutPut .= "<div class='size down'>";
        $testOutPut .= "<p>" . pathinfo($image->name, PATHINFO_FILENAME) . "<br />";
        $testOutPut .= "<img src='{$img->url}' width='{$img->width}' height='{$img->height}' alt='{$img->name}' title='' /></p>";
        $fileFormat = pathinfo($img->filename, PATHINFO_EXTENSION);
        $curFormat = '';
        $refFormat = (preg_match('/wrong_([a-z]{3})_is_(.*?)\./', $img->name, $matches) ? $matches[2] : $fileFormat);
        $class = hnCheckFormat($img->filename, $refFormat, $curFormat);
        $testOutPut .= "<p style='margin:-10px 10px 0 10px;'" . $class . "> fileextension: $fileFormat<br />expected imagetype: $refFormat<br />detected imagetype: $curFormat</p>";
        $testOutPut .= "</div>\n";
    }
    $testOutPut .= "<div style='clear:both !important;'>&nbsp;</div>";
