<?php

$hasIR = $modules->isInstalled('ImagickResizer');
$hasIR = $hasIR ? ImagickInfo::hasImagick() : $hasIR;

$bodyCssClass = $user->isLoggedIn() ? 'testing' : '';

/**
 * Demo site header include file (HTML5)
 *
 * Note that this file has nothing to do with ProcessWire. We just split our common
 * header and footer markup into separate files (head.inc and foot.inc) like this,
 * since it was common to all of our templates.
 *
**/
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />

    <title><?php echo $page->get("headline|title"); ?></title>

    <meta name="description" content="<?php echo $page->summary; ?>" />

    <meta name="generator" content="ProcessWire <?php echo $config->version; ?>" />

    <!-- <link href='http://fonts.googleapis.com/css?family=Source+Code+Pro|Muli|Nunito' rel='stylesheet' type='text/css' /> -->
    <link href='http://fonts.googleapis.com/css?family=Source%2BCode%2BPro%7CMuli%7CNunito' rel='stylesheet' type='text/css' />

    <link rel="stylesheet" type="text/css" href="<?php echo $config->urls->templates?>styles/main.css" />

    <!--[if IE]>
    <link rel="stylesheet" type="text/css" href="<?php echo $config->urls->templates?>styles/ie.css" />
    <![endif]-->

    <script type="text/javascript" src="<?php echo $config->urls->templates?>scripts/jquery-1.4.2.min.js"></script>

    <script type="text/javascript" src="<?php echo $config->urls->templates?>scripts/main.js"></script>

    <!--
    This website is powered by ProcessWire CMF/CMS.
    ProcessWire is a free open source content management framework licensed under the GNU GPL.
    ProcessWire is Copyright 2012 by Ryan Cramer / Ryan Cramer Design, LLC.
    Learn more about ProcessWire at: http://processwire.com
    -->

</head>

<body class="<?php echo $bodyCssClass;?>">

    <p id='bgtitle'><?php

        // print the section title as big faded text that appears near the top left of the page
        echo $page->rootParent->title;

    ?></p>

    <div id="masthead" class="masthead">

        <div class="container">

            <a href='<?php echo $config->urls->root; ?>'><p id='logo'>ProcessWire</p></a>

            <ul id='topnav'><?php

                // Create the top navigation list by listing the children of the homepage.
                // If the section we are in is the current (identified by $page->rootParent)
                // then note it with <a class='on'> so we can style it differently in our CSS.
                // In this case we also want the homepage to be part of our top navigation,
                // so we prepend it to the pages we cycle through:

                $homepage = $pages->get("/");
                $children = $homepage->children;
                #$children->prepend($homepage);

                foreach($children as $child) {
                    if('Imagick Resizer'==$child->title && !$hasIR) continue;
                    $class = $child === $page->rootParent ? " class='on'" : '';
                    echo "<li><a$class href='{$child->url}'>{$child->title}</a></li>";
                }

            ?></ul>

            <ul id='breadcrumb'><?php

                // Create breadcrumb navigation by cycling through the current $page's
                // parents in order, linking to each:

                foreach($page->parents as $parent) {
                    echo "<li><a href='{$parent->url}'>{$parent->title}</a> &gt; </li>";
                }
                echo "<li><a href='$page->url'>{$page->title}</a></li>";

            ?></ul>

<?php
            echo "            <h1 id='title'>" . $page->get("headline|title");
                if('imagick-resizer'==$page->name && $hasIR) {
                    $m = wire('modules')->get('ImagickResizer')->getModuleInfo();
                    $version = preg_replace('/(\d)(?=\d)/', '$1.', str_pad("{$m['version']}", 3, "0", STR_PAD_LEFT));
                    echo " ($version)";
                    $version = trim(str_replace('http://www.imagemagick.org', '', ImagickInfo::getImageMagickVersion(true)));
                    echo " <span style='font-size:small'>$version</span></h1>";
                }
                elseif('image-sizer-core'==$page->name) {
                    $version = wire('config')->version;
                    echo " ($version)</h1>";

                }
            echo '</h1>';
//<!--
//            <form id='search_form' action='< ?php echo $config->urls->root? >search/' method='get'>
//                <input type='text' name='q' id='search_query' value='< ?php echo htmlentities($input->whitelist('q'), ENT_QUOTES, 'UTF-8'); ? >' />
//                <button type='submit' id='search_submit'>Search</button>
//            </form>
//-->
?>


        </div>

    </div>

    <div id="content" class="content">

        <div class="container">

            <div id="bodycopy">
