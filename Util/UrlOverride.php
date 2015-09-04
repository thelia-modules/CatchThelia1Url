<?php
/*************************************************************************************/
/*      This file is part of the module CatchThelia1Url.                             */
/*                                                                                   */
/*      Copyright (c) OpenStudio                                                     */
/*      email : dev@thelia.net                                                       */
/*      web : http://www.thelia.net                                                  */
/*                                                                                   */
/*      For the full copyright and license information, please view the LICENSE.txt  */
/*      file that was distributed with this source code.                             */
/*************************************************************************************/

namespace CatchThelia1Url\Util;

use Symfony\Component\Routing\RequestContext;
use Thelia\Tools\URL;

/**
 * Because setRequestContext does not exist in version 2.1
 * @author Gilles Bourgeat <gbourgeat@openstudio.fr>
 */
class UrlOverride extends URL
{
    /**
     * @param RequestContext $requestContext
     */
    public function setRequestContext(RequestContext $requestContext)
    {
        $this->requestContext = $requestContext;
    }
}
