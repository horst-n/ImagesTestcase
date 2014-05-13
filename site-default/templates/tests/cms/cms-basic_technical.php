<?php
/**
* ----------------------------------------------------------------------------------------------------
*  $Source: /WEB/pw4/htdocs/site/templates/tests/cms/cms-basic_technical.php,v $
*  $Id: cms-basic_technical.php,v 1.3.2.2 2014/05/11 16:02:25 horst Exp $
* ----------------------------------------------------------------------------------------------------
*/

    $referenceColors = array(

        // grayscale
        '26,30' => array('0, 0, 0', '0, 0, 0'),
        '43,30' => array('27, 27, 27', '27, 27, 27'),
        '60,30' => array('48, 48, 48', '48, 48, 48'),
        '77,30' => array('70, 70, 70', '70, 70, 70'),
        '93,30' => array('94, 94, 94', '94, 94, 94'),
        '109,30' => array('118, 118, 118', '118, 118, 118'),
        '126,30' => array('145, 145, 145', '145, 145, 145'),
        '143,30' => array('171, 171, 171', '171, 171, 171'),
        '159,30' => array('198, 198, 198', '198, 198, 198'),
        '176,30' => array('226, 226, 226', '226, 226, 226'),
        '192,30' => array('255, 255, 255', '255, 255, 255'),

        // CMY
        '26,118' => array('6, 159, 227', '156, 156, 156'),
        '43,118' => array('228, 2, 127', '128, 128, 128'),
        '60,118' => array('251, 237, 4', '237, 237, 237'),
        // CMY lighten
        '26,130' => array('158, 231, 248', '223, 223, 223'),
        '43,130' => array('252, 184, 221', '210, 210, 210'),
        '60,130' => array('254, 250, 186', '248, 248, 248'),
        // CMY darken
        '26,138' => array('2, 39, 55', '36, 36, 36'),
        '43,138' => array('57, 0, 33', '25, 25, 25'),
        '60,138' => array('61, 56, 0', '60, 60, 60'),

        // RGB
        '93,118' => array('253, 0, 2', '137, 137, 137'),
        '109,118' => array('25, 251, 4', '221, 221, 221'),
        '126,118' => array('4, 58, 255', '93, 93, 93'),
        // RGB lighten
        '93,130' => array('252, 182, 184', '206, 206, 206'),
        '109,130' => array('138, 254, 183', '233, 233, 233'),
        '126,130' => array('179, 188, 253', '197, 197, 197'),
        // RGB darken
        '93,138' => array('74, 2, 3', '35, 35, 35'),
        '109,138' => array('2, 60, 2', '54, 54, 54'),
        '126,138' => array('1, 6, 61', '10, 10, 10')

        );


    $options['upscaling'] = false;
    $options['cropping']  = false;
    $testOutPut .= displayOptions($options, array('sharpening'));

    $width = 220;

    foreach($images as $image) {
        set_time_limit(15);

        $img = $image->width($width, $options);
        $testOutPut .= "<div class='cms'>";
        $testOutPut .= "<img src='{$img->url}' width='{$img->width}' height='{$img->height}' alt='{$img->name}' title='' />\n";
        $testOutPut .= "<p>" . str_replace(array('-isicc.', '-is.', '-icc.', '_', '.', 'colortarget', $width.'x0', 's0q95nu'), array(' ', ' ', ' ', ' ', ' ', ''), $img->name) . "</p>";
        $testOutPut .= "<ul>";
        $i = 0;
        foreach($referenceColors as $coords=>$rc) {
            if(in_array($i, array(0,11,14,17,20,23,26))) {
                $testOutPut .= '<li><hr /></li>';
            }
            $i++;
            $res = hnGetColor($coords, $img->filename);
            $ref = 'gray'==substr(strtolower($img->name), 12, 4) ? $rc[1] : $rc[0];
            $class = hnCompareColor($res, $ref);
            $res = implode(', ', $res);
            $testOutPut .= "<li{$class}>$ref => $res</li>";
            if(in_array($i, array(11,20,29))) {
                $testOutPut .= '<li><hr /></li>';
            }
        }
        $testOutPut .= "</ul><br /></div>\n";

    }
