<?php 
/**
 * Copyright 2006 - 2010 Eric D. Hough (http://ehough.com)
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
?>
<object style="width: <?php echo ${org_tubepress_template_Template::EMBEDDED_WIDTH}; ?>px; height:<?php echo ${org_tubepress_template_Template::EMBEDDED_HEIGHT}; ?>px"
    data="<?php echo ${org_tubepress_template_Template::EMBEDDED_DATA_URL}; ?>"
    type="application/x-shockwave-flash">
  <param name="allowfullscreen" value="<?php echo ${org_tubepress_template_Template::EMBEDDED_FULLSCREEN}; ?>" />
  <param name="allowscriptaccess" value="always" />
  <param name="movie" value="<?php echo ${org_tubepress_template_Template::EMBEDDED_DATA_URL}; ?>" />
</object>