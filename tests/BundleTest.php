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
        
        $this->assertTrue($container->has('kmj.encrypt_decrypt'));
        //dump($container->has('Doctrine\ORM\EntityManager'));exit;
    }
    
    public static function getKernelClass() 
    {
        return AppKernel::class;
    }
}
