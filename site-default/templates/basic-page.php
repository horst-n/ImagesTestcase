<?php

/**
 * Page template
 *
 */
include("./head.inc");


if('imagick-cropping' == $page->name) {

//$mem = new hn_memory_usage();
//$shortnotation = true;
//echo $mem->limit($shortnotation) . ' | ' . $mem->available($shortnotation) . ' | ' . $mem->usage($shortnotation);
//include("./foot.inc");
//return;


    $orig = $page->images->first();
    $orig->removeVariations();

    $useGD = false;

//    $img = $orig->width(8000, array('upscaling'=>true, 'useGD'=>$useGD));
//    echo "<p>{$img->url}<br /><img src='{$img->url}' /></p>";



    $tests = array();
    foreach(array('width', 'height') as $dimension) {
        $tests[$dimension] = array(
            'same' => array('w'=>$orig->$dimension, 'options'=>array('useGD'=>$useGD)),
            'down' => array('w'=>intval($orig->$dimension / 2), 'options'=>array('useGD'=>$useGD)),
            'up-yes' => array('w'=>intval($orig->$dimension * 2), 'options'=>array('upscaling'=>true, 'useGD'=>$useGD)),
            'up-no' => array('w'=>intval($orig->$dimension * 2), 'options'=>array('upscaling'=>false, 'useGD'=>$useGD))
            );
    }
    $tests['size'] = array(
        'w-crop-yes' => array('w'=>intval($orig->width / 2), 'options'=>array('cropping'=>true, 'useGD'=>$useGD), 'size'=>true),
        'w-crop-no' => array('w'=>intval($orig->width / 2), 'options'=>array('cropping'=>false, 'useGD'=>$useGD), 'size'=>true),
        'h-crop-yes' => array('w'=>intval($orig->height / 2), 'options'=>array('cropping'=>true, 'useGD'=>$useGD), 'size'=>true),
        'h-crop-no' => array('w'=>intval($orig->height / 2), 'options'=>array('cropping'=>false, 'useGD'=>$useGD), 'size'=>true),
        );
    foreach($tests as $dimension=>$testset) {
        echo "<p>$dimension</p><ul>";
        foreach($testset as $test=>$param) {
            if('size'==$dimension) {
                if(!isset($param['size'])) continue;
                $img = $orig->size($param['w'], $param['w'], $param['options']);
                echo "<li>$test<br />{$param['w']} == {$img->height} == {$img->width}</li>";
            } else {
                if(isset($param['size'])) continue;
                $img = $orig->$dimension($param['w'], $param['options']);
                echo "<li>$test<br />{$param['w']} == {$img->$dimension}</li>";
            }
        }
        echo "</ul>";
    }

}
elseif('new-naming-convention' == $page->name) {

    echo "<h3>An attempt to create a new naming scheme for image names</h3>";
    echo "<p>Following is a new naming scheme that can reflect much more image variations whereas the core naming scheme reflect a fewer part of them. ";
    echo "Images settings for sharpening s0 | s1 | s2 | s3 is supported, quality q0 - q100 and for images created with 'upscaling = false' a 'nu' is appended (nu = no upscaling).</p>";

    echo "<p>The extended naming scheme now is available as module: <a target='_blank' href='https://github.com/horst-n/PageimageNamingScheme'>PageimageNamingScheme</a></p>";

    echo "<h4>Test:</h4>";
    #echo "<p>sharpening: 'none', 'soft', 'medium', 'strong' :: qualities: 0, 50, 100 :: cropping: false, true, 'nw', 'se', '25,35' :: upscaling: true, false</p>";
    $code = "    foreach(array('none', 'soft', 'medium', 'strong') as \$s) {
        foreach(array(100, 50, 0) as \$q) {
            foreach(array(false, true, 'nw', '25,35') as \$c) {
                foreach(array(true, false) as \$u) {
                    // create image with this soptions and display \$image->name
                }
            }
        }
    }";
    echo "<br /><pre>{$code}</pre>";

    $num = 0;
    $orig = $page->images->first();
    $orig->removeVariations();
    $oldnames = array();
    echo "<ol class='namingconventions'>\n";
    foreach(array('none', 'soft', 'medium', 'strong') as $s) {
        foreach(array(100, 50, 0) as $q) {
            foreach(array(false, true, 'se', '20,30') as $c) {
                foreach(array(true, false) as $u) {
                    #$img = $orig->size(intval($orig->height / 2), intval($orig->width / 2), array('quality'=>$q, 'cropping'=>$c, 'upscaling'=>$u, 'sharpening'=>$s, 'useGD'=>true, 'forcenew'=>true, 'appendix'=>'aBc' . str_pad(strval($num+1), 3, '0', STR_PAD_LEFT) . '-xyz+#0123dkladhuerzwew'));
                    $img = $orig->size(intval($orig->height / 2), intval($orig->width / 2), array('quality'=>$q, 'cropping'=>$c, 'upscaling'=>$u, 'sharpening'=>$s, 'useGD'=>true, 'forcenew'=>true));
                    //echo "<li><img src='{$img->url}' />{$img->name}</li>\n";
                    echo "<li>{$img->name}</li>\n";
                    $oldname = pathinfo($orig->filename, PATHINFO_DIRNAME) . '/' . pathinfo($orig->filename, PATHINFO_FILENAME) . '.' . intval($orig->height / 2) . 'x' . intval($orig->width / 2) . imagesizer::croppingValueStr($c) . '.' . pathinfo($orig->filename, PATHINFO_EXTENSION);
                    $oldnames[basename($oldname)] = $oldname;
                    $num++;
                }
            }
        }
    }
    $num2 = 0;
    foreach($oldnames as $basename=>$oldname) {
        if(!file_exists($oldname) && copy($orig->filename, $oldname)) {
            echo "<li style='color:orange;'>{$basename}</li>\n";
            $num2++;
        }
    }
    $num3 = 0;
    foreach(array('test1', 'test2', 'test3') as $prefix) {
        $pim = $orig->pimLoad($prefix, true)->width(intval($orig->height / 2), 'none')->height(intval($orig->width / 2), 'none')->pimSave();
        echo "<li style='color:violet;'>{$pim->name}</li>\n";
        $num3++;
    }
    echo "</ol>";

    $v = $orig->getVariations()->count();
    echo "<p>We have created <strong>$num</strong> image variations with the new naming scheme, additionally copied <span style='font-weight:bold;color:orange;'>$num2</span> files with core naming scheme and created <span style='font-weight:bold;color:violet;'>$num3</span> files with the Pageimage Manipulator. Now let's try to collect all of them and count with \$image->getVariations()->count():</p>";
    $class = $v==intval($num+$num2+$num3) ? " class='perm_success bold'" : " class='perm_failed bold'";
    $res = $v==intval($num+$num2+$num3) ? "$v = SUCCESS!" : "$v = FAILED!";
    echo "<p{$class}>$res</p>";

}
else {

    echo $page->body;

}


include("./foot.inc");

