<?php

namespace App;

use JetBrains\PhpStorm\ArrayShape;

class Responses
{
    // User data
    public const WRONG_PASSWORD = 'Mot de passe incorrect. Veuillez réessayer';
    public const INVALID_EMAIL = 'L\'adress e-mail que vous avez spécifié n\'est pas valide. Veuillez choisir une autre';
    public const EMAIL_REQUIRED = 'L\'adress e-mail est requise';
    public const EMAIL_NOT_FOUND = 'Cette adresse e-mail n\'est associée à aucun compte';
    public const EMAIL_TAKEN = 'Cette adresse e-mail est déjà utilisée';
    public const INVALID_LAST_NAME = 'Le nom que vous avez spécifié n\'est pas valide. Veuillez choisir un autre';
    public const INVALID_FIRST_NAME = 'Le prénom que vous avez spécifié n\'est pas valide. Veuillez choisir un autre';
    public const SESSION_EXPIRED = 'Votre session a expiré, veuillez actualiser et réessayer';
    public const PASSCODE_OVERFLOW = 'vous avez atteint le nombre maximum de tentatives. Un nouveau mot de passage a été envoyé à votre courrier';
    public const WRONG_PASSCODE = 'Mot de passage incorrect. Veuillez vérifier le mail le plus récent dans votre courrier';
    public const CANNOT_CANCEL = 'Cette application ne peut être annulée !';
    public const CANNOT_DELETE = 'Cette application ne peut être supprimée !';
    public const SIZE_OVERFLOW =  'La taille de votre fichier est trop grande';
    public const INVALID_FACEBOOK_LINK = 'Le lien Facebook n\'est pas valide. Veuillez choisir un autre';
    public const INVALID_INSTAGRAM_LINK = 'Le lien Instagram n\'est pas valide. Veuillez choisir un autre';
    public const FAILED_BILLING = 'Failed to find the proper plan price';
    public const GENERAL_ERROR_FR = 'Une erreur inattendue s\'est produite, veuillez réessayer plus tard.';
    public const USING_FACEBOOK_ACCOUNT = 'Vous avez déjà créé un compte en utilisant Facebook. Veuillez vous connecter à votre compte existant en utilisant Facebook.';
    public const USING_GOOGLE_ACCOUNT = 'Vous avez déjà créé un compte  en utilisant Google. Veuillez vous connecter à votre compte  existant en utilisant Google.';
    public const GOOGLE_LOGIN_NO_ACCOUNT = 'Vous n\'avez pas de compte Google enregistré. Veuillez créer un compte en utilisant Google';
    public const FACEBOOK_LOGIN_NO_ACCOUNT = 'Vous n\'avez pas de compte Facebook enregistré. Veuillez créer un compte  en utilisant Facebook';
    public const LOCAL_ACCOUNT = 'Vous avez déjà créé un compte. Veuillez vous connecter à votre compte en introduisant votre email et mot de passe.';
    public const ALREADY_APPLIED = 'Vous avez déjà un plan en cours de traitement. Vous pouvez annuler le plan et recommencer depuis votre tableau de bord';
    public const FILE_NOT_VALID = 'Veuillez choisir une image ou un fichier PDF s\'il vous plait';
    public const UNHANDLED_IMAGE_EXTENSION = 'Le type de l\'image n\'est pas autorisé';
    public const SAME_PASSWORD = 'Vous avez déjà utilisé ce mot de passe. Veuillez utiliser un autre';
    public const ALREADY_WRITE_DIARY = 'Vous avez déjà écrit votre journal aujourd\'hui !';
    public const INVALID_IMAGE = 'Veuillez sélectionner une image valide';
    public const PROGRESS_DELETED = 'Vous avez déjà supprimé cet élément';
    public const ALREADY_WRITE_BODY_PROGRESS = 'Vous avez déjà soumis votre progrès il y\'a moins de 15 jours';
    public const CLASSROOM_FULL = 'Nous avons atteint la capacité maximale d\'abonnés. Veuillez réessayer plus tard ou conctactez-nous si vous pensez que c\'est une erreur';

    #[ArrayShape(['error' => "array"])] public static function emptyDebugResponseError($message): array
    {
        return [
            'error' => [
                'debugging_errors' => [],
                'display_error' => $message
            ]
        ];
    }

    #[ArrayShape(['error' => "array"])] public static function DebugResponseError($debug, $message): array
    {
        return [
            'error' => [
                'debugging_errors' => $debug,
                'display_error' => $message
            ]
        ];
    }


}
