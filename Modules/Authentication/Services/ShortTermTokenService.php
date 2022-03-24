<?php

namespace Modules\Authentication\Services;

use App\Dictionary;
use DateInterval;
use DateTime;
use Modules\Authentication\Entities\RecoverLogModel;
use Modules\Authentication\Entities\RegisterLog;
use ParagonIE\Paseto\Builder;
use ParagonIE\Paseto\Exception\PasetoException;
use ParagonIE\Paseto\JsonToken;
use ParagonIE\Paseto\Keys\SymmetricKey;
use ParagonIE\Paseto\Parser;
use ParagonIE\Paseto\Protocol\Version4;
use ParagonIE\Paseto\ProtocolCollection;
use ParagonIE\Paseto\Purpose;
use ParagonIE\Paseto\Rules\NotExpired;
use Throwable;

class ShortTermTokenService
{
    private const BASE64_SECRET_KEY = 'AzedVHZi9ajx8Qjoacz93Ljklhfdggfg45KLHLI455POGdf5456cfrVpcHU3oxGlcBWE0bZB6Tdfpu';

    /**
     * @param string|null $action
     * @return string
     * @throws PasetoException
     * @throws Throwable
     */
    public static function generateRandomToken(?string $action): string
    {
        $ttl = 'PT' . env('VERIFICATION_TOKEN_TTL') . 'S';
        $token = (new Builder())->setKey(self::getEncryptionKey())
            ->setVersion(new Version4)
            ->setPurpose(Purpose::local())
            ->setIssuedAt()
            ->setNotBefore()
            ->setClaims([
                'action' => $action
            ])
            ->setExpiration((new DateTime())->add(new DateInterval($ttl)));
        return $token->toString();
    }

    private static function getEncryptionKey(): SymmetricKey
    {
        return SymmetricKey::fromEncodedString(self::BASE64_SECRET_KEY);
    }

    /**
     * @param string $token
     * @return bool
     * @throws Throwable
     */
    public static function errorInToken(string $token): bool
    {
        return self::parseToken($token) == null || self::tokenNotFoundInDB($token);
    }

    /**
     * @param string $token
     * @return bool
     * @throws Throwable
     */
    public static function tokenNotFoundInDB(string $token): bool
    {
        $action = self::parseToken($token)->getClaims()['action'];
        $row = match ($action) {
            Dictionary::RECOVER_PASSWORD_ACTION => RecoverLogModel::find($token),
            Dictionary::ACCOUNT_ACTIVATION_ACTION => RegisterLog::find($token),
        };
        return $row == null;
    }

    /**
     * @param $token
     * @return JsonToken|null
     * @throws Throwable
     */
    public static function parseToken($token): ?JsonToken
    {
        $parser = (new Parser())
            ->setKey(self::getEncryptionKey())
            ->setPurpose(Purpose::local())
            ->setAllowedVersions(ProtocolCollection::v4());
        try {
            $parsed_token = $parser->parse($token);
        } catch (PasetoException) {
            return null;
        }
        return $parsed_token;
    }

    /**
     * @param string $token
     * @return bool
     * @throws Throwable
     */
    public static function tokenExpired(string $token): bool
    {
        $parser = (new Parser())
            ->setKey(self::getEncryptionKey())
            ->setPurpose(Purpose::local())
            ->setAllowedVersions(ProtocolCollection::v4())
            ->addRule(new NotExpired);

        try {
            $parser->parse($token);
        } catch (PasetoException) {
            return true;
        }
        return false;
    }

}
