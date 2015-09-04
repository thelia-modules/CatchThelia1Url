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

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use CatchThelia1Url\Util\UrlGenerator;
use Thelia\Core\HttpFoundation\Request;

/**
 * @author Gilles Bourgeat <gbourgeat@openstudio.fr>
 */
class KernelRequestListener implements EventSubscriberInterface
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
     * @param GetResponseEvent $event
     */
    public function kernelRequestResolver(GetResponseEvent $event)
    {
        $url = null;

        if (null !== $fond = $event->getRequest()->get('fond', null)) {
            switch ($fond) {
                case 'produit':
                    $url = $this->urlGenerator->resolveProduct();
                    break;
                case 'rubrique':
                    $url = $this->urlGenerator->resolveCategory();
                    break;
                case 'dossier':
                    $url = $this->urlGenerator->resolveFolder();
                    break;
                case 'contenu':
                    $url = $this->urlGenerator->resolveContent();
                    break;
                default:
                    $url = $this->resolveOther($fond);
            }
        }

        if ($url !== null) {
            $event->setResponse(new RedirectResponse($url, 301));
        }
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::REQUEST => ['kernelRequestResolver', 192]
        ];
    }

    /**
     * @param string $fond
     * @return null|string
     */
    protected function resolveOther($fond)
    {
        switch ($fond) {
            case 'connexion':
                return $this->urlGenerator->getUrl('/login');
                break;
            case 'recherche':
                if (null !== $q = $this->request->get('keywords', null)) {
                    return $this->urlGenerator->getUrl('/search', ['q' => $q]);
                } elseif (null !== $q = $this->request->get('motcle', null)) {
                    return $this->urlGenerator->getUrl('/search', ['q' => $q]);
                }
                break;
            case 'panier':
                return $this->urlGenerator->getUrl('/cart');
                break;
            case 'mdpoublie':
                return $this->urlGenerator->getUrl('/password');
                break;
        }

        return null;
    }
}
