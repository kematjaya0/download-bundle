<?php

namespace Kematjaya\DownloadBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;
/**
 * @author Nur Hidayatullah <kematjaya0@gmail.com>
 */
class DownloadBundle extends Bundle 
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);
    }
}
