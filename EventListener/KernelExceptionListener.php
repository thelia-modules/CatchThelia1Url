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

namespace CatchThelia1Url\EventListener;

use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\KernelEvents;
use Thelia\Core\HttpFoundation\Request;
use Thelia\Model\CategoryDocumentQuery;
use Thelia\Model\ContentDocumentQuery;
use Thelia\Model\FolderDocumentQuery;
use Thelia\Model\ProductDocumentQuery;
use Thelia\Model\ProductImageQuery;
use CatchThelia1Url\CatchThelia1Url;
use CatchThelia1Url\Util\UrlGenerator;

/**
 * @author Gilles Bourgeat <gbourgeat@openstudio.fr>
 */
class KernelExceptionListener implements EventSubscriberInterface
{
    /** @var null|ContainerInterface */
    protected $container;

    /** @var Request */
    protected $request;

    /** @var UrlGenerator */
    protected $urlGenerator;

    /**
     * @param Request $request
     * @param ContainerInterface $container
     * @param UrlGenerator $urlGenerator
     */
    public function __construct(Request $request, ContainerInterface $container, UrlGenerator $urlGenerator)
    {
        $this->container = $container;
        $this->request = $request;
        $this->urlGenerator = $urlGenerator;
    }

    /**
     * @param GetResponseForExceptionEvent $event
     */
    public function kernelExceptionResolver(GetResponseForExceptionEvent $event)
    {
        $exception = $event->getException();

        if ($exception instanceof NotFoundHttpException) {
            if (null === $view = $event->getRequest()->get('_view')) {
                $view = $event->getRequest()->getPathInfo();
            }

            $url = null;

            switch ($view) {
                case 'produit.php':
                    $url = $this->urlGenerator->resolveProduct();
                    break;
                case 'rubrique.php':
                    $url = $this->urlGenerator->resolveCategory();
                    break;
                case 'dossier.php':
                    $url = $this->urlGenerator->resolveFolder();
                    break;
                case 'contenu.php':
                    $url = $this->urlGenerator->resolveContent();
                    break;
            }

            if ($url === null) {
                $url = $this->resolveImage($view);
            }
            if ($url === null) {
                $url = $this->resolveDocument($view);
            }
            if ($url === null) {
                $url = $this->resolveOther($view);
            }

            if ($url !== null) {
                $event->setResponse(new RedirectResponse($url, 301));
            }
        }
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::EXCEPTION => ['kernelExceptionResolver', 192]
        ];
    }

    /**
     * @param string $view
     * @return null|string
     */
    protected function resolveOther($view)
    {
        switch ($view) {
            case 'contact.php':
                return $this->urlGenerator->getUrl('/contact');
                break;
            case 'panier.php':
                return $this->urlGenerator->getUrl('/cart');
                break;
            case 'recherche.php':
                if (null !== $q = $this->request->get('keywords', null)) {
                    return $this->urlGenerator->getUrl('/search', ['q' => $q]);
                } elseif (null !== $q = $this->request->get('motcle', null)) {
                    return $this->urlGenerator->getUrl('/search', ['q' => $q]);
                }
                break;
            case 'mdpoublie.php':
                return $this->urlGenerator->getUrl('/password');
                break;
        }

        return null;
    }

    /**
     * @param string $view
     * @return null|string
     */
    protected function resolveDocument($view)
    {
        if ($match = preg_match(
            "#\\/client\\/document\\/(?<file>[^?\\#]+)#i",
            $view,
            $matchData
        )) {
            if (null !== $document = ProductDocumentQuery::create()->findOneByFile($matchData['file'])) {
                return $this->urlGenerator->generateDocument($document->getFile(), 'product');
            }

            if (null !== $document = CategoryDocumentQuery::create()->findOneByFile($matchData['file'])) {
                return $this->urlGenerator->generateDocument($document->getFile(), 'category');
            }

            if (null !== $document = ContentDocumentQuery::create()->findOneByFile($matchData['file'])) {
                return $this->urlGenerator->generateDocument($document->getFile(), 'content');
            }

            if (null !== $document = FolderDocumentQuery::create()->findOneByFile($matchData['file'])) {
                return $this->urlGenerator->generateDocument($document->getFile(), 'folder');
            }
        }

        return null;
    }

    /**
     * @param string $view
     * @return null|string
     */
    protected function resolveImage($view)
    {
        $types = [
            'produit'   => 'product',
            'rubrique'  => 'category',
            'dossier'   => 'folder',
            'contenu'   => 'content'
        ];

        // test if the view is a image
        if ($match = preg_match(
            "#\\/client\\/gfx\\/photos\\/(?<type>contenu|dossier|produit|rubrique)\\/(?<file>[^?\\#]+)#i",
            $view,
            $matchData
        )) {
            $width = CatchThelia1Url::getConfig($types[$matchData['type']] . 'ImageWidth');
            $height = CatchThelia1Url::getConfig($types[$matchData['type']] . 'ImageHeight');
        } else {
            // test if the view is a resized image
            $match = preg_match(
                "#\\/client\\/cache\\/(?<type>contenu|dossier|produit|rubrique)\\/(?<width>[0-9]*)_(?<height>[0-9]*)(?:_[^_]*){5}_(?<file>[^?\\#]+)#i",
                $view,
                $matchData
            );

            $width = (!empty($matchData['width'])) ? $matchData['width'] : null;
            $height = (!empty($matchData['height'])) ? $matchData['height'] : null;
        }

        if ($match) {
            $class = '\Thelia\Model\\' . ucfirst($types[$matchData['type']]) . 'ImageQuery';

            /** @var ProductImageQuery$class|ContentImageQuery|ContentImageQuery|FolderImageQuery$class $class */
            if (null !== $image = $class::create()->findOneByFile($matchData['file'])) {
                return $this->urlGenerator->generateImage(
                    $image->getFile(),
                    $types[$matchData['type']],
                    $width,
                    $height
                );
            }
        }

        return null;
    }
}
