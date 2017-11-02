<?php
namespace OCA\Bookmarks\Db;

use OCP\IDBConnection;
use OCP\AppFramework\Db\Mapper;

class BookmarkMapper extends Mapper {

    public function __construct(IDBConnection $db) {
        parent::__construct($db, 'bookmarks', '\OCA\Bookmarks\Db\Bookmark');
    }

    public function find(int $id, string $userId): Bookmark {
        $sql = 'SELECT * FROM *PREFIX*bookmarks WHERE id = ? AND user_id = ?';
        return $this->findEntity($sql, [$id, $userId]);
    }

    public function findParentId($parentId, $userId) : array
    {
        $sql = 'SELECT * FROM *PREFIX*bookmarks WHERE user_id = ? and parent_id = ?';
        return $this->findEntities($sql, [$userId, $parentId]);
    }

    public function findAll(string $userId): array {
        $sql = 'SELECT * FROM *PREFIX*bookmarks WHERE user_id = ?';
        return $this->findEntities($sql, [$userId]);
    }

}