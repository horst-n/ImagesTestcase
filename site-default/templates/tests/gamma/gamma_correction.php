<?php
/**
* ----------------------------------------------------------------------------------------------------
*  $Source: /WEB/pw4/htdocs/site/templates/tests/gamma/gamma_correction.php,v $
*  $Id: gamma_correction.php,v 1.1.2.4 2014/05/11 16:02:25 horst Exp $
* ----------------------------------------------------------------------------------------------------
*/

    $options['upscaling'] = false;
    $options['cropping']  = false;
    #$testOutPut .= displayOptions($options);

    $testOutPut .= '<br />';

    foreach($images as $image) {

        $width = intval($image->width / 2);
        unset($elapsedTime);

        if(!$coreImageSizer) {
            $timer->pause('test');
            $options['appendix'] = 'isgd';
            $options['forcenew'] = ((isset($removeVariations) && true===$removeVariations) ? true : false);
            $options['useCMS'] = false;
            $options['useGD'] = true;
            $timer->start('isgd');
            $img = $image->width($width, $options);
            $elapsedTime = $timer->current('isgd');
            $timer->delete('isgd');
            $testOutPut .= "<div class='size down'>";
            $testOutPut .= "<p style='color:#999;'>" . str_replace(array('.129x0s0q95nu', '.150x0s0q95nu'), array(''), $img->name) . "</p>";
            $testOutPut .= "<img src='{$img->url}' width='{$img->width}' height='{$img->height}' alt='{$img->name}' title='' /></p>";
            $testOutPut .= "<p style='color:#999;'>processed via GD ImageSizer</p>";
            if(isset($elapsedTime)) {
                $testOutPut .= "<p style='color:#999;'>time: $elapsedTime</p>";
                unset($elapsedTime);
            }
            $testOutPut .= "</div>\n";
            $timer->resume('test');
        }

        $options['appendix'] = $coreImageSizer ? 'isbasic' : 'basic';
        $options['useGD']    = $coreImageSizer ? true : false;
        $options['forcenew'] = isset($removeVariations) && true===$removeVariations ? true : false;
        $options['useCMS'] = false;
        $timer->start('isim');
        $img = $image->width($width, $options);
        $elapsedTime = $timer->current('isim');
        $timer->delete('isim');
        $testOutPut .= "<div class='size down'>";
        $testOutPut .= "<p style='color:#999;'>" . str_replace(array('.129x0s0q95nu', '.150x0s0q95nu'), array(''), $img->name) . "</p>";
        $testOutPut .= "<img src='{$img->url}' width='{$img->width}' height='{$img->height}' alt='{$img->name}' title='' /></p>";
        $testOutPut .= "<p style='color:#077;'>basic image handling</p>";
        if(isset($elapsedTime)) {
            $testOutPut .= "<p style='color:#999;'>time: $elapsedTime</p>";
            unset($elapsedTime);
        }
        $testOutPut .= "</div>\n";

        if(!$coreImageSizer) {
            $timer->pause('test');
            if(isset($GLOBALS['useCMS'])) unset($GLOBALS['useCMS']);
            $options['appendix'] = ($coreImageSizer ? 'isicc' : 'icc');
            $options['useGD']    = false;
            $options['forcenew'] = ((isset($removeVariations) && true===$removeVariations) ? true : false);
            $options['useCMS'] = true;
            $timer->start('isim');
            $img = $image->width($width, $options);
            $elapsedTime = $timer->current('isim');
            $timer->delete('isim');
            $hasUsedCMS = isset($GLOBALS['useCMS']) ? " class='perm_success'" : " class='perm_failed'";
            $testOutPut .= "<div class='size down'>";
            $testOutPut .= "<p style='color:#999;'>" . str_replace(array('.129x0s0q95nu', '.150x0s0q95nu'), array(''), $img->name) . "</p>";
            $testOutPut .= "<img src='{$img->url}' width='{$img->width}' height='{$img->height}' alt='{$img->name}' title='' /></p>";
            $testOutPut .= "<p{$hasUsedCMS}>image handling with icc conversion</p>";
            if(isset($elapsedTime)) {
                $testOutPut .= "<p style='color:#999;'>time: $elapsedTime</p>";
                unset($elapsedTime);
            }
            $testOutPut .= "</div>\n";
            $timer->resume('test');
        }

        $testOutPut .= "<div style='clear:both !important;'>&nbsp;</div>";

    }

    $testOutPut .= "<p>The totaltime is only measured for the ImagickResizer <span style='color:#077;'>basic image handling</span>! Otherwise a comparision with ImageSizers GD-lib is not possible.</p>";
