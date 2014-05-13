<?php
/**
* ----------------------------------------------------------------------------------------------------
*  $Source: /WEB/pw4/htdocs/site/templates/tests.inc,v $
*  $Id: tests.inc,v 1.8.2.2 2014/05/11 16:02:25 horst Exp $
* ----------------------------------------------------------------------------------------------------
*/

    require_once(dirname(__FILE__) . '/tests.functions.inc');
    $coreImageSizer = 'image-sizer-core'==$page->name ? true : false;


    $dir = dirname(__FILE__) . '/tests/';
    $folders = $testcases = array();
    $hn = new hn_basic();

    if($dh = opendir($dir)) {
        while (($file = readdir($dh)) !== false) {
            if(is_file($dir . '/' . $file)) continue;
            if(is_dir($dir . '/' . $file . '/') && !in_array($file, array('.', '..'))) {
                $folders["$file"] = $file;
            }
        }
        closedir($dh);
    }

    foreach($folders as $folder) {
        if(is_dir($dir . $folder . '/')) {
            if($dh = opendir($dir . $folder . '/')) {
                while (($file = readdir($dh)) !== false) {
                    if(is_file($dir . $folder . '/' . $file) && 'php' == strtolower( pathinfo($dir . $folder . '/' . $file, PATHINFO_EXTENSION))) {
                        $test = str_replace('.php', '', $file);
                        $testcases[$folder]["$test"] = $test;
                    }
                }
                closedir($dh);
            }
        }
    }
    $testcases = $hn->hn_arrayKey_sort($testcases, false, false);


    $group = $input->urlSegment1;
    $test = $input->urlSegment2;


    if(empty($group) && empty($test)) {
        // we are on a TopPage and display a menu
        echo "<h3>Tests</h3>\n";
        foreach($testcases as $group => $tests) {
            if(!is_array($tests)) continue;
            echo "<h4>" . strtoupper("$group") . "</h4>\n\t<ul>\n";
            $tests = $hn->hn_arrayKey_sort($tests, false, false);
            foreach($tests as $test) {
                $url = $page->url . "{$group}/{$test}/";
                echo "\t\t<li><a href='$url'>$test</a></li>\n";
            }
            echo "\t</ul>\n";
        }

        return;
    }


    if(!empty($group)) {
        if(!in_array($group, $folders)) {
            echo "<p>ERROR: requested unknown Testgroup!</p>";
            return;
        }
    }
    else {
        echo "<p>ERROR: requested no Testgroup!</p>";
        return;
    }


    if(empty($test)) {
        echo "<p>ERROR: requested no Test!</p>";
        return;
    }


    if(!in_array($test, $testcases[$group])) {
        echo "<p>ERROR: requested unknown Test!</p>";
        return;
    }


    echo "<h4>$group :: $test</h4>";

    // prepare for a single test

    $imagesBody = '';
    $p = $pages->get('/images/' . strtolower($group) . '/' . strtolower($test) . '/');
    if(0<$p->id) {
        // get special set of images
        $images = $p->images;
        $cropimages = $p->cropimages;
        $imagesBody = $p->body;
    }
    else {
        $p = $pages->get('/images/' . strtolower($group) . '/');
        if(0<$p->id) {
            // get special set of images
            $images = $p->images;
            $cropimages = $p->cropimages;
            $imagesBody = $p->body;
        }
        else {
            // get default set of images
            $images = $pages->get('/images/')->images;
            $cropimages = $p->cropimages;
            $imagesBody = $p->body;
        }
    }

    if(!$images || 0==$images->count) {
        echo "<p>WARNING: no Images found for the Test!</p>";
        return;
    }

    $testfile = dirname(__FILE__) . '/tests/' . strtolower($group) . '/' . strtolower($test) . '.php';
    if(!file_exists($testfile)) {
        echo "<p>WARNING: have not found the testinstructions!</p>";
        return;
    }


    $removeVariations = false;
    if($user->isLoggedIn() && $user->hasRole('superuser')) {
        $removeVariations = true;
    }


    if($coreImageSizer) $GLOBALS['SKIP_IMAGICK_RESIZER'] = true;

    if(!$coreImageSizer && !$modules->isInstalled('ImagickResizer')) {
        echo "<p style='color:red;'>needed Module ImagickResizer is not installed!</p>";
    }
    else {
        echo $imagesBody;
        $cachedOutputName = ($coreImageSizer ? 'image-sizer-core' : 'imagick-resizer') . "---{$group}---{$test}";
        $cachedOutputFilename = $config->paths->cache . $cachedOutputName . '.cache';
        $hnb = new hn_basic();
        $outputArray = array();
        if(!$hnb->GetCacheArray($outputArray, $cachedOutputFilename) || $user->isLoggedIn()) {
            $options = array(
                'quality'    => 95,
                'sharpening' => 'none',
                'upscaling'  => true,
                'cropping'   => true,
                'useCMS'     => false,
                'keepICC'    => false,
                'keepXMP'    => false,
                'keepEXIF'   => false,
                'appendix'   => ($coreImageSizer ? 'is' : ''),
                'useGD'      => ($coreImageSizer ? true : false),
                'forcenew'   => ((isset($removeVariations) && true===$removeVariations) ? true : false)
                );
            $testOutPut = '';
            $outputArray = array();
            $timer->start('test');
            include($testfile);
            $timer->stop('test');
            $outputArray['testOutPut'] = $testOutPut;
            $hnb->SetCacheArray($outputArray, $cachedOutputFilename);
            hnWriteTesttime($coreImageSizer, $group, $test, $timer->current('test'));
        }
        echo $outputArray['testOutPut'];
        unset($outputArray, $testOutPut, $cachedOutputName, $cachedOutputFilename, $hnb);
    }

    if(isset($GLOBALS['SKIP_IMAGICK_RESIZER'])) unset($GLOBALS['SKIP_IMAGICK_RESIZER']);
