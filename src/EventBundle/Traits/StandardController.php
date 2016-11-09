<?php

namespace EventBundle\Traits;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request,
    Symfony\Component\HttpFoundation\Response,
    Symfony\Component\HttpFoundation\RedirectResponse;

/**
 * Standardowe / użyteczne akcje wstrzykiwane do kontrolera akcji.
 *
 * @author Andrzej Wojdas <praca.aw@gmail.com>
 */
trait StandardController {
    
    protected $em;
    
    public function setContainer(ContainerInterface $container = null)
    {
        parent::setContainer($container);
        
        $this->em = $this->container->get('doctrine')->getManager();
    }
    
    public function getRepository($persistentObjectName) {
        static $factories = array();
        if ( !in_array($persistentObjectName, $factories) ) {
            $factories[$persistentObjectName] = $this->em->getRepository($persistentObjectName);
        }
        
        return $factories[$persistentObjectName];
    }
    
    public function trans($value, $args = array()) {
        static $translator = null;
        
        $td_value = explode(':', $value);
        $translationDomain = null;
        if ( count($td_value) === 2 ) {
            $translationDomain = $td_value[0];
            $value = $td_value[1];
        }
        
        if ( $translator === null ) {
            $translator = $this->get('translator');
        }
        
        return $translator->trans($value, $args, $translationDomain);
    }
    
    public function flashSuccess($message, $args = array()) {
        $this->addFlash('success', $this->trans($message, $args));
        
        return $this;
    }
    
    public function flashError($message, $args = array()) {
        $this->addFlash('error', $this->trans($message, $args));
        
        return $this;        
    }
    
    public function flashNotice($message, $args = array()) {
        $this->addFlash('notice', $this->trans($message, $args));
        
        return $this;
    }
}