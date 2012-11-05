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
class FakeThingy
{
    public $_arg;

    public function __construct($arg)
    {
        $this->_arg = $arg;
    }
}

class tubepress_impl_options_ui_DefaultFieldBuilderTest extends TubePressUnitTest
{
    private $_sut;

    private $_mockOptionDescriptorReference;

    private $_mockMessageService;

    private $_mockStorageManager;

    private $_mockHttpRequestParameterService;

    private $_mockTemplateBuilder;

    private $_mockEnvironmentDetector;

    public function setup()
    {
        $this->_mockOptionDescriptorReference   = Mockery::mock(tubepress_spi_options_OptionDescriptorReference::_);
        $this->_mockMessageService              = Mockery::mock(tubepress_spi_message_MessageService::_);
        $this->_mockStorageManager              = Mockery::mock(tubepress_spi_options_StorageManager::_);
        $this->_mockHttpRequestParameterService = Mockery::mock(tubepress_spi_http_HttpRequestParameterService::_);
        $this->_mockTemplateBuilder             = Mockery::mock('ehough_contemplate_api_TemplateBuilder');
        $this->_mockEnvironmentDetector         = Mockery::mock(tubepress_spi_environment_EnvironmentDetector::_);

        tubepress_impl_patterns_ioc_KernelServiceLocator::setOptionDescriptorReference($this->_mockOptionDescriptorReference);
        tubepress_impl_patterns_ioc_KernelServiceLocator::setMessageService($this->_mockMessageService);
        tubepress_impl_patterns_ioc_KernelServiceLocator::setOptionStorageManager($this->_mockStorageManager);
        tubepress_impl_patterns_ioc_KernelServiceLocator::setHttpRequestParameterService($this->_mockHttpRequestParameterService);
        tubepress_impl_patterns_ioc_KernelServiceLocator::setTemplateBuilder($this->_mockTemplateBuilder);
        tubepress_impl_patterns_ioc_KernelServiceLocator::setEnvironmentDetector($this->_mockEnvironmentDetector);

        $this->_sut = new tubepress_impl_options_ui_DefaultFieldBuilder();
    }

    public function testBuild()
    {
        $result = $this->_sut->build('something awesome', 'FakeThingy');

        $this->assertTrue($result instanceof FakeThingy);
        $this->assertEquals('something awesome', $result->_arg);
    }

    public function testBuildMeta()
    {
        $this->_setupOptionDescriptorReferenceForMetaMultiSelect();

        $mockProvider1 = Mockery::mock(tubepress_spi_provider_PluggableVideoProviderService::_);
        $mockProvider1->shouldReceive('getAdditionalMetaNames')->once()->andReturn(array('xyz'));

        $mockProvider2 = Mockery::mock(tubepress_spi_provider_PluggableVideoProviderService::_);
        $mockProvider2->shouldReceive('getAdditionalMetaNames')->once()->andReturn(array('abc'));

        $mockProviders = array($mockProvider1, $mockProvider2);

        $mockServiceCollectionsRegistry = Mockery::mock(tubepress_spi_patterns_sl_ServiceCollectionsRegistry::_);
        $mockServiceCollectionsRegistry->shouldReceive('getAllServicesOfType')->once()->with(tubepress_spi_provider_PluggableVideoProviderService::_)->andReturn($mockProviders);

        tubepress_impl_patterns_ioc_KernelServiceLocator::setServiceCollectionsRegistry($mockServiceCollectionsRegistry);

        $result = $this->_sut->buildMetaDisplayMultiSelectField();

        $this->assertTrue($result instanceof tubepress_impl_options_ui_fields_MetaMultiSelectField);
    }

    private function _getOdNames()
    {
        return array(

            tubepress_api_const_options_names_Meta::AUTHOR,
            tubepress_api_const_options_names_Meta::CATEGORY,
            tubepress_api_const_options_names_Meta::DESCRIPTION,
            tubepress_api_const_options_names_Meta::ID,
            tubepress_api_const_options_names_Meta::LENGTH,
            'abc',
            'xyz',
            tubepress_api_const_options_names_Meta::KEYWORDS,
            tubepress_api_const_options_names_Meta::TITLE,
            tubepress_api_const_options_names_Meta::UPLOADED,
            tubepress_api_const_options_names_Meta::URL,
            tubepress_api_const_options_names_Meta::VIEWS,
        );
    }

    private function _setupOptionDescriptorReferenceForMetaMultiSelect()
    {
        $names = $this->_getOdNames();

        $ods = array();

        foreach ($names as $name) {

            $od = new tubepress_spi_options_OptionDescriptor($name);
            $od->setBoolean();

            $ods[] = $od;

            $this->_mockOptionDescriptorReference->shouldReceive('findOneByName')->with($name)->once()->andReturn($od);
        }

        return $ods;
    }
}
