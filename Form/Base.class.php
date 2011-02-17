<?php
/**
 *   @copyright Copyright (c) 2007 Quality Unit s.r.o.
 *   @author Juraj Simon
 *   @package WpPostAffiliateProPlugin
 *   @version 1.0.0
 *
 *   Licensed under GPL2
 */

abstract class postaffiliatepro_Form_Base extends postaffiliatepro_Base {
    
    const TYPE_FORM = 'default';
    const TYPE_TEMPLATE = 'template';
    
    /**
     * @var HTMLForm
     */
    private $form;    
    private $settings;
    private $formName;

    public function __construct($name = null, $action = null) {
        $this->formName = $name;
        if ($name !== null && $action !== null) {
            $this->loadSettingsString($name);
            $this->form = new HTMLForm($name, 'post', $action, '', $this->getType());
        } else {
            $this->form = new HTMLForm('', '', '', '', $this->getType());
        }
        $this->initForm();
    }

    private function loadSettingsString($name) {
        ob_start();
        settings_fields($name);
        $this->settings = ob_get_contents();
        ob_end_clean();
    }
    
    protected abstract function getType();

    protected abstract function initForm();

    protected abstract function getTemplateFile();

    protected function addSubmit() {
        $this->form->add('submit', 'submit', _('Save changes'), array('class'=>'button-primary'));
    }

    protected function addHtml($name, $code) {
        $this->form->add('html', $name, $code);
    }
    
    protected function getOption($name) {
        return get_option($name);
    }
    
    protected function addCheckbox($name, $templateName = null, $additionalCode = '') {
        if ($this->getOption($name) == 'true') {
            $checked = 'checked';
        } else {
            $checked = '';
        }
        if ($templateName === null) {
            $templateName = $name;
        }
        $this->form->add('html', $templateName, '<input type="checkbox" name="'.$name.'" id="'.$name.'_" value="true" '.$checked.' '.$additionalCode.'></input>');
    }
    
    protected function parseBlock($name, $variables) {
        $this->form->parseBlock($name, $variables);
    }

    protected function addSelect($name, $options) {
        //options = assoc. arr, key(value) and value(name) od select option
        $select = $this->form->add('select', $name, $this->getOption($name));
        $select->addOptions($options);
        return $select;
    }

    protected function addPassword($name, $size = 20) {
        $this->form->add('password', $name, '', array('size' => $size, 'value' => $this->getOption($name)));
    }

    protected function addTextBox($name, $size = 20) {
        $this->form->add('text', $name, '', array('size' => $size, 'value' => $this->getOption($name)));
    }

    public function render($toVar = false) {
        if ($this->formName != null) {            
            $this->form->add('html', 'form-settings', $this->settings);
        }        
        return $this->form->render($this->getTemplateFile(), $toVar);
    }
    
    public function renderTemplate($templateFile) {
        $this->form->render($templateFile);
    }
}
?>