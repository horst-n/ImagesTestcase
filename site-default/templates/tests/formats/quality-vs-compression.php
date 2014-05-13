<?php
/**
* ----------------------------------------------------------------------------------------------------
*  $Source: /WEB/pw4/htdocs/site/templates/tests/formats/quality-vs-compression.php,v $
*  $Id: quality-vs-compression.php,v 1.1.2.2 2014/05/11 16:02:25 horst Exp $
* ----------------------------------------------------------------------------------------------------
*/


    $options['sharpening'] = 'soft';
    $options['quality']  = 90;
    $options['upscaling'] = false;
    $options['cropping']  = false;
    #$testOutPut .= displayOptions($options, array());

    $testOutPut .= "<p>setting different qualities:  quality = filesize</p>";
    $qualities = array('100', '099', '090', '080', '050', '025', '001', '000');
    foreach($images as $image) {
        set_time_limit(15);

        $width = intval($image->width / 2);
        $results = array();
        foreach($qualities as $quality) {
            $img = $image->width($width, array_merge($options, array('quality'=>intval($quality))));
            $results[$quality] = filesize($img->filename);
            @unlink($img->filename);
        }
        $testOutPut .= "<div class='size down'>";
        $testOutPut .= "<p>" . pathinfo($image->name, PATHINFO_FILENAME) . "</p>";
        $testOutPut .= "<ul>";
        foreach($results as $k=>$v) $testOutPut .= "<li>$k = $v </li>";
        $testOutPut .= "</ul>";
        $testOutPut .= "</div>\n";
    }
    $testOutPut .= "<div style='clear:both !important;'>&nbsp;</div>";
