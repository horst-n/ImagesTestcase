<?php
/**
* ----------------------------------------------------------------------------------------------------
*  $Source: /WEB/pw4/htdocs/site/templates/tests.php,v $
*  $Id: tests.php,v 1.7.2.4 2014/05/11 16:02:25 horst Exp $
* ----------------------------------------------------------------------------------------------------
*/

include("./head.inc");

echo $page->body;

if(!$hasIR && ('imagick-resizer'==$page->name || 'imagick-resizer'==$page->parent->name)) {
    echo "<h3>You must install the Imagick Resizer module before you can run tests on it!</h3>";
    echo "<p>Please login and go to modules section and install it.</p>";
    echo "<ul><li>On the config page of the module please tip the checkbox for using the CMS with ICC profiles.</li>
            <li> Open the 'CMS - ICC Color Management System' and check if it has selected a profile for all six usages.</li>";
    echo "<li>Under 'Advanced' please select 'Do not use GD-lib, throw error instead' and save / submit it.</li></ul>";
    include("./foot.inc");
return;
}

if('imagick-resizer'==$page->name || 'image-sizer-core'==$page->name) {

    if(isset($GLOBALS['useCMS'])) unset($GLOBALS['useCMS']);
    $timer = new hn_timer();
    include("./tests.inc");
    echo "<div style='clear:both !important;'>&nbsp;</div>";

    if($user->isLoggedIn()) {
        if(('imagick-resizer'==$page->name || 'imagick-resizer'==$page->parent->name) && ('cms-with-icc_visual'==$input->urlSegment2 || 'cms-basic_visual'==$input->urlSegment2 || 'cms-with-icc_short'==$input->urlSegment2)) {
            // create a csv line for copy paste
            $api = new hn_php_api();
            $resUsedCMS = isset($successfulusage) && isset($selection) ? "{$successfulusage}/" . count($selection) : (isset($GLOBALS['useCMS']) ? '1' : '0');
            $res = array_merge(array(
                'test' => $input->urlSegment2,
                'hasUsedCMS' => $resUsedCMS,
                'executiontime' => $timer->current('test'),
                'engine' => $api->api,
                'php' => $api->ver,
                'os' => $api->sys,
                'imageMagick' => ImagickInfo::getImageMagickVersion(false)),
                ImagickInfo::getImageMagickInfos()
                );
            $hn = new hn_basic();
            $hn->my_var_dump($res, 1);
            echo "<pre>Test execution time: {$timer->current('test')}</pre>";
        }
        else {
            echo "<pre>Test execution time: {$timer->current('test')}</pre>";
        }
    }
    else {
        if(!empty($input->urlSegment1)) {
            $res = hnReadTesttime($coreImageSizer, $group, $test);
            if(0<$res[1]) {
                echo "<pre>Last run was on " . date('d.m.Y - H:i', $res[1]) . ", test execution time: {$res[0]}</pre>";
            }
        }
    }
    if(isset($GLOBALS['useCMS'])) unset($GLOBALS['useCMS']);
    unset($timer);


    if(!empty($input->urlSegment1)) {
        echo "<div style='clear:both !important;'>&nbsp;</div>";
        $linktext = $user->isLoggedIn() ? 'run this test with' : 'view this test result from';
        if('imagick-resizer'==$page->name || 'imagick-resizer'==$page->parent->name) {
            $pname = 'image-sizer-core';
            echo "<p id='competitor'><a href='/{$pname}/{$group}/{$test}/'>{$linktext} Image Sizer</a></p>";
        }
        else {
            $pname = 'imagick-resizer';
            echo "<p id='competitor'><a href='/{$pname}/{$group}/{$test}/'>{$linktext} Imagick Resizer</a></p>";
        }
    }
}

include("./foot.inc");
