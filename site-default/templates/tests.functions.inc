<?php
/**
* ----------------------------------------------------------------------------------------------------
*  $Source: /WEB/pw4/htdocs/site/templates/tests.functions.inc,v $
*  $Id: tests.functions.inc,v 1.3.2.2 2014/05/05 14:13:05 horst Exp $
* ----------------------------------------------------------------------------------------------------
*/


function displayOptions($options, $supressKeys=array()) {
    $opts = array();
    foreach($options as $k=>$v) {
        if(in_array($k, array_merge($supressKeys, array('useGD', 'appendix', 'forcenew')))) continue;
        $opts["$k"] = $v;
    }
    $hn = new hn_basic();
    return str_replace('array(' . count($opts) . ')', '$options', $hn->my_var_dump($opts, 3));
}



function hnCheckFormat($filename, $ref, &$curFormat, $permanentCSS='perm_', $booleanResult=false) {
    $types = array('image/gif'=>'gif', 'image/jpeg'=>'jpg', 'image/png'=>'png');
    $flip = array_flip($types);
    $ref = strtolower($ref);
    if(!in_array($ref, $types)) {
        $res = false;
    }
    else {
        $curFormat = $types[getImageType($filename)];
        $res = $flip[$ref] == $flip[$curFormat];
    }
    if($booleanResult) return $res;
    return $res ? " class='{$permanentCSS}success'" : " class='{$permanentCSS}failed'";
}



if(!function_exists('getImageType')) {
    function getImageType($fn) {
        $types1 = array(1=>'image/gif', 2=>'image/jpeg', 3=>'image/png');
        $types2 = array('gif'=>'image/gif', 'jpg'=>'image/jpeg', 'jpeg'=>'image/jpeg', 'png'=>'image/png');
        if(function_exists('exif_imagetype') && in_array(exif_imagetype($fn),array_flip($types1))) {
            return $types1[exif_imagetype($fn)];
        }
        if(function_exists('getimagesize')) {
            $info = @getimagesize($fn);
            if(isset($info[2])) {
                if(in_array($info[2],array_flip($types1))) {
                    return $types1[$info[2]];
                }
                return false;
            }
        }
        $pathinfo = pathinfo($fn);
        if(isset($pathinfo['extension']) && in_array(strtolower($pathinfo['extension']),array_flip($types2))) {
            return $types2[strtolower($pathinfo['extension'])];
        }
        return false;
    }
}



/**
* size width
* size height
* size size
*/
function hnCompareSize($cur, $ref, $operator='==', $permanentCSS='perm_', $booleanResult=false) {
    $operators = array('==', '>=', '>', '<=', '<');
    if(!in_array($operator, $operators)) {
        throw new WireException(__('ERROR: wrong value for operator in ' . __FUNCTION__ . ' (' . basename(__FILE__) . ')!'));
    }
    $res = 0;
    switch($operator) {
        case '>=':
            $res = $cur >= $ref ? true : false;
            break;
        case '>':
            $res = $cur > $ref ? true : false;
            break;
        case '<=':
            $res = $cur <= $ref ? true : false;
            break;
        case '<':
            $res = $cur < $ref ? true : false;
            break;
        case '==':
            $res = $cur == $ref ? true : false;
            break;
    }
    if($booleanResult) return $res;
    return $res ? " class='{$permanentCSS}success'" : " class='{$permanentCSS}failed'";
}


/**
* cms technical
*/
function hnGetColor($coords, $filename) {
    global $coreImageSizer;

    $tmp = explode(',', $coords, 2);
    $x = intval($tmp[0]);
    $y = intval($tmp[1]);
    $r = $g = $b = -1;

    if($coreImageSizer) {
        $im = imagecreatefromstring(file_get_contents($filename));
        if(is_resource($im)) {
            $rgb = imagecolorat($im, $x, $y);
            imagedestroy($im);
            $r = ($rgb >> 16) & 0xFF;
            $g = ($rgb >> 8) & 0xFF;
            $b = $rgb & 0xFF;
        }
    }
    else {
        $im = new Imagick();
        $res = $im->readImage($filename);
        if($res) {
            $px = $im->getImagePixelColor($x, $y);
            $rgb = $px->getColor(false);
            $r = $rgb['r'];
            $g = $rgb['g'];
            $b = $rgb['b'];
            $px->clear();
            $px = null;
        }
        $im->clear();
        $im->destroy();
        $im = null;
    }
    return array($r, $g, $b);
}



/**
* cms technical
*/
function hnCompareColor($cur, $ref) {

    $ref = explode(',', $ref);
    foreach($ref as $k=>$v) {
        $ref[$k] = intval($v);
    }
    $res = 0;
    foreach(array(0, 1, 2) as $c) {
        if($cur[$c] == $ref[$c]) {
            continue;
        }
        if($cur[$c] + 1 == $ref[$c]) {
            continue;
        }
        if($cur[$c] + 2 == $ref[$c]) {
            continue;
        }
        if($cur[$c] - 1 == $ref[$c]) {
            continue;
        }
        if($cur[$c] - 2 == $ref[$c]) {
            continue;
        }
        if($ref[$c] + 1 == $cur[$c]) {
            continue;
        }
        if($ref[$c] + 2 == $cur[$c]) {
            continue;
        }
        if($ref[$c] - 1 == $cur[$c]) {
            continue;
        }
        if($ref[$c] - 2 == $cur[$c]) {
            continue;
        }
        $res++;
    }
    return $res > 0 ? " class='mismatch'" : '';
}



function hnWriteTesttime($coreImageSizer, $group, $test, $exectutiontime) {
    $cachefile = wire('config')->paths->cache . 'testexecutiontimes.txt';
    $coreImageSizer = $coreImageSizer ? 'cis' : 'ir';
    $all = array();
    $hn = new hn_basic();
    $hn->GetCacheArray($all, $cachefile);
    $all["$coreImageSizer-$group-$test"] = array($exectutiontime, time());
    $hn->SetCacheArray($all, $cachefile);
}
function hnReadTesttime($coreImageSizer, $group, $test) {
    $cachefile = wire('config')->paths->cache . 'testexecutiontimes.txt';
    $coreImageSizer = $coreImageSizer ? 'cis' : 'ir';
    $all = array();
    $hn = new hn_basic();
    $hn->GetCacheArray($all, $cachefile);
    return isset($all["$coreImageSizer-$group-$test"]) ? $all["$coreImageSizer-$group-$test"] : array(0,0);
}

