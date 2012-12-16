<?php
/**
 * Copyright 2006 - 2012 Eric D. Hough (http://ehough.com)
 *
 * This file is part of TubePress (http://tubepress.org)
 *
 * This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at http://mozilla.org/MPL/2.0/.
 */
?>
<select id="multiselect-<?php echo ${tubepress_impl_options_ui_fields_AbstractMultiSelectField::TEMPLATE_VAR_NAME}; ?>" name="<?php echo ${tubepress_impl_options_ui_fields_AbstractMultiSelectField::TEMPLATE_VAR_NAME}; ?>[]" multiple="multiple">

	<?php foreach (${tubepress_impl_options_ui_fields_FilterMultiSelectField::TEMPLATE_VAR_PROVIDERS} as $providerName => $providerLabel): ?>

	<option value="<?php echo $providerName; ?>" <?php if (in_array($providerName, ${tubepress_impl_options_ui_fields_FilterMultiSelectField::TEMPLATE_VAR_CURRENTVALUES})): ?>selected="selected"<?php endif; ?>><?php echo $providerLabel; ?></option>

	<?php endforeach; ?>
</select>