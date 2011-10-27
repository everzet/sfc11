<?php

namespace Kinosklad\Bundle\MainBundle;

use Kinosklad\Bundle\MainBundle\Entity\FilmRepository;

class FilmProvider
{
    private $repository;
    private $searchString;

    public function __construct(FilmRepository $repository)
    {
        $this->repository = $repository;
    }

    public function setSearchString($search)
    {
        $this->searchString = $search;
    }

    public function getSearchString()
    {
        return $this->searchString;
    }

    public function fetch()
    {
        if (null === $this->searchString) {
            return $this->repository->findAll();
        }

        $qb = $this->repository->findAllQB();
        $searchString = $this->searchString;

        if (preg_match('/genre=([^ ]+)/', $searchString, $matches)) {
            array_shift($matches);
            foreach ($matches as $i => $genre) {
                $qb->andWhere("g.name = :genre{$i} OR gt.value = :genre{$i}");
                $qb->setParameter('genre'.$i, $genre);
            }
            $searchString = preg_replace('/genre=[^ ]+/', '', $searchString);
        }

        if ('' !== $searchString = trim($searchString)) {
            $qb->andWhere('f.name LIKE :name OR ft.value LIKE :name');
            $qb->setParameter('name', '%'.$searchString.'%');
        }

        return $qb->getQuery()->execute();
    }
}
