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
    public function show(Request $request, $slug, $chapter) {
        $chapter = Chapter::where('id', $chapter)->first();
        return Inertia::render('chapter/chapter', ['chapter' => $chapter, 'slug' => $slug]);
    }
    /**
     * Odkázání na formulář k vytvoření kapitoly
     * @param $slug
     * @return \Inertia\Response
     */
    public function create($slug) {
        //$this->authorize('create');
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
     * Odkázání na formulář k aktualizaci kapitoly
     * @param Request $request
     * @param $slug
     * @param $chapter
     * @return \Inertia\Response
     */
    public function edit(Request $request, $slug, $chapter) {
        $chapter = Chapter::where("slug", $chapter)->with("partition")->first();
        $this->authorize('update', $chapter);
        return Inertia::render('chapter/editChapter', ['chapter' => $chapter, 'slug' => $slug]);
    }

    /**
     * Aktualizace kapitoly
     * @param ChapterRequest $chapterRequest
     * @param $slug
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(ChapterRequest $chapterRequest, $slug) {
        $chapter = Chapter::where("slug", $chapterRequest->slug)->first();
        $this->authorize('update', $chapter);
        $chapter->update([
            'name' => $chapterRequest->name,
            'perex' => $chapterRequest->perex,
            'context' => $chapterRequest->contentChapter,
            'slug' => SlugService::createSlug(Chapter::class, 'slug', $chapterRequest->name),
        ]);
        return to_route('subject.show', $slug);
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
