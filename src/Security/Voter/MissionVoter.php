<?php

namespace App\Security\Voter;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;
use App\Entity\Mission;
use App\Entity\User;

final class MissionVoter extends Voter
{
    public const EDIT = 'MISSION_EDIT';
    public const DELETE = 'MISSION_DELETE';

    protected function supports(string $attribute, mixed $subject): bool
    {
        return in_array($attribute, [self::EDIT, self::DELETE]) && $subject instanceof Mission;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {

        $user = $token->getUser();

        if (!$user instanceof UserInterface) {
            return false; // L'utilisateur doit être connecté
        }

        /** @var Mission $mission */
        $mission = $subject;

        // Un admin peut tout modifier et supprimer
        if (in_array('ROLE_ADMIN', $user->getRoles())) {
            return true;
        }

        // Un opérateur ne peut modifier ou supprimer que SES missions
        if (in_array('ROLE_OPERATOR', $user->getRoles()) && $mission->getOperator() === $user) {
            return true;
        }

        // Sinon, accès refusé
        return false;
    }
}
