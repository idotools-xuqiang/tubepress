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

function_exists('tubepress_load_classes') || require dirname(__FILE__) . '/../../../../tubepress_classloader.php';
tubepress_load_classes(array('org_tubepress_api_feed_FeedFetcher',
    'org_tubepress_api_cache_Cache',
    'org_tubepress_impl_log_Log',
    'org_tubepress_impl_ioc_IocContainer'));

/**
 * Base functionality for feed retrieval services.
 */
class org_tubepress_impl_feed_FastHttpClientFeedFetcher implements org_tubepress_api_feed_FeedFetcher
{
    const LOG_PREFIX = 'Fast HTTP Client Feed Fetcher';

    /**
     * Fetches the feed from a remote provider
     *
     * @param string  $url      The URL to fetch.
     * @param boolean $useCache Whether or not to use the network cache.
     *
     * @return unknown The raw feed from the provider
     */
    public function fetch($url, $useCache)
    {
        global $tubepress_base_url;

        $ioc       = org_tubepress_impl_ioc_IocContainer::getInstance();
        $cache     = $ioc->get('org_tubepress_api_cache_Cache');

        $result = '';
        if ($useCache) {

            org_tubepress_impl_log_Log::log(self::LOG_PREFIX, 'First asking cache for <tt>%s</tt>', $url);

            if ($cache->has($url)) {
                org_tubepress_impl_log_Log::log(self::LOG_PREFIX, 'Cache has <tt>%s</tt>. Sweet.', $url);
                $result = $cache->get($url);
            } else {
                org_tubepress_impl_log_Log::log(self::LOG_PREFIX, 'Cache does not have <tt>%s</tt>. We\'ll have to get it from the network.', $url);
                $result = $this->_getFromNetwork($url, $ioc);
                $cache->save($url, $result);
            }
        } else {
            org_tubepress_impl_log_Log::log(self::LOG_PREFIX, 'Skip cache check for <tt>%s</tt>', $url);
            $result = $this->_getFromNetwork($url, $ioc);
        }
        
        org_tubepress_impl_log_Log::log(self::LOG_PREFIX, 'Raw result for <tt>%s</tt> is in the HTML source for this page. <span style="display:none">%s</span>', $url, htmlspecialchars($result));
        
        return $result;
    }

    private function _getFromNetwork($url, $ioc)
    {
        $client = $ioc->get('org_tubepress_api_http_HttpClient');
        return $client->get($url);
    }

}
