<?php
/**
 * Copyright 2006 - 2012 Eric D. Hough (http://ehough.com)
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
 * Registers a few extensions to allow TubePress to work inside WordPress.
 */
class tubepress_plugins_core_wordpresscore_WordPressCore
{
    public static function registerWordPressListeners()
    {
        $environmentDetector = tubepress_impl_patterns_ioc_KernelServiceLocator::getEnvironmentDetector();

        if (! $environmentDetector->isWordPress()) {

            //short circuit
            return;
        }

        $eventDispatcher = tubepress_impl_patterns_ioc_KernelServiceLocator::getEventDispatcher();

        $eventDispatcher->addListener(tubepress_api_const_event_CoreEventNames::BOOT,
            array(new tubepress_plugins_wordpresscore_listeners_WordPressIocContainerBuilder(), 'onBoot'));

        $eventDispatcher->addListener(tubepress_api_const_event_CoreEventNames::BOOT,
            array(new tubepress_plugins_wordpresscore_listeners_WordPressApiIntegrator(), 'onBoot'));
    }
}

tubepress_plugins_core_wordpresscore_WordPressCore::registerWordPressListeners();