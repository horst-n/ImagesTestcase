<?php
/**
* ----------------------------------------------------------------------------------------------------
*  $Source: /WEB/pw4/htdocs/site/templates/tests/cms/cms-with-icc_visual.php,v $
*  $Id: cms-with-icc_visual.php,v 1.4.2.2 2014/05/11 16:02:25 horst Exp $
* ----------------------------------------------------------------------------------------------------
*/

    $options['upscaling'] = false;
    $options['cropping']  = false;
    $options['appendix'] .= 'icc';
    $options['useCMS']    = true;
    $testOutPut .= displayOptions($options, array('sharpening'));

    $width = 220;

    $selection = array();
    foreach($images as $image) $selection[] = $image->name;
    $successfulusage = 0;

    foreach($images as $image) {
        set_time_limit(15);

        if(!in_array($image->name, $selection)) continue;

        if($coreImageSizer) {

            $img = $image->width($width, $options);
            $testOutPut .= "<div style='float:left; width:".($width + 10)."px; height:".intval($width + 10)."px;'>";
            $testOutPut .= "<p>" . str_replace(array('-isicc.', '-is.', '-icc.', '_', '.', 'colortarget', $width.'x0', 's0q95nu'), array(' ', ' ', ' ', ' ', ' ', ''), $img->name) . "<br />";
            $testOutPut .= "<img src='{$img->url}' width='{$img->width}' height='{$img->height}' alt='{$img->name}' title='' /></p></div>\n";

        }
        else {

            if(isset($GLOBALS['useCMS'])) unset($GLOBALS['useCMS']);
            $img = $image->width($width, $options);
            $hasUsedCMS = isset($GLOBALS['useCMS']) ? " class='success'" : " class='failed bold'";
            $successfulusage += isset($GLOBALS['useCMS']) ? 1 : 0;
            $testOutPut .= "<div style='float:left; width:".($width + 10)."px; height:".intval($width + 10)."px;'>";
            $testOutPut .= "<p{$hasUsedCMS}>" . str_replace(array('-isicc.', '-is.', '-icc.', '_', '.', 'colortarget', $width.'x0', 's0q95nu'), array(' ', ' ', ' ', ' ', ' ', ''), $img->name) . "<br />";
            $testOutPut .= "<img src='{$img->url}' width='{$img->width}' height='{$img->height}' alt='{$img->name}' title='' /></p></div>\n";

        }
    }
