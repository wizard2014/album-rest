<?php

namespace Application\Mapper;

use Doctrine\ORM\EntityManagerInterface;

class Album implements AlbumInterface
{
    const ALBUM_ENTITY = 'Application\Entity\Album';

    /**
     * @var EntityManagerInterface
     */
    protected $em;

    /**
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @return array
     */
    public function findAll()
    {
        $er = $this->em->getRepository(self::ALBUM_ENTITY);

        return $er->findAll();
    }

    /**
     * @param $id
     *
     * @return object
     */
    public function findById($id)
    {
        $er = $this->em->getRepository(self::ALBUM_ENTITY);

        return $er->findOneBy(['id' => $id]);
    }

    /**
     * @param $entity
     *
     * @return mixed
     */
    public function persist($entity)
    {
        $this->em->persist($entity);
        $this->em->flush();

        return $entity;
    }

    /**
     * @param $entity
     */
    public function remove($entity)
    {
        $this->em->remove($entity);
        $this->em->flush();
    }
}