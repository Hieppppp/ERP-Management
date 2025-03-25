<@php

namespace {namespace};

use App\Services\BaseService;
use App\Repositories\BaseRepository;
use {useRepository};

class {class} extends BaseService implements {implements}
{
    /**
     * {repositoryName}
     *
     * @return void
     */
    public function __construct(
        BaseRepository $repository
    ) {
        parent::__construct($repository);
    }
}
