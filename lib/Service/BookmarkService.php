<?php
namespace OCA\Bookmarks\Service;

use Exception;

use OCP\AppFramework\Db\DoesNotExistException;
use OCP\AppFramework\Db\MultipleObjectsReturnedException;

use OCA\Bookmarks\Db\Bookmark;
use OCA\Bookmarks\Db\BookmarkMapper;


class BookmarkService {

    /** @var BookmarkMapper */
    private $mapper;

    public function __construct(BookmarkMapper $mapper){
        $this->mapper = $mapper;
    }

    public function findAll(string $userId): array {
        return $this->mapper->findAll($userId);
    }

    private function handleException (Exception $e): void {
        if ($e instanceof DoesNotExistException ||
            $e instanceof MultipleObjectsReturnedException) {
            throw new BookmarksNotFound($e->getMessage());
        } else {
            throw $e;
        }
    }

    public function find($id, $userId) {
        try {
            return $this->mapper->find($id, $userId);

        // in order to be able to plug in different storage backends like files
        // for instance it is a good idea to turn storage related exceptions
        // into service related exceptions so controllers and service users
        // have to deal with only one type of exception
        } catch(Exception $e) {
            $this->handleException($e);
        }
    }

    public function create($title, $url, $parentId, $userId) {
        $bookmark = new Bookmark();
        $bookmark->setTitle($title);
        $bookmark->setUrl($url);
        $bookmark->setParentId($parentId);
        $bookmark->setUserId($userId);
        return $this->mapper->insert($bookmark);
    }

    public function update($id, $title, $url, $userId) {
        try {
            $bookmark = $this->mapper->find($id, $userId);
            $bookmark->setTitle($title);
            $bookmark->setUrl($url);
            return $this->mapper->update($bookmark);
        } catch(Exception $e) {
            $this->handleException($e);
        }
    }

    public function delete($id, $userId) {
        try {
            $bookmark = $this->mapper->find($id, $userId);
            $this->mapper->delete($bookmark);
            return $bookmark;
        } catch(Exception $e) {
            $this->handleException($e);
        }
    }

}