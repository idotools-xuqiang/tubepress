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
 * Describes an option that TubePress can work with.
 */
class tubepress_api_model_options_OptionDescriptor
{
    const _ = 'tubepress_api_model_options_OptionDescriptor';

    /**
     * @var string The globally unique name of this option.
     */
    private $_name;

    /**
     * @var array An array of acceptable values.
     */
    private $_acceptableValues;

    /**
     * @var array Aliases. Currently not used.
     */
    private $_aliases = array();

    /**
     * @var mixed The default value for this option.
     */
    private $_defaultValue;

    /**
     * @var string The human-readable description of this option. May contain HTML.
     */
    private $_description;

    /**
     * @var array Providers for which this option does not work.
     */
    private $_excludedProviders = array();

    /**
     * @var bool Boolean flag to indicate if this option is boolean.
     */
    private $_isBoolean = false;

    /**
     * @var string The short label for this option. 30 chars or less.
     */
    private $_label;

    /**
     * @var bool Is this option Pro only?
     */
    private $_proOnly = false;

    /**
     * @var bool Should we store this option in persistent storage?
     */
    private $_shouldPersist = true;

    /**
     * @var bool Can this option be set via shortcode?
     */
    private $_shortcodeSettable = true;

    /**
     * @var string Regex describing valid values that this option can take on (from a string).
     */
    private $_validValueRegex;

    /**
     * Constructor.
     *
     * @param string $name The globally unique name of this option.
     *
     * @throws InvalidArgumentException If the name is null or empty.
     */
    public function __construct($name)
    {
        if (! is_string($name) || ! isset($name)) {

            throw new InvalidArgumentException('Must supply an option name');
        }

        $this->_name = $name;
    }

    /**
     * @return array An array of acceptable values that this option can take. May be null or empty.
     */
    public final function getAcceptableValues()
    {
        return $this->_acceptableValues;
    }

    /**
     * @return array An array of aliases for this option. Not currently used. May be empty, never null.
     */
    public final function getAliases()
    {
        return $this->_aliases;
    }

    /**
     * @return mixed The default value for this option. May be null.
     */
    public final function getDefaultValue()
    {
        return $this->_defaultValue;
    }

    /**
     * @return string The human-readable description of this option. May be empty or null.
     */
    public final function getDescription()
    {
        return $this->_description;
    }

    /**
     * @return string The short label for this option. 30 chars or less.
     */
    public final function getLabel()
    {
        return $this->_label;
    }

    /**
     * @return string The globally unique name of this option.
     */
    public final function getName()
    {
        return $this->_name;
    }

    /**
     * @return string Regex describing valid values that this option can take on (from a string).
     */
    public final function getValidValueRegex()
    {
        return $this->_validValueRegex;
    }

    /**
     * @return bool True if this option has a description, false otherwise.
     */
    public final function hasDescription()
    {
        return $this->_description !== null;
    }

    /**
     * @return bool True if this option has a discrete set of acceptable values, false otherwise.
     */
    public final function hasDiscreteAcceptableValues()
    {
        return ! empty($this->_acceptableValues);
    }

    /**
     * @return bool True if this option has a label, false otherwise.
     */
    public final function hasLabel()
    {
        return $this->_label !== null;
    }

    /**
     * @return bool True if this option has a valid value regex, false otherwise.
     */
    public final function hasValidValueRegex()
    {
        return $this->_validValueRegex !== null;
    }

    /**
     * @return bool True if this option can be set via shortcode, false otherwise.
     */
    public final function isAbleToBeSetViaShortcode()
    {
        return $this->_shortcodeSettable;
    }

    /**
     * @return bool True if this option is applicable to Vimeo, false otherwise.
     */
    public final function isApplicableToVimeo()
    {
        return ! in_array(tubepress_spi_provider_Provider::VIMEO, $this->_excludedProviders);
    }

    /**
     * @return bool True if this option is applicable to YouTube, false otherwise.
     */
    public final function isApplicableToYouTube()
    {
        return ! in_array(tubepress_spi_provider_Provider::YOUTUBE, $this->_excludedProviders);
    }

    /**
     * @return bool True if this option takes on only boolean values, false otherwise.
     */
    public final function isBoolean()
    {
        return $this->_isBoolean;
    }

