<?php

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractRestfulController;
use Zend\View\Model\JsonModel;

use Application\Entity\Album;

/**
 * Class IndexController
 *
 * @package Application\Controller
 *
 *              -Create-            -Read-              -Update-                    -Delete-
 *              POST	            GET	                PUT	                        DELETE
 * /app	        create record	    Album list  	    Update Album data   	    delete all
 * /app/42	    error	            Album Data  	    Update Album    	        delete album
 */

class IndexController extends AbstractRestfulController
{
    const ALBUM_ENTITY = 'Application\Entity\Album';

    protected $mapper;

    /**
     * @return JsonModel
     */
    public function getList()
    {
        $results = $this->mapper->findAll();

        $albums = $this->getArrayResult($results);

        return new JsonModel([
            'albums' => $albums
        ]);
    }

    /**
     * @param mixed $id
     *
     * @return JsonModel
     */
    public function get($id)
    {
        $album = $this->mapper->findById($id);

        if (!is_null($album)) {
            $album = $album->getArrayCopy();
        }

        return new JsonModel([
            'album' => $album
        ]);
    }

    /**
     * @param mixed $data
     *
     * @return JsonModel
     */
    public function create($data)
    {
        $newAlbum = new Album();

        if (!isset($data['artist'], $data['title'])) {
            return new JsonModel([
                'error' => 'missing params'
            ]);
        }

        $newAlbum->setArtist($data['artist']);
        $newAlbum->setTitle($data['title']);

        $this->mapper->persist($newAlbum);

        return new JsonModel([
            'id' => $newAlbum->getId()
        ]);
    }

    /**
     * @param mixed $id
     * @param mixed $data (data={"artist":"new artist", "title":"new title"})
     *
     * @return JsonModel
     */
    public function update($id, $data)
    {
        $album = $this->mapper->findById($id);

        if (is_null($album)) {
            return new JsonModel([
                'error' => 'no such album'
            ]);
        }

        /**
         * @todo get data from PUT request
         */
        $data = (array)json_decode($data['data']);

        $album->setArtist($data['artist']);
        $album->setTitle($data['title']);

        $this->mapper->persist($album);

        return new JsonModel([
            'id' => $album->getId()
        ]);
    }

    /**
     * @param mixed $id
     *
     * @return JsonModel
     */
    public function delete($id)
    {
        $album = $this->mapper->findById($id);

        $this->mapper->remove($album);

        return new JsonModel([
            'data' => 'deleted',
        ]);
    }

    /**
     * @param mixed $mapper
     */
    public function setMapper($mapper)
    {
        $this->mapper = $mapper;
    }

    /**
     * @param $results
     *
     * @return array
     */
    protected function getArrayResult($results)
    {
        $albums = []; $count = 0;
        foreach($results as $result) {
            $albums[$count]['id']       = $result->getId();
            $albums[$count]['artist']   = $result->getArtist();
            $albums[$count]['title']    = $result->getTitle();

            $count++;
        }

        return $albums;
    }
}