<?php

namespace App;

use App\Models\User;
use ParagonIE\Paseto\Builder;
use ParagonIE\Paseto\Exception\InvalidVersionException;
use ParagonIE\Paseto\Exception\PasetoException;
use ParagonIE\Paseto\JsonToken;
use ParagonIE\Paseto\Keys\SymmetricKey;
use ParagonIE\Paseto\Parser;
use ParagonIE\Paseto\Protocol\Version4;
use ParagonIE\Paseto\ProtocolCollection;
use ParagonIE\Paseto\Purpose;

class Auth
{
    private const BASE64_SECRET_KEY = 'wHr4Zi9ajx8Qjoacz93Ljklhfdg5KLHLKGdf5456cfrVpcHU3oxGlcBWE0bZB6Tdfpu';

    /**
     * @throws PasetoException
     */
    public static function generateToken($user): string
    {
        $token = (new Builder())->setKey(self::getEncryptionKey())
            ->setVersion(new Version4)
            ->setPurpose(Purpose::local())
            ->setIssuedAt()
            ->setNotBefore()
            ->setNonExpiring(true)
            ->setClaims([
                'id' => $user->id
            ]);
        return $token->toString();
    }

    /**
     * @param string $token
     * @return bool
     * @throws InvalidVersionException
     * @throws PasetoException
     */
    public static function errorInRandomToken(string $token): bool
    {
        return self::parseToken($token) == null;
    }

    /**
     * @throws PasetoException
     */
    public static function errorInToken($token): bool
    {
        return self::parseToken($token) == null || User::find(self::getUserIDFromToken($token)) == null;
    }

    public static function getEncryptionKey(): SymmetricKey
    {
        return SymmetricKey::fromEncodedString(self::BASE64_SECRET_KEY);
    }

    /**
     * @throws InvalidVersionException
     * @throws PasetoException
     */
    public static function getUserIDFromToken($token): string
    {
        return self::parseToken($token)->getClaims()['id'];
    }

    /**
     * @throws PasetoException
     * @throws InvalidVersionException
     */
    public static function parseToken($token): ?JsonToken
    {
        $parser = (new Parser())
            ->setKey(self::getEncryptionKey())
            ->setPurpose(Purpose::local())
            ->setNonExpiring(true)
            ->setAllowedVersions(ProtocolCollection::v4());
        try {
            $parsed_token = $parser->parse($token);
        } catch (PasetoException) {
            return null;
        }
        return $parsed_token;
    }
}