    /**
     * @return bool True if this option is applicable to all providers, false otherwise.
     */
    public final function isApplicableToAllProviders()
    {
        return empty($this->_excludedProviders);
    }

    /**
     * @return bool Should we store this option in persistent storage?
     */
    public final function isMeantToBePersisted()
    {
        return $this->_shouldPersist;
    }

    /**
     * @return bool Is this option Pro only?
     */
    public final function isProOnly()
    {
        return $this->_proOnly;
    }

    /**
     * @param array $values An array of acceptable values for this option.
     *
     * @throws InvalidArgumentException If this option is boolean, or a regular expression has already been set.
     *
     * @return void
     */
    public final function setAcceptableValues(array $values)
    {
        $this->_checkNotBoolean();
        $this->_checkRegexNotSet();

        $this->_acceptableValues = $values;
    }

    /**
     * @param array $aliases An array of aliases for this option.
     *
     * @throws InvalidArgumentException
     */
    public final function setAliases(array $aliases)
    {
        $this->_aliases = $aliases;
    }

    /**
     * Mark this option as boolean-only.
     *
     * @throws InvalidArgumentException If an array of acceptable values has already been set, or a regex has already
     *                                  been set.
     *
     * @return void
     */
    public final function setBoolean()
    {
        $this->_checkAcceptableValuesNotSet();
        $this->_checkRegexNotSet();

        $this->_isBoolean = true;
    }

    /**
     * Mark this option as non-settable via shortcode.
     *
     * @return void
     */
    public final function setCannotBeSetViaShortcode()
    {
        $this->_shortcodeSettable = false;
    }

    /**
     * @param mixed $value The default value for this option. May be null.
     *
     * @return void
     */
    public final function setDefaultValue($value)
    {
        $this->_defaultValue = $value;
    }

    /**
     * @param string $description The description for this option.
     *
     * @throws InvalidArgumentException If a non-string description is supplied.
     *
     * @return void
     */
    public final function setDescription($description)
    {
        if (! is_string($description)) {

            throw new InvalidArgumentException('Description must be a string for ' . $this->getName());
        }

        $this->_description = $description;
    }

    /**
     * Mark this option as transient.
     *
     * @return void
     */
    public final function setDoNotPersist()
    {
        $this->_shouldPersist = false;
    }

    /**
     * @param array $excludedProviders The array of providers for which this option is not applicable.
     *
     * @return void
     */
    public final function setExcludedProviders(array $excludedProviders)
    {
        $this->_excludedProviders = $excludedProviders;
    }

    /**
     * @param string $label The short label for this option. 30 chars or less.
     *
     * @throws InvalidArgumentException If you supply a non-string for the label.
     *
     * @return void
     */
    public final function setLabel($label)
    {
        if (! is_string($label)) {

            throw new InvalidArgumentException('Label must be a string for ' . $this->getName());
        }

        $this->_label = $label;
    }

    /**
     * Mark this option as pro-only.
     *
     * @return void
     */
    public final function setProOnly()
    {
        $this->_proOnly = true;
    }

    /**
     * @param string $validValueRegex Regex describing valid values that this option can take on (from a string).
     *
     * @throws InvalidArgumentException If a non-string is supplied as the regex.
     *
     * @return void
     */
    public final function setValidValueRegex($validValueRegex)
    {
        if (! is_string($validValueRegex)) {

            throw new InvalidArgumentException('Regex must be a string for ' . $this->getName());
        }

        $this->_checkAcceptableValuesNotSet();
        $this->_checkNotBoolean();

        $this->_validValueRegex = $validValueRegex;
    }

    private function _checkRegexNotSet()
    {
        if (isset($this->_validValueRegex)) {

            throw new InvalidArgumentException($this->getName() . ' already has a regex set');
        }
    }

    private function _checkAcceptableValuesNotSet()
    {
        if (! empty($this->_acceptableValues)) {

            throw new InvalidArgumentException($this->getName() . ' already has acceptable values set');
        }
    }

    private function _checkNotBoolean()
    {
        if ($this->_isBoolean === true) {

            throw new InvalidArgumentException($this->getName() . ' is set to be a boolean');
        }
    }
}
