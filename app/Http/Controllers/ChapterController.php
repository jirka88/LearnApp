<?php

namespace App\Http\Controllers;

use App\Http\Requests\ChapterRequest;
use App\Models\Chapter;
use App\Models\Partition;
use Cviebrock\EloquentSluggable\Services\SlugService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ChapterController extends Controller
{
    public function create($slug) {
        return Inertia::render('chapter/createChapter', ['slug' => $slug]);
    }
    public function store(ChapterRequest $chapterRequest) {
        $partition = Partition::where("slug", $chapterRequest->slug)->first();
        Chapter::create([
            "name" => $chapterRequest->name,
            "perex" => $chapterRequest->perex,
            "context" => $chapterRequest->contentChapter,
            "slug" => SlugService::createSlug(Chapter::class, 'slug', $chapterRequest->name),
            "partition_id" => $partition->id
        ]);
        return to_route('subject.show', $chapterRequest->slug);
    }
}
