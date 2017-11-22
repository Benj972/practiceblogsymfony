<?php

namespace SnowTricks\HomeBundle\Repository;

/**
 * TrickRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Tools\Pagination\Paginator;

class TrickRepository extends EntityRepository
{

	public function getTricks($page, $nbPerPage)
  	{
    $query = $this->createQueryBuilder('t')
      ->leftJoin('t.images', 'i')
      ->addSelect('i')
      ->leftJoin('t.messages', 'm')
      ->addSelect('m')
      ->getQuery()
    ;
    $query
      // On définit l'annonce à partir de laquelle commencer la liste
      ->setFirstResult(($page-1) * $nbPerPage)
      // Ainsi que le nombre d'annonce à afficher sur une page
      ->setMaxResults($nbPerPage)
    ;
    // Enfin, on retourne l'objet Paginator correspondant à la requête construite
    // (n'oubliez pas le use correspondant en début de fichier)
    return new Paginator($query, true);
  	}

}
