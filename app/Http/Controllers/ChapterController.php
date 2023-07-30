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
    /**
     * Odkázání na formulář k vytvoření kapitoly
     * @param $slug
     * @return \Inertia\Response
     */
    public function create($slug) {
        return Inertia::render('chapter/createChapter', ['slug' => $slug]);
    }

    /**
     * Vytvoření kapitoly
     * @param ChapterRequest $chapterRequest
     * @return \Illuminate\Http\RedirectResponse
     */
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

    /**
     * Vymazání kapitoly
     * @param Request $request
     * @param $slug
     * @param $chapter
     * @return void
     */
    public function destroy(Request $request, $slug, $chapter) {
        $chapterDelete = Chapter::where('slug', $chapter)->first();
        $chapterDelete->delete();
    }
}
