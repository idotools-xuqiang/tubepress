<?php
/*
 * Copyright 2006 - 2016 TubePress LLC (http://tubepress.com)
 *
 * This file is part of TubePress (http://tubepress.com)
 *
 * This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at http://mozilla.org/MPL/2.0/.
 */

/**
 * @covers tubepress_event_ioc_EventExtension
 */
class tubepress_test_event_ioc_EventExtensionTest extends tubepress_api_test_ioc_AbstractContainerExtensionTest
{
    /**
     * @return tubepress_event_ioc_EventExtension
     */
    protected function buildSut()
    {
        return  new tubepress_event_ioc_EventExtension();
    }

    protected function prepareForLoad()
    {
        $this->expectRegistration(
            'container_aware_event_dispatcher',
            'Symfony\Component\EventDispatcher\ContainerAwareEventDispatcher'
        )->withArgument(new tubepress_api_ioc_Reference('symfony_service_container'));

        $this->expectRegistration(
            tubepress_api_event_EventDispatcherInterface::_,
            'tubepress_event_impl_tickertape_EventDispatcher'
        )->withArgument(new tubepress_api_ioc_Reference('container_aware_event_dispatcher'));
    }

    protected function getExpectedExternalServicesMap()
    {
        return array(

            'symfony_service_container' => 'Symfony\Component\DependencyInjection\ContainerInterface',
        );
    }
}
