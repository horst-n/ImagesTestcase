<?php
/*******************************************************************************
  *  PageimageNamingScheme
  *
  * ---------------------------------------------------------------------------
  *  @version     -   '0.0.4'
  *  @date        -   $Date: 2014/05/13 18:08:52 $
  *  @licence     -   GNU GPL v2 - http://www.gnu.org/licenses/gpl-2.0.html
  * ---------------------------------------------------------------------------
  *  $Source: /WEB/pw4/htdocs/site/modules/PageimageNamingScheme/PageimageNamingSchemeConfig.php,v $
  *  $Id: PageimageNamingSchemeConfig.php,v 1.3.2.2 2014/05/13 18:08:52 horst Exp $
  ******************************************************************************
**/


class PageimageNamingSchemeConfig extends Wire {

    public function getConfig(array $data) {

        // check that they have the required PW version
        if(version_compare(wire('config')->version, '2.4.2', '<')) {
            $this->error(" requires ProcessWire 2.4.2 or newer. Please update.");
        }

        $modules = wire('modules');
        $form = new InputfieldWrapper();

        $field = $modules->get("InputfieldMarkup");
        $field->attr('name', 'info1');
        $field->collapsed = Inputfield::collapsedNo;
        $field->attr('value',
            "This module implements a new naming scheme that can reflect much more image variations whereas the core naming scheme reflect only a lesser part of them. <br /><br />
            Images settings for sharpening s0 | s1 | s2 | s3 is supported, quality q0 - q100 and for images created with 'upscaling = false' a 'nu' is appended (nu = no upscaling).
            All settings regarding to cropped variations are supported like with the core naming scheme with one difference, the centered cropped variation is reflected by a 'c' in the filename.<br /><br />
            If you want to uninstall this module you may run a cleaning script that removes all Imagevariations of all pages, sitewide! To do that you have to tipp the checkbox below and submit the form.
            Regarding to the amount of pages and images you have installed in your site this can take some time.<br /><br />
            You may also take care manually of that. But be aware that uninstalling this module can lead in a lot of orphaned imagevariation files without cleaning.<br /><br />"
            );
        $field->label = __('Info');
        $field->columnWidth = 100;
        $form->add($field);

        $field = $modules->get('InputfieldCheckbox');
        $field->attr('name', 'remove_all_variations');
        $field->label = __(' Before uninstall this module: Remove all extended Imagevariations to avoid orphaned files!');
        $field->attr('value', 1);
        $field->attr('checked', '');
        $field->columnWidth = 65;
        $form->add($field);

        if(wire('session')->remove_all_variations) {
            wire('session')->remove('remove_all_variations');
            $testmode = '1'==$data['do_only_test_run'] ? true : false;
            $field->notes = $this->doTheDishes( !$testmode );
        } else if(wire('input')->post->remove_all_variations) {
            wire('session')->set('remove_all_variations', 1);
        }

        $field = $modules->get('InputfieldCheckbox');
        $field->attr('name', 'do_only_test_run');
        $field->label = __('Run only in test mode! Do not delete the variations.');
        $field->attr('value', 1);
        $field->attr('checked', '1');
        $field->columnWidth = 35;
        $form->add($field);

        return $form;
    }


    public function doTheDishes($deleteVariations=false) {
        $errors = array();
        $success = false;
        try {
            $success = $this->removeAllVariations($deleteVariations);

        } catch(Exception $e) {
            $errors[] = $e->getMessage();
        }
        if($success) {
            $note = $this->_('SUCCESS! All Imagevariations are removed. Now you can safely uninstall the module!');
            $this->message($note);

        } else {
            $note = $this->_('ERROR: Removing Imagevariations was not successfully finished. Refer to the errorlog for more details.');
            $this->error($note);
        }
        return $note;
    }


    private function removeAllVariations($deleteVariations=false) {
        $stack = new filo();
        $stack->push(1);
        while($id = $stack->pop()) {
            set_time_limit(intval(15));
            // get the page
            $page = wire('pages')->get($id);
            if(0==$page->id) continue;
            // add children to the stack
            foreach($page->children('include=all') as $child) {
                $stack->push($child->id);
            }
            // iterate over the fields
            foreach($page->fields as $field) {
                if(! $field->type instanceof FieldtypeImage) {
                    continue;
                }
                // get the images
                $imgs = $page->{$field->name};
                $count = count($imgs);
                if(0==$count) continue;
                $this->message('- found page: ' . $page->title . ' - with imagefield: ' . $field->name . ' - count: ' . $count);
                foreach($imgs as $img) {
                    if(true===$deleteVariations) {
                        #$this->message(' REMOVED! ');
                        $img->removeVariations();
                    }
                }
            }
            wire('pages')->uncache($page);
        }
        return true;
    }

}

if( ! class_exists('filo')) {
    /** @shortdesc: Stack, First In - Last Out  **/
    class filo {

        /** @private **/
        var $elements;
        /** @private **/
        var $debug;

        /** @private **/
        function filo($debug=FALSE) {
            $this->debug = $debug;
            $this->zero();
        }

        /** @private **/
        function push($elm) {
            array_push($this->elements, $elm);
            if($this->debug) echo "<p>filo->push(".$elm.")</p>";
        }

        /** @private **/
        function pop() {
            $ret = array_pop( $this->elements );
            if($this->debug) echo "<p>filo->pop() = $ret</p>";
            return $ret;
        }

        /** @private **/
        function zero() {
            $this->elements = array();
            if($this->debug) echo "<p>filo->zero()</p>";
        }
    }
} // end class FILO

