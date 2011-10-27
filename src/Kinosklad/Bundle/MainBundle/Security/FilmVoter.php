<?php

namespace Kinosklad\Bundle\MainBundle\Security;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Security\Core\Authorization\Voter\VoterInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

use Kinosklad\Bundle\MainBundle\Entity\Film;
use Kinosklad\Bundle\MainBundle\Entity\User;

class FilmVoter implements VoterInterface
{
    public function supportsAttribute($attribute)
    {
        return true;
    }

    public function supportsClass($class)
    {
        return true;
    }

    public function vote(TokenInterface $token, $object, array $attributes)
    {
        if (!($object instanceof Film && $token->getUser() instanceof User)) {
            return VoterInterface::ACCESS_ABSTAIN;
        }

        switch (reset($attributes)) {
            case 'EDIT':
                if ($token->getUser()->isSuperAdmin() || $object->isAuthor($token->getUser())) {
                    return VoterInterface::ACCESS_GRANTED;
                }
                break;
        }

        return VoterInterface::ACCESS_DENIED;
    }
}
