<?php
/**
* ----------------------------------------------------------------------------------------------------
*  $Source: /WEB/pw4/htdocs/site/templates/tests/sharpen/sharpen.php,v $
*  $Id: sharpen.php,v 1.1.2.2 2014/05/11 16:02:26 horst Exp $
* ----------------------------------------------------------------------------------------------------
*/


    $options['upscaling'] = false;
    $options['cropping']  = false;
    $testOutPut .= displayOptions($options, array('sharpening'));

    foreach($images as $image) {
        set_time_limit(15);
        $width = 200;
        foreach(array('none', 'soft', 'medium', 'strong') as $sharp) {
            $option = array_merge($options, array('sharpening'=>$sharp));
            $img = $image->size($width, $width, $option);
            $testOutPut .= "<div style='float:left; width:220px; height:220px;'>";
            $testOutPut .= "<p>" . str_replace(array('-is', '_', '.', 'sharpen', $width.'x'.$width, 's0q95nu', 's1q95nu', 's2q95nu', 's3q95nu'), array('', ' ', ' ', ''), $img->name) . " <br /><strong>$sharp</strong><br />";
            $testOutPut .= "<img src='{$img->url}' width='{$img->width}' height='{$img->height}' alt='{$img->name}' title='' /></p>";
            $testOutPut .= "</div>\n";
        }
    }
    $testOutPut .= "<div style='clear:both !important;'>&nbsp;</div>";
