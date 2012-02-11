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

class_exists('org_tubepress_impl_classloader_ClassLoader') || require dirname(__FILE__) . '/../../../classloader/ClassLoader.class.php';
org_tubepress_impl_classloader_ClassLoader::loadClasses(array(
    'org_tubepress_api_const_options_names_Display',
    'org_tubepress_api_const_options_values_OrderByValue',
    'org_tubepress_api_exec_ExecutionContext',
    'org_tubepress_api_provider_ProviderResult',
    'org_tubepress_impl_ioc_IocContainer',
));


/**
 * Shuffles videos on request.
 */
class org_tubepress_impl_plugin_filters_providerresult_PerPageSorter
{
    private static $_logPrefix = 'Per Page Sorter';

	public function alter_providerResult(org_tubepress_api_provider_ProviderResult $providerResult)
	{
		$ioc       = org_tubepress_impl_ioc_IocContainer::getInstance();
		$context   = $ioc->get(org_tubepress_api_exec_ExecutionContext::_);
		$sortOrder = $context->get(org_tubepress_api_const_options_names_Feed::ORDER_BY);

		/** Grab a handle to the videos. */
		$videos = $providerResult->getVideoArray();

		/** Determine the sort method name. */
		$sortCallback = '_' . $sortOrder . '_compare';

		if ($sortOrder === org_tubepress_api_const_options_values_OrderByValue::RANDOM) {

		    org_tubepress_impl_log_Log::log(self::$_logPrefix, 'Shuffling videos');

		    shuffle($videos);

		} else {

		    /** If we have a sorter, use it. */
		    if (method_exists($this, $sortCallback)) {

		        org_tubepress_impl_log_Log::log(self::$_logPrefix, 'Now sorting videos on page (%s)', $sortOrder);

		        uasort($videos, array($this, $sortCallback));

		    } else {

		        org_tubepress_impl_log_Log::log(self::$_logPrefix, 'No sort available for this page (%s)', $sortOrder);

		        uasort($videos, array($this, '_newest_compare'));
		    }
		}

		$videos = array_values($videos);

		/** Modify the feed result. */
		$providerResult->setVideoArray($videos);

		return $providerResult;
	}

	private function _commentCount_compare($one, $two)
	{
	    return $this->_compareGreatestToLeast($one->getCommentCount(), $two->getCommentCount());
	}

	private function _duration_compare($one, $two)
	{
	    return $this->_compareGreatestToLeast($one->durationInSeconds, $two->durationInSeconds);
	}

	private function _newest_compare($one, $two)
	{
	    return $this->_compareGreatestToLeast($one->timePublishedInUnixTime, $two->timePublishedInUnixTime);
	}

	private function _oldest_compare($one, $two)
	{
	    return $this->_compareLeastToGreatest($one->timePublishedInUnixTime, $two->timePublishedInUnixTime);
	}

	private function _rating_compare($one, $two)
	{
	    return $this->_compareGreatestToLeast($one->getRatingAverage(), $two->getRatingAverage());
	}

	private function _title_compare($one, $two)
	{
	    return strcmp($one->getTitle(), $two->getTitle());
	}

	private function _viewCount_compare($one, $two)
	{
	    return $this->_compareGreatestToLeast($one->getCommentCount(), $two->getCommentCount());
	}

	private function _compareLeastToGreatest($one, $two)
	{
	    if ($one == $two) {

	        return 0;
	    }

	    return $one < $two ? -1 : 1;
	}

	private function _compareGreatestToLeast($one, $two)
	{
	    if ($one == $two) {

	        return 0;
	    }

	    return $one > $two ? -1 : 1;
	}
}