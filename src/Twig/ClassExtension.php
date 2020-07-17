<?php

namespace Kematjaya\DownloadBundle\Twig;

use Twig\TwigFunction;
use Twig\Extension\AbstractExtension;
/**
 * @author Nur Hidayatullah <kematjaya0@gmail.com>
 */
class ClassExtension extends AbstractExtension
{
    public function getFunctions()
    {
        return array(
            new TwigFunction('get_class', array($this, 'getClass'))
        );
    }
    
    public function getClass($object)
    {
        return (new \ReflectionClass($object))->getName();
    }
}
