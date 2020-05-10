<?php

namespace Kematjaya\DownloadBundle\Encryption;

use Defuse\Crypto\Key;
use Defuse\Crypto\Crypto;
use Defuse\Crypto\Exception\WrongKeyOrModifiedCiphertextException;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
/**
 * @author Nur Hidayatullah <kematjaya0@gmail.com>
 */
class EncryptDecrypt 
{
    private $keyString;
    
    public function __construct(ParameterBagInterface $parameterBag) 
    {
        $this->keyString = $parameterBag->get('encrypt_key');
    }

    public function encryp($text)
    {
        $key = Key::loadFromAsciiSafeString($this->keyString);
        return Crypto::encrypt((string) $text, $key);;
    }
    
    public function decryp($ciphertext)
    {
        $plaintext = null;
        try{
            $key = Key::loadFromAsciiSafeString($this->keyString);
            $plaintext = Crypto::decrypt($ciphertext, $key);
        } catch (WrongKeyOrModifiedCiphertextException $ex) 
        {
            throw $ex;
        }
        
        return $plaintext;
    }
}
