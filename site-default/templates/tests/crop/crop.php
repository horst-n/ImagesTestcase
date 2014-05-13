<?php
/**
* ----------------------------------------------------------------------------------------------------
*  $Source: /WEB/pw4/htdocs/site/templates/tests/crop/crop.php,v $
*  $Id: crop.php,v 1.1.2.7 2014/05/11 16:02:25 horst Exp $
* ----------------------------------------------------------------------------------------------------
*/

    $useGD = $coreImageSizer ? true : false;
    #$testOutPut .= displayOptions($options, array('cropping', 'upscaling'));

    $orig = $images->first();
    #$orig->removeVariations();

    $aufgaben = array();
    $aufgaben['size'] = array(
        #'crop no resize' => array('w'=>intval($orig->width), 'h'=>intval($orig->height), 'options'=>array('cropping'=>true), 'size'=>true),
        'crop false' => array('w'=>intval($orig->height / 4 * 3), 'h'=>intval($orig->height / 4 * 3), 'options'=>array('cropping'=>false), 'size'=>true),
        'crop true (center)' => array('w'=>intval($orig->height / 4 * 3), 'h'=>intval($orig->height / 4 * 3), 'options'=>array('cropping'=>true), 'size'=>true),
        );
    foreach(array('north','south') as $d) {
        $aufgaben['size']["crop $d"] = array('h'=>intval($orig->height / 2), 'w'=>intval($orig->width), 'options'=>array('cropping'=>"$d"), 'size'=>true, 'dim'=>'w');
    }
    foreach(array('west','east') as $d) {
        $aufgaben['size']["crop $d"] = array('h'=>intval($orig->height), 'w'=>intval($orig->width / 2), 'options'=>array('cropping'=>"$d"), 'size'=>true, 'dim'=>'h');
    }

//    foreach(array('northwest','northeast') as $d) {
//        $aufgaben['size']["crop $d"] = array('w'=>intval($orig->height / 2), 'h'=>intval($orig->width / 2), 'options'=>array('cropping'=>"$d"), 'size'=>true);
//    }
//    foreach(array('southwest','southeast') as $d) {
//        $aufgaben['size']["crop $d"] = array('h'=>intval($orig->height), 'w'=>intval($orig->width / 2), 'options'=>array('cropping'=>"$d"), 'size'=>true);
//    }

    foreach($aufgaben['size'] as $key=>$param) {
        set_time_limit(15);

        $option = array_merge($options, $param['options']);
        $img = $orig->size($param['w'], $param['h'], $option);

        $testOutPut .= "<div class='size up'>";
        $testOutPut .= "<p>{$key}<br />";
        $testOutPut .= "<img src='{$img->url}' width='{$img->width}' height='{$img->height}' alt='{$img->name}' title='' /></p>";

        $operator = $option['cropping'] ? '==' : '<=';
        $dim1 = isset($param['dim']) && 'h'==$param['dim'] ? 'height' : 'width';
        $dim2 = 'height'==$dim1 ? 'h' : 'w';
        $testOutPut .= "<p" . hnCompareSize($img->width, $param['w'], $operator) . ">";
        $testOutPut .= "{$param[$dim2]} $operator " . $img->{$dim1} . "</p>";
        $testOutPut .= "</div>\n";

#$testOutPut .= "<div style='clear:both !important;'>&nbsp;</div>";
    }
    $testOutPut .= "<div style='clear:both !important;'>&nbsp;</div>";

