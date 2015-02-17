<?php

namespace Application\Mapper;

interface AlbumInterface
{
    /**
     * @return mixed
     */
    public function findAll();

    /**
     * @param $id
     *
     * @return mixed
     */
    public function findById($id);
}