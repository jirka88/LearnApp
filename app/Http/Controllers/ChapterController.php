<?php

namespace App\Http\Controllers;

use App\Http\Requests\ChapterRequest;
use App\Models\Chapter;
use App\Models\Licences;
use App\Models\Partition;
use App\Models\Roles;
use App\Models\User;
use Cviebrock\EloquentSluggable\Services\SlugService;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;
use function Symfony\Component\String\b;

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
        if(Chapter::where("partition_id", $partition->id)->where("name", $chapterRequest->name)->first() !== null) {
            return redirect()->back()->withErrors(["name" => "Jméno musí být unikátní!"]);
        }
        if(auth()->user()->licences->id == 1 && $partition->Chapter()->count() > Licences::standartUserChaptersInPartitions) {
            return redirect()->back()->with(["LicenceLimitations" => "Přesáhnut limit!"]);
        }
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
        $chapterModel = new Chapter();
        $chapter =  $chapterModel->getChapter($chapter)->Partition()->with(['Users' => function ($query2) {
                $query2->find(auth()->user()->id);
            }])->first();
        $this->authorize('update', $chapter);
        return Inertia::render('chapter/editChapter', ['chapter' => $chapter, 'slug' => $chapter->slug]);
    }

    /**
     * Aktualizace kapitoly
     * @param ChapterRequest $chapterRequest
     * @param $slug
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(ChapterRequest $chapterRequest, $slug) {
        $chapterModel = new Chapter();
        $chapter = $chapterModel->getChapter($slug);
        $this->authorize('update', $chapter);
        $chapter->update([
            'name' => $chapterRequest->name,
            'perex' => $chapterRequest->perex,
            'context' => $chapterRequest->contentChapter,
            'slug' => SlugService::createSlug(Chapter::class, 'slug', $chapterRequest->name),
        ]);
        return to_route('subject.show', $chapter->Partition()->find($chapter->partition_id)->slug);
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
        $chapter = [];
        if($sort !== null) {
            $chapter = Chapter::where('name', 'LIKE', '%'.$sort.'%')->select('name', 'perex','slug')->get();
        }
        if(count($chapter) === 0) {
            $chapter = 'Nic nenalezeno!';
        }


        return response()->json(["search" => $chapter]);
    }
}
