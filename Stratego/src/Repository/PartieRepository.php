<?php

namespace App\Repository;

use App\Entity\Partie;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
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
    public function findPartieJoueur(UserInterface $user)
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
            ->andWhere('p.Joueur1 !=joueur')
            ->setParameter('val', Partie::MANQUE_JOUEUR)
            ->setParameter('joueur',$user)
            ->orderBy('p.dateDebut', 'ASC')
            ->getQuery()
            ->getOneOrNullResult()
            ;
    }



    /*
    public function findOneBySomeField($value): ?Partie
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
