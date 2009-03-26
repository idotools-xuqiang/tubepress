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

/**
 * Registers TubePress as a widget
 *
 */
function tubepress_init_widget()
{
	$msg = new org_tubepress_message_WordPressMessageService();
	$widget_ops = array('classname' => 'widget_tubepress', 
	    'description' => $msg->_("widget-description"));
	wp_register_sidebar_widget('tubepress', "TubePress", 
	    'tubepress_widget', $widget_ops);
	wp_register_widget_control('tubepress', "TubePress", 
	    'tubepress_widget_control');
}

/**
 * Executes the main widget functionality
 *
 * @param unknown_type $opts
 */
function tubepress_widget($opts)
{
	extract($opts);
	
	$iocContainer = new org_tubepress_ioc_DefaultIocService();
	$tpom = $iocContainer->get(org_tubepress_ioc_IocService::OPTIONS_MGR);
	
	$tpom->setCustomOptions(
	    array(org_tubepress_options_category_Display::RESULTS_PER_PAGE => 3,
	        org_tubepress_options_category_Meta::VIEWS => false,
	        org_tubepress_options_category_Meta::DESCRIPTION => true,
	        org_tubepress_options_category_Display::DESC_LIMIT => 50,
	        org_tubepress_options_category_Display::CURRENT_PLAYER_NAME => org_tubepress_player_Player::POPUP,
	        org_tubepress_options_category_Display::THUMB_HEIGHT => 105,
	        org_tubepress_options_category_Display::THUMB_WIDTH => 135
	        )
	);
	
	$shortcodeService = $iocContainer->get(org_tubepress_ioc_IocService::SHORTCODE);
	$wpsm = $iocContainer->get(org_tubepress_ioc_IocService::STORAGE);
	
	/* now apply the user's shortcode */
	$shortcodeService->parse($wpsm->get(org_tubepress_options_category_Widget::TAGSTRING), $tpom);
	
	$gallery = $iocContainer->get(org_tubepress_ioc_IocService::WIDGET_GALL);
		
	/* get the output */
	$out = $gallery->generate(mt_rand());

	/* do the standard WordPress widget dance */
	echo $before_widget . $before_title . 
	    $wpsm->get(org_tubepress_options_category_Widget::TITLE) .
	    $after_title . $out . $after_widget;
}

/**
 * Handles the widget configuration panel
 *
 */
function tubepress_widget_control()
{
    $iocContainer = new org_tubepress_ioc_DefaultIocService();
    $wpsm         = $iocContainer->get(org_tubepress_ioc_IocService::STORAGE);
    $msg          = $iocContainer->get(org_tubepress_ioc_IocService::MESSAGE);
    
	if ( $_POST["tubepress-widget-submit"] ) {
		$wpsm->set(org_tubepress_options_category_Widget::TAGSTRING, 
		    strip_tags(stripslashes($_POST["tubepress-widget-tagstring"])));
		$wpsm->set(org_tubepress_options_category_Widget::TITLE, 
		    strip_tags(stripslashes($_POST["tubepress-widget-title"])));
	}

    /* load up the gallery template */
    $tpl = new net_php_pear_HTML_Template_IT(dirname(__FILE__) . "/../../../ui/widget/html_templates");
    if (!$tpl->loadTemplatefile("controls.tpl.html", true, true)) {
        throw new Exception("Couldn't load widget control template");
    }
    
    $tpl->setVariable("WIDGET-TITLE", 
        $msg->_("options-meta-title-title"));
    $tpl->setVariable("WIDGET-TITLE-VALUE", 
        $wpsm->get(org_tubepress_options_category_Widget::TITLE));
    $tpl->setVariable("WIDGET-TAGSTRING", 
        $msg->_("widget-tagstring-description"));
    $tpl->setVariable("WIDGET-TAGSTRING-VALUE", 
        $wpsm->get(org_tubepress_options_category_Widget::TAGSTRING));
    echo $tpl->get();
}

?>
