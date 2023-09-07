<?php

namespace App\Http\Controllers;

use App\Http\Requests\ChapterRequest;
use App\Models\Chapter;
use App\Models\Partition;
use App\Models\Roles;
use App\Models\User;
use Cviebrock\EloquentSluggable\Services\SlugService;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;
class ChapterController extends Controller
{
    public function show(Request $request, $slug, $chapterName) {
        $chapter = Chapter::where('slug', $chapterName)->with(['Partition.Users' => function ($query) {
          $query->where('user_id', auth()->user()->id);
        }])->first();
        if(count($chapter->Partition->Users) === 0) {
            if(auth()->user()->roles->id == Roles::ADMIN || auth()->user()->roles->id == Roles::OPERATOR) {
                $chapter->Partition->Users[] = User::find($chapter->Partition->created_by);
                $chapter->Partition->Users->first()["permission"] = ["permission_id" => null];
            }
        }
        $this->authorize('view', $chapter);
        return Inertia::render('chapter/chapter', ['chapter' => $chapter, 'slug' => $slug]);
    }
    /**
     * Odkázání na formulář k vytvoření kapitoly
     * @param $slug
     * @return \Inertia\Response
     */
    public function create($slug) {
        $user = User::with(['patritions' => function ($query) use ($slug) {
            $query->where('slug', $slug)->first();
        }])->find(auth()->user()->id);
        $this->authorize("create", $user);
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
        $chapter = Chapter::where("slug", $chapter)->with(["partition" => function ($query) {
            $query->with(['Users' => function ($query2) {
                $query2->find(auth()->user()->id);
            }])->first();
        }])->first();

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
        return to_route('subject.show', $slug);
    }
    public function selectChapter(Request $request) {
        $sort = $request->input('select');
        $chapter = Chapter::where('name', $sort)->select('name', 'perex', 'id', 'slug')->first();
        return response()->json($chapter);
    }
}
