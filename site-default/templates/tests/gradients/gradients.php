<?php
/**
* ----------------------------------------------------------------------------------------------------
*  $Source: /WEB/pw4/htdocs/site/templates/tests/gradients/gradients.php,v $
*  $Id: gradients.php,v 1.1.2.2 2014/05/11 16:02:25 horst Exp $
* ----------------------------------------------------------------------------------------------------
*/

    $options['upscaling'] = false;
    $options['cropping']  = false;
    $options['sharpening']  = 'soft';
    $options['quality']  = 90;
    $testOutPut .= displayOptions($options);

    $testOutPut .= "<br />";

    foreach($images as $image) {

        $width = 250;
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
            $testOutPut .= "<div class='size up'>";
            $testOutPut .= "<p style='color:#999;'>" . str_replace(array('.250x0s1q90nu', '.250x0s1q90nu'), array(''), $img->name) . "</p>";
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
        $testOutPut .= "<div class='size up'>";
        $testOutPut .= "<p style='color:#999;'>" . str_replace(array('.250x0s1q90nu', '.250x0s1q90nu'), array(''), $img->name) . "</p>";
        $testOutPut .= "<img src='{$img->url}' width='{$img->width}' height='{$img->height}' alt='{$img->name}' title='' /></p>";
        $testOutPut .= "<p style='color:#077;'>processed via " . ($coreImageSizer ? 'ImageSizer' : 'ImagickResizer' ) . "</p>";
        if(isset($elapsedTime)) {
            $testOutPut .= "<p style='color:#999;'>time: $elapsedTime</p>";
            unset($elapsedTime);
        }
        $testOutPut .= "</div>\n";


        if(360<$image->width) {
            $width2 = 270;
            $height2 = $image->height;
            if(isset($GLOBALS['useCMS'])) unset($GLOBALS['useCMS']);
            $options['appendix'] = ($coreImageSizer ? 'ishalf' : 'half');
            $options['useGD']    = $coreImageSizer ? true : false;
            $options['forcenew'] = isset($removeVariations) && true===$removeVariations ? true : false;
            $options['useCMS']   = false;
            $options['cropping'] = 'w';
            $timer->start('isim');
            $img = $image->size($width2, $height2, $options);
            $elapsedTime = $timer->current('isim');
            $timer->delete('isim');
            // reset it to the default of this test
            $options['cropping'] = false;
            $testOutPut .= "<div class='size up'>";
            $testOutPut .= "<p style='color:#999;'>" . str_replace(array('half.', '.s1q90nu', 's1q90wnu'), array('.', ''), $img->name) . "</p>";
            $testOutPut .= "<img src='{$img->url}' width='{$img->width}' height='{$img->height}' alt='{$img->name}' title='' /></p>";
            $testOutPut .= "<p style='color:#077;'>processed via " . ($coreImageSizer ? 'ImageSizer' : 'ImagickResizer' ) . "</p>";
            if(isset($elapsedTime)) {
                $testOutPut .= "<p style='color:#999;'>time: $elapsedTime</p>";
                unset($elapsedTime);
            }
            $testOutPut .= "</div>\n";
        }

        $testOutPut .= "<div style='clear:both !important;'>&nbsp;</div>";
    }

    $testOutPut .= "<p>The totaltime is only measured for the ImagickResizer! Otherwise a comparision with ImageSizers GD-lib is not possible.</p>";
