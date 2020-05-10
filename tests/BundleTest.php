<?php

namespace Kematjaya\DownloadBundle\Tests;

use Kematjaya\DownloadBundle\Tests\AppKernel;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * @author Nur Hidayatullah <kematjaya0@gmail.com>
 */
class BundleTest extends WebTestCase 
{
    public function testInitBundle()
    {
        $client = static::createClient();
        $container = $client->getContainer();
        
        dump($container);exit;
        //dump($container->has('kmj.serial_number_console'));exit;
    }
    
    public static function getKernelClass() 
    {
        return AppKernel::class;
    }
}
