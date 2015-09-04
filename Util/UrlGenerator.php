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

use Symfony\Component\EventDispatcher\EventDispatcher;
use CatchThelia1Url\Model\Base\T1T2ProductQuery;
use CatchThelia1Url\Model\T1T2CategoryQuery;
use CatchThelia1Url\Model\T1T2ContentQuery;
use CatchThelia1Url\Model\T1T2FolderQuery;
use Symfony\Component\Routing\RequestContext;
use Thelia\Core\Event\Document\DocumentEvent;
use Thelia\Core\Event\Image\ImageEvent;
use Thelia\Core\Event\TheliaEvents;
use Thelia\Core\HttpFoundation\Request;
use Thelia\Model\ConfigQuery;
use Thelia\Action\Image;
use Thelia\Model\ProductQuery;
use Thelia\Tools\URL;

/**
 * @author Gilles Bourgeat <gbourgeat@openstudio.fr>
 */
class UrlGenerator
{
    /** @var Request */
    protected $request;

    /** @var EventDispatcher */
    protected $dispatcher;

    /** @var URL */
    protected $url;

    /**
     * @param Request $request
     * @param EventDispatcher $dispatcher
     */
    public function __construct(Request $request, EventDispatcher $dispatcher)
    {
        $this->dispatcher = $dispatcher;
        $this->request = $request;

        $requestContext = new RequestContext();
        $requestContext->fromRequest($this->request);

        $this->url = new UrlOverride();
        $this->url->setRequestContext($requestContext);
    }

    /**
     * @param string $type
     * @param int $idT1
     * @return string|null
     */
    public function generateUrlByIdT1($type, $idT1)
    {
        try {
            $class = '\CatchThelia1Url\Model\T1T2' . ucfirst($type) . 'Query';

            /** @var T1T2FolderQuery|T1T2CategoryQuery|T1T2ContentQuery|T1T2ProductQuery $class */
            if (!empty($idT1) && null !== $find = $class::create()->filterByIdt1($idT1)->findOne()) {
                return $this->url->retrieve($type, $find->getIdt2(), $this->request->getPreferredLanguage())->toString();
            }
        } catch (\Exception $e) {
            return null;
        }
    }

    public function getUrl($path, array $parameters = null)
    {
        return $this->url->absoluteUrl($path, $parameters);
    }

    /**
     * @param string $file
     * @param string $type
     * @return null|string
     */
    public function generateDocument($file, $type)
    {
        if (null === $baseSourceFilePath = ConfigQuery::read('documents_library_path')) {
            $baseSourceFilePath = THELIA_LOCAL_DIR . 'media' . DS . 'documents';
        } else {
            $baseSourceFilePath = THELIA_ROOT . $baseSourceFilePath;
        }

        $event = (new DocumentEvent($this->request))
            ->setSourceFilepath($baseSourceFilePath . DS . $type .DS . $file)
            ->setCacheSubdirectory($type);

        try {
            // Dispatch document processing event
            $this->dispatcher->dispatch(TheliaEvents::DOCUMENT_PROCESS, $event);

            return $event->getDocumentUrl();
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * @param string $file
     * @param string $type
     * @param int $width
     * @param int $height
     * @return string|null
     */
    public function generateImage($file, $type, $width, $height)
    {
        if (null === $baseSourceFilePath = ConfigQuery::read('images_library_path')) {
            $baseSourceFilePath = THELIA_LOCAL_DIR . 'media' . DS . 'images';
        } else {
            $baseSourceFilePath = THELIA_ROOT . $baseSourceFilePath;
        }

        // Create image processing event
        $eventImage = (new ImageEvent($this->request))
            ->setResizeMode(Image::KEEP_IMAGE_RATIO)
            ->setWidth($width)
            ->setHeight($height)
            ->setSourceFilepath($baseSourceFilePath . DS . $type . DS . $file)
            ->setCacheSubdirectory($type);

        try {
            // Dispatch image processing event
            $this->dispatcher->dispatch(TheliaEvents::IMAGE_PROCESS, $eventImage);

            return $eventImage->getOriginalFileUrl();
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * @return null|string
     */
    public function resolveProduct()
    {
        if (null !== $ref = $this->request->get('ref')) {
            if (null !== $product = ProductQuery::create()
                ->filterByRef($ref)
                ->findOne()) {
                return $this->url->retrieve('product', $product->getId(), $this->request->getPreferredLanguage())->toString();
            }
        }

        return $this->generateUrlByIdT1('product', intval($this->request->get('id_produit', null)));
    }

    /**
     * @return null|string
     */
    public function resolveCategory()
    {
        return $this->generateUrlByIdT1('category', intval($this->request->get('id_rubrique', null)));
    }

    /**
     * @return null|string
     */
    public function resolveFolder()
    {
        return $this->generateUrlByIdT1('folder', intval($this->request->get('id_dossier', null)));
    }

    /**
     * @return null|string
     */
    public function resolveContent()
    {
        return $this->generateUrlByIdT1('content', intval($this->request->get('id_contenu', null)));
    }
}
