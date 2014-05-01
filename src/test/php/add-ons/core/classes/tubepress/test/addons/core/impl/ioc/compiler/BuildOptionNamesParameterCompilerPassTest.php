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
 * @covers tubepress_addons_core_impl_ioc_compiler_BuildOptionNamesParameterCompilerPass<extended>
 */
class tubepress_test_addons_core_impl_ioc_compiler_BuildOptionNamesParameterCompilerPassTest extends tubepress_test_TubePressUnitTest
{
    /**
     * @var tubepress_addons_core_impl_ioc_compiler_BuildOptionNamesParameterCompilerPass
     */
    private $_sut;

    /**
     * @var ehough_mockery_mockery_MockInterface
     */
    private $_mockContainer;

    public function onSetup()
    {
        $this->_sut           = new tubepress_addons_core_impl_ioc_compiler_BuildOptionNamesParameterCompilerPass();
        $this->_mockContainer = ehough_mockery_Mockery::mock('tubepress_api_ioc_ContainerBuilderInterface');
    }

    public function testProcess()
    {
        $mockOptionProviderReferences = array(

            'x' => array(tubepress_api_options_ProviderInterface::_)
        );

        $mockOptionProvider = ehough_mockery_Mockery::mock(tubepress_api_options_ProviderInterface::_);
        $mockOptionProvider->shouldReceive('getAllOptionNames')->once()->andReturn(array('one', 'two', 'three'));

        $this->_mockContainer->shouldReceive('findTaggedServiceIds')->once()->with(tubepress_api_options_ProviderInterface::_)->andReturn($mockOptionProviderReferences);
        $this->_mockContainer->shouldReceive('get')->once()->with('x')->andReturn($mockOptionProvider);
        $this->_mockContainer->shouldReceive('setParameter')->once()->with('tubePressOptionNames', array('one', 'two', 'three'));

        $this->_sut->process($this->_mockContainer);

        $this->assertTrue(true);
    }
}