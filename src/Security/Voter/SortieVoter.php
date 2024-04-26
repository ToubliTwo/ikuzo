<?php

namespace App\Security\Voter;

use App\Entity\Sorties;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class SortieVoter extends Voter
{
    public const EDIT = 'SORTIES_EDIT';
    public const DELETE = 'SORTIES_DELETE';
    public const VIEW = 'SORTIES_VIEW';
    public const CREATE = 'SORTIES_CREATE';
    public const LIST = 'SORTIES_LIST';
    public const LIST_ALL = 'SORTIES_LIST_ALL';

    protected function supports(string $attribute, mixed $subject): bool
    {
        // replace with your own logic
        // https://symfony.com/doc/current/security/voters.html
        return
            in_array($attribute, [self::CREATE, self::LIST, self::LIST_ALL]) ||
            (
                in_array($attribute, [self::EDIT, self::VIEW])
                && $subject instanceof \App\Entity\Sorties
            );
    }

    /**
     * @param Sorties|null $subject
     */
    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();

        // if the user is anonymous, do not grant access
        if (!$user instanceof User) {
            return false;
        }

        // ... (check conditions and return true to grant permission) ...
        switch ($attribute) {
            case self::EDIT:
            case self::DELETE:
                return $subject->getId() === $user->getId();
                break;

            case self::VIEW:
            case self::CREATE:
            case self::LIST:
                return true;
                break;
        }

        return false;
    }
}
