<?php

namespace Kematjaya\DownloadBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * @author Nur Hidayatullah <kematjaya0@gmail.com>
 */
class DownloadExtension extends Extension
{
    //put your code here
    public function load(array $configs, ContainerBuilder $container) 
    {
        $loader = new XmlFileLoader($container,
            new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.xml');
    }

}
