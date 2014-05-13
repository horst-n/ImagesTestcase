
                <?php

                // Output navigation for any children below the bodycopy.
                // This navigation cycles through the page's children and prints
                // a link and summary:

                if($page->numChildren) {

                    echo "<ul class='nav'>";

                    foreach($page->children as $child) {
                        if('Imagick Resizer'==$child->title && !$hasIR) continue;
                        echo "<li><p><a href='{$child->url}'>{$child->title}</a><br /><span class='summary'>{$child->summary}</span></p></li>";
                    }

                    echo "</ul>";

                    if(!$hasIR) {
                        echo "<p>You have not installed the Imagick Resizer module!</p>";
                        echo "<p>Please login and go to modules section and install it.</p>";
                        echo "<ul><li>On the config page of the module please tip the checkbox for using the CMS with ICC profiles.</li>
                                <li> Open the 'CMS - ICC Color Management System' and check if it has selected a profile for all six usages.</li>";
                        echo "<li>Under 'Advanced' please select 'Do not use GD-lib, throw error instead' and save / submit it.</li></ul>";
                    }

                }

                ?>


            </div><!--/bodycopy-->

        </div><!--/container-->

    </div><!--/content-->

    <div id="footer" class="footer">

        <div class="container">

            <p>Powered by <a href='http://processwire.com'>ProcessWire Open Source CMS/CMF</a> &copy; <?php echo date("Y"); ?> <a href="http://www.ryancramer.com">Ryan Cramer Design, LLC</a></p>

        </div>

    </div>

    <?php

    // If the page is editable, then output a link that takes us straight to the page edit screen:
    if($page->editable()) {
        echo "<a class='nav' id='editpage' href='{$config->urls->admin}page/edit/?id={$page->id}'>Edit</a>";
    }

    ?>
</body>
</html>
