<?php

namespace OCA\Bookmarks\Controller;

use OCP\IRequest;
use OCP\AppFramework\Http\DataResponse;
use OCP\AppFramework\Controller;

use OCA\Bookmarks\Service\BookmarkService;

class BookmarksController extends Controller {
    /** @var BookmarkService */
    private $service;

    /** @var string */
    private $userId;

    use Errors;

    public function __construct($appName,
                                IRequest $request,
                                BookmarkService $service,
                                $userId) {
        parent::__construct($appName, $request);
        $this->service = $service;
        $this->userId = $userId;
    }

    /**
     * @NoAdminRequired
     */
    public function index(): DataResponse {
        return new DataResponse($this->service->findAll($this->userId));
    }

    /**
     * @NoAdminRequired
     */
    public function show(int $id): DataResponse {
        return $this->handleNotFound(function () use ($id) {
            return $this->service->find($id, $this->userId);
        });
    }

    /**
     * @NoAdminRequired
     * @param string $title
     * @param string $url
     * @param $parentId
     * @return DataResponse
     */
    public function create(string $title, string $url, $parentId): DataResponse {
        return new DataResponse($this->service->create(
            $title,
            $url,
            $parentId,
            $this->userId));
    }

    /**
     * @NoAdminRequired
     */
    public function update(int $id, string $title,
                           string $url): DataResponse {
        return $this->handleNotFound(function () use ($id, $title, $url) {
            return $this->service->update($id, $title, $url, $this->userId);
        });
    }

    /**
     * @NoAdminRequired
     */
    public function destroy(int $id): DataResponse {
        return $this->handleNotFound(function () use ($id) {
            return $this->service->delete($id, $this->userId);
        });
    }

}
