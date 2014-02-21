<?php
/**
 * Copyright 2006 - 2014 TubePress LLC (http://tubepress.com)
 *
 * This file is part of TubePress (http://tubepress.com)
 *
 * This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at http://mozilla.org/MPL/2.0/.
 */

/**
 * Handles applying video meta info to the gallery template.
 */
class tubepress_addons_core_impl_listeners_template_SingleVideoMeta
{
    public function onSingleVideoTemplate(tubepress_api_event_EventInterface $event)
    {
        $template        = $event->getSubject();
        $context         = tubepress_impl_patterns_sl_ServiceLocator::getExecutionContext();
        $messageService  = tubepress_impl_patterns_sl_ServiceLocator::getMessageService();
        $optionProvider  = tubepress_impl_patterns_sl_ServiceLocator::getOptionProvider();

        /**
         * @var $metaNameService tubepress_addons_core_impl_options_MetaOptionNameService
         */
        $metaNameService = tubepress_impl_patterns_sl_ServiceLocator::getService(tubepress_addons_core_impl_options_MetaOptionNameService::_);
        $metaNames       = $metaNameService->getAllMetaOptionNames();
        $shouldShow      = array();
        $labels          = array();

        foreach ($metaNames as $metaName) {

            $shouldShow[$metaName] = $context->get($metaName);
            $labels[$metaName]     = $messageService->_($optionProvider->getLabel($metaName));
        }

        $template->setVariable(tubepress_api_const_template_Variable::META_SHOULD_SHOW, $shouldShow);
        $template->setVariable(tubepress_api_const_template_Variable::META_LABELS, $labels);
    }
}
