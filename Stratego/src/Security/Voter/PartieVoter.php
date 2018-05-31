<?php

namespace App\Security\Voter;

use App\Entity\Partie;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class PartieVoter extends Voter
{
    const Joueur1='VIEW-1';
    const Joueur2='VIEW-2';
    const JoueJ1 ="PLAY-1";
    const JoueJ2 ="PLAY-2";
    const PeutJouer="PEUT_JOUER";
    const InitJ1='INIT-J1';
    const InitJ2='INIT-J2';
    protected function supports($attribute, $subject)
    {
        // replace with your own logic
        // https://symfony.com/doc/current/security/voters.html
        return in_array($attribute, [self::Joueur1,self::Joueur2,self::JoueJ1,self::JoueJ2,self::PeutJouer,self::InitJ1,self::InitJ2])
            && $subject instanceof Partie;
    }

    /**
     * @param string $attribute
     * @param Partie $subject
     * @param TokenInterface $token
     * @return bool
     */
    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        $user = $token->getUser();
        // if the user is anonymous, do not grant access
        if (!$user instanceof UserInterface) {
            return false;
        }

        // ... (check conditions and return true to grant permission) ...
        switch ($attribute) {
            case self::Joueur1:
                return ($subject->getJoueur1()->getId()==$user->getId())?true:false;
                break;
            case self::Joueur2:
                return ($subject->getJoueur2()->getId()==$user->getId())?true:false;
                break;
            case self::JoueJ1 :
                if($subject->getJoueur1()->getId()==$user->getId() && Partie::ENCOUR==$subject->getEtatPartie())
                {
                    if($subject->getTourJoueur()==1)
                    {
                        return true;
                    }
                }
                return false;
                break;
            case self::JoueJ2 :
                if($subject->getJoueur2()->getId()==$user->getId()&& Partie::ENCOUR==$subject->getEtatPartie())
                {
                    if($subject->getTourJoueur()==-1)
                    {
                        return true;
                    }
                }
                break;
            case self::PeutJouer :
                $joueur=$subject->getTourJoueur();
                if($joueur==1)
                {
                    return ($subject->getJoueur1()->getId()==$user->getId())?true:false;
                }else if($joueur==-1){
                    return ($subject->getJoueur2()->getId()==$user->getId())?true:false;
                }
                return false;
                break;
            case self::InitJ1 :
                if($subject->getJoueur1()->getId()==$user->getId()&& Partie::INITIALISATION==$subject->getEtatPartie())
                {
                    return true;
                }
                break;
            case self::InitJ2 :
                if($subject->getJoueur2()->getId()==$user->getId()&& Partie::INITIALISATION==$subject->getEtatPartie())
                {
                    return true;
                }
                break;
        }

        return false;
    }
}
