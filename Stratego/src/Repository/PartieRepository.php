<?php

namespace App\Repository;

use App\Entity\Partie;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @method Partie|null find($id, $lockMode = null, $lockVersion = null)
 * @method Partie|null findOneBy(array $criteria, array $orderBy = null)
 * @method Partie[]    findAll()
 * @method Partie[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PartieRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Partie::class);
    }

    /**
     * @param UserInterface $user
     * @return Partie[]
     */
    public function findPartieJoueurDefie(UserInterface $user)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.etatPartie= :val')
            ->andWhere('p.Joueur2 =:joueur')
            ->setParameter('val', Partie::ATTENTE)
            ->setParameter('joueur',$user)
            ->orderBy('p.dateDebut', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @param UserInterface $user
     * @return Partie|null
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function findPartieEnAttenteJoueur(UserInterface $user)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.etatPartie= :val')
            ->setParameter('val', Partie::MANQUE_JOUEUR)
            ->getQuery()
            ->getOneOrNullResult();
    }
    /**
     * @param UserInterface $user
     * @return Partie|null
     */
    public function findPartieOuJoueurEstPresent(UserInterface $user,$etat)
    {
        $qb = $this->createQueryBuilder('p');
        $or=$qb->expr()->orX();
        $or->add($qb->expr()->eq('p.Joueur1',':joueur'));
        $or->add($qb->expr()->eq('p.Joueur2',':joueur'));

        return $this->createQueryBuilder('p')
            ->andWhere($or)
            ->andWhere('p.etatPartie= :val')
            ->setParameter('val', $etat)
            ->setParameter('joueur',$user)
            ->orderBy('p.dateDebut', 'DESC')
            ->getQuery()
            ->getResult()
            ;
    }
}
