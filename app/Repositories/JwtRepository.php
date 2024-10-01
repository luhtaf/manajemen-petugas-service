<?php

namespace App\Repositories;

use Lcobucci\JWT\Configuration;
use Lcobucci\JWT\Signer\Rsa\Sha256;
use Lcobucci\JWT\Signer\Key\InMemory;

class JwtRepository
{
    protected $privateKeyPath;
    protected $passphrase;
    protected $config;

    public function __construct()
    {
        // Define the path to your private PEM key and the passphrase
        $this->privateKeyPath = storage_path('app/keys/private.pem');
        $this->passphrase = 'semart';

        // Set up the configuration with the signer and private key
        $this->config = Configuration::forSymmetricSigner(
            new Sha256(),
            InMemory::file($this->privateKeyPath, $this->passphrase)
        );
    }

    public function generateToken($userId)
    {
        try {
            // Retrieve the token builder from the configuration
            $now = new \DateTimeImmutable();
            $token = $this->config->builder()
                ->issuedBy('https://your-domain.com') // Issuer (iss claim)
                ->permittedFor('https://audience.com') // Audience (aud claim)
                ->identifiedBy(bin2hex(random_bytes(16)), true) // Token ID (jti claim)
                ->issuedAt($now) // Issued at time (iat claim)
                ->canOnlyBeUsedAfter($now->modify('+1 minute')) // Not before (nbf claim)
                ->expiresAt($now->modify('+1 hour')) // Expiration time (exp claim)
                ->withClaim('id', $userId) // Custom claim for user ID
                ->getToken($this->config->signer(), $this->config->signingKey()); // Sign the token

            return $token->toString();
        } catch (\Exception $e) {
            // Return detailed error message in development
            throw new \Exception('An error occurred while generating the token: ' . $e->getMessage());
        }
    }
}
