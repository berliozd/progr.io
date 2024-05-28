<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\SeoService;

class SeoController extends Controller
{
    public function __construct(private readonly SeoService $seoService)
    {
    }

    public function meta()
    {
        return [
            'keywords' => $this->seoService->getGenericKeywords(),
            'description' => $this->seoService->getGenericDescription()
        ];
    }
}
