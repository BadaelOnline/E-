<?php

namespace App\Http\Controllers\SocialMedia;

use App\Http\Controllers\Controller;
use App\Http\Requests\SocialMedia\SocialMediaRequest;
use App\Service\SocialMedia\SocialMediaService;

class SocialMediaController extends Controller
{
    private $SocialMediaService;

    public function __construct(SocialMediaService $SocialMediaService)
    {
        $this->SocialMediaService=$SocialMediaService;
    }
    public function get()
    {
        return$this->SocialMediaService->get();
    }
    public function  getById($id)
    {
        return $this->SocialMediaService->getById($id);
    }
    public function getTrashed()
    {
        return $this->SocialMediaService->getTrashed();
    }
    public function create(SocialMediaRequest $request)
    {
        return $this->SocialMediaService->create($request);
    }
    public function update(SocialMediaRequest $request,$id)
    {
        return $this->SocialMediaService->update($request,$id);
    }
    public function trash($id)
    {
        return $this->SocialMediaService->trash($id);
    }
    public function restoreTrashed($id)
    {
        return $this->SocialMediaService->restoreTrashed($id);
    }
    public function delete($id)
    {
        return $this->SocialMediaService->delete($id);
    }
}
