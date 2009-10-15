<?php
/**
 * Copyright 2006, 2007, 2008, 2009 Eric D. Hough (http://ehough.com)
 * 
 * This file is part of TubePress (http://tubepress.org)
 * 
 * TubePress is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 * 
 * TubePress is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with TubePress.  If not, see <http://www.gnu.org/licenses/>.
 *
 */

function_exists('tubepress_load_classes')
    || require(dirname(__FILE__) . '/../../../../tubepress_classloader.php');
tubepress_load_classes(array('org_tubepress_options_category_Gallery',
    'org_tubepress_options_Type',
    'org_tubepress_message_MessageService',
    'org_tubepress_options_reference_OptionsReference',
    'org_tubepress_options_storage_StorageManager',
    'org_tubepress_template_Template'));

/**
 * Prints widgets for the options form
 *
 */
class org_tubepress_options_form_WidgetPrinter
{
    private $_optionsReference;
    private $_messageService;
    private $_tpl;
    private $_tpsm;

    public function getHtml($optionName)
    {
        $this->_processSingleWidget($optionName);
        
        return $this->_tpl->getHtml();
    }
    
    public function getHtmlForRadio($optionName)
    {
        $this->_displayGalleryInput($optionName, $this->_tpsm->get(org_tubepress_options_category_Gallery::MODE));
        
        return $this->_tpl->getHtml();
    }
    
    private function _processSingleWidget($optionName)
    {
        $type = $this->_optionsReference->getType($optionName);
        switch ($type) {
            case org_tubepress_options_Type::BOOL:
                $this->_displayBooleanInput($optionName);
                break;
            case org_tubepress_options_Type::TEXT:
            case org_tubepress_options_Type::INTEGRAL:
                $this->_displayTextInput($optionName);
                break;
            case org_tubepress_options_Type::COLOR:
                $this->_displayColorInput($optionName);
                break;
            case org_tubepress_options_Type::ORDER:
            case org_tubepress_options_Type::PLAYER:
            case org_tubepress_options_Type::TIME_FRAME:
            case org_tubepress_options_Type::SAFE_SEARCH:
            case org_tubepress_options_Type::PLAYER_IMPL:
                $this->_displayMenuInput($optionName, $this->_createMenuItemsArray($type));
        }
    }
    
    private function _createMenuItemsArray($optionType)
    {
        $resuls = array();
        foreach ($this->_optionsReference->getValidEnumValues($optionType) as $value) {
            $results[$this->_messageService->_("$optionType-$value")] = $value;
        }
        return $results;
    }
    
    /**
     * Displays a drop-down menu
     *
     * @param string           $name   The name of the select input
     * @param array            $values The possible values for the drop-down
     * 
     * @return void
     */
    private function _displayMenuInput($name, $values)
    {   
        $value = $this->_tpsm->get($name);
        $this->_tpl->setVariable("OPTION_NAME", $name);
        foreach ($values as $validValueTitle => $validValue) {
            
            if ($validValue === $value) {
                $this->_tpl->setVariable("OPTION_SELECTED", "SELECTED");
            }
            $this->_tpl->setVariable("MENU_ITEM_TITLE", $validValueTitle);
            $this->_tpl->setVariable("MENU_ITEM_VALUE", $validValue);
            $this->_tpl->parse("menuItem");
        }
        $this->_tpl->parse("menu");
    }
    
    /**
     * Displays a text input
     *
     * @param string           $name  The name of the input
     * 
     * @return void
     */
    private function _displayTextInput($name)
    {    
        $value = $this->_tpsm->get($name);
        $this->_tpl->setVariable("OPTION_VALUE", $value);
        $this->_tpl->setVariable("OPTION_NAME", $name);
        $this->_tpl->parse("text");
    }
    
    /**
     * Displays a text input
     *
     * @param string           $name  The name of the input
     * 
     * @return void
     */
    private function _displayColorInput($name)
    {    
        $value = $this->_tpsm->get($name);
        $this->_tpl->setVariable("OPTION_VALUE", $value);
        $this->_tpl->setVariable("OPTION_NAME", $name);
        $this->_tpl->parse("color");
    }
    
    /**
     * Displays a checkbox input
     *
     * @param string           $name  The name of the checkbox input
     * 
     * @return void
     */
    private function _displayBooleanInput($name)
    {   
        $value = $this->_tpsm->get($name);
        $this->_tpl->setVariable("OPTION_NAME", $name);
        
        if ($value) {
            $this->_tpl->setVariable("OPTION_SELECTED", "CHECKED");
        }
        $this->_tpl->parse("checkbox");
    }
    
    /**
     * Displays a radio button and then an optional additional input
     *
     * @param string           $name  The name of the input
     * @param string           $value The current value
     * 
     * @return void
     */
    private function _displayGalleryInput($name, $value)
    {   
        $this->_tpl->setVariable("OPTION_NAME", $name);
        if ($name === $value) {
            $this->_tpl->setVariable("OPTION_SELECTED", "CHECKED");
        }
        $this->_tpl->parse("galleryType");
    }
    
    public function setMessageService(org_tubepress_message_MessageService $messageService) { $this->_messageService = $messageService; }
    public function setOptionsReference(org_tubepress_options_reference_OptionsReference $reference) { $this->_optionsReference = $reference; }
    public function setStorageManager(org_tubepress_options_storage_StorageManager $storageManager) { $this->_tpsm = $storageManager; }
    public function setTemplate(org_tubepress_template_Template $template) { $this->_tpl = $template; }
 
}
