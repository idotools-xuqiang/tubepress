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
 * Displays the thumbnails tab.
 */
class tubepress_impl_options_ui_tabs_ThumbsTab extends tubepress_impl_options_ui_tabs_AbstractPluggableOptionsPageTab
{
    const TAB_NAME = 'thumbs';

    /**
     * Get the untranslated title of this tab.
     *
     * @return string The untranslated title of this tab.
     */
    protected final function getRawTitle()
    {
        return 'Thumbnails';  //>(translatable)<
    }

    public final function getName()
    {
        return self::TAB_NAME;
    }
}