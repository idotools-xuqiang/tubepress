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

interface tubepress_api_ioc_ContainerInterface
{
    /**
     * Gets a service.
     *
     * @param string $id The service identifier
     *
     * @return object The associated service, or null if not defined.
     *
     * @api
     * @since 3.1.0
     */
    function get($id);

    /**
     * Gets a parameter.
     *
     * @param string $name The parameter name
     *
     * @return mixed  The parameter value
     *
     * @throws InvalidArgumentException if the parameter is not defined
     *
     * @api
     * @since 3.1.0
     */
    function getParameter($name);

    /**
     * Returns true if the given service is defined.
     *
     * @param string $id The service identifier
     *
     * @return Boolean true if the service is defined, false otherwise
     *
     * @api
     * @since 3.1.0
     */
    function has($id);

    /**
     * Checks if a parameter exists.
     *
     * @param string $name The parameter name
     *
     * @return Boolean The presence of parameter in container
     *
     * @api
     * @since 3.1.0
     */
    function hasParameter($name);

    /**
     * Sets a service.
     *
     * @param string $id      The service identifier
     * @param object $service The service instance
     *
     * @return void
     *
     * @api
     * @since 3.1.0
     */
    function set($id, $service);

    /**
     * Sets a parameter.
     *
     * @param string $name  The parameter name
     * @param mixed  $value The parameter value
     *
     * @return void
     *
     * @api
     * @since 3.1.0
     */
    function setParameter($name, $value);
}