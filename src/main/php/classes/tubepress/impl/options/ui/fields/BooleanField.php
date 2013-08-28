<?php
/**
 * Copyright 2006 - 2013 TubePress LLC (http://tubepress.com)
 *
 * This file is part of TubePress (http://tubepress.com)
 *
 * This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at http://mozilla.org/MPL/2.0/.
 */

/**
 * Displays a checkbox input.
 */
class tubepress_impl_options_ui_fields_BooleanField extends tubepress_impl_options_ui_fields_AbstractOptionDescriptorBasedField
{
    const FIELD_CLASS_NAME = 'tubepress_impl_options_ui_fields_BooleanField';

    /**
     * Get the path to the template for this field, relative
     * to TubePress's root.
     *
     * @return string The path to the template for this field, relative
     *                to TubePress's root.
     */
    protected final function getTemplatePath()
    {
        return 'src/main/resources/system-templates/options_page/fields/checkbox.tpl.php';
    }
}