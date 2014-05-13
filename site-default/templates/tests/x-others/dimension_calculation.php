<?php
/**
* ----------------------------------------------------------------------------------------------------
*  $Source: /WEB/pw4/htdocs/site/templates/tests/x-others/dimension_calculation.php,v $
*  $Id: dimension_calculation.php,v 1.1.2.2 2014/05/11 16:02:26 horst Exp $
* ----------------------------------------------------------------------------------------------------
*/

    #$options['sharpening'] = 'soft';
    #$options['quality']  = 90;
    $options['upscaling'] = false;
    $options['cropping']  = false;
    #$testOutPut .= displayOptions($options);

    $testOutPut .= "<br />";
    $image = $images->first();

    foreach(array(-4,-3,-2,-1,0,1,2,3,4) as $i) {
        set_time_limit(15);

        $options['appendix']  = ($coreImageSizer ? 'is' : '') . 'calc';
        $width = intval($image->width / 4 * 3) + $i;
        $img = $image->width($width, $options);

        $testOutPut .= "<div class='size down'>";
        #$testOutPut .= "<p>" . str_replace(array('-si', '_', '.', 'size', $width.'x0', 's1q90nu', 's1q90', 'same', '-', 'jpg', 'scale'), array('', ' ', ' ', ''), $img->name) . "<br />";
        $testOutPut .= "<p>$i<br />";
        $testOutPut .= "<img src='{$img->url}' width='{$img->width}' height='{$img->height}' alt='{$img->name}' title='' /></p>";
        $testOutPut .= "<p" . hnCompareSize($img->width, $width, '==') . ">width from {$image->width} to $width = result: {$img->width}";
        $testOutPut .= "</span></p>";
        $testOutPut .= "</div>\n";
    }
    $testOutPut .= "<div style='clear:both !important;'>&nbsp;</div>";

