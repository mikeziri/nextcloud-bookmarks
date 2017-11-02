<?php
namespace OCA\Bookmarks\Db;

use JsonSerializable;

use OCP\AppFramework\Db\Entity;

class Bookmark extends Entity implements JsonSerializable {

    protected $title;
    protected $url;
    protected $userId;
    protected $parentId;
    protected $isFolder;

    public function jsonSerialize(): array {
        return [
            'id' => $this->id,
            'parent_id' => (int)$this->parentId,
            'title' => $this->title,
            'url' => $this->url,
            'is_folder' => (bool)$this->isFolder,
        ];
    }
}