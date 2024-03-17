<?php

namespace App\Http\Controllers;

use App\Enums\UserLicences;
use App\Enums\UserRoles;
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
    protected $chapterModel;

    public function __construct(Chapter $chapterModel)
    {
        $this->chapterModel = $chapterModel;
    }
    /**
     * Vrácení kapitoly
     * @param Request $request
     * @param $slug
     * @param $chapterName
     * @return \Inertia\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function show(Request $request, $slug, $chapterName) {
        $chapter = $this->chapterModel->getChapterWithPermission($chapterName);
        if($chapter->Partition->Users) {
            if(auth()->user()->roles->id == UserRoles::ADMIN || auth()->user()->roles->id == UserRoles::OPERATOR) {
                $chapter->Partition->Users = User::find($chapter->Partition->created_by);
                $chapter->Partition->Users["permission"] = ["permission_id" => null];
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
            $query->where('slug', $slug)->firstOrFail();
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
        $subjectModel = app('App\Models\Partition');
        $partition =  $subjectModel->getSubjectBySlug($chapterRequest->slug);
        if(Chapter::where("partition_id", $partition->id)->where("name", $chapterRequest->name)->first() !== null) {
            return redirect()->back()->withErrors(["name" => "Jméno musí být unikátní!"]);
        }
        if(auth()->user()->licences->id === UserLicences::STANDART && $partition->Chapter()->count() >= Licences::standartUserChaptersInPartitions) {
            return redirect()->back()->withErrors(["message" => "Přesáhnut limit!"]);
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
        $chapter = $this->chapterModel->getChapterWithPermission($chapter);
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
        $chapter = $this->chapterModel->getChapter($slug);
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
        $chapterDelete = $this->chapterModel->getChapter($chapter);
        $chapterDelete->delete();
        return to_route('subject.show', $slug);
    }

    /**
     * Vyhledání kapitol
     * @param Request $request
     * @param $slug
     * @return \Illuminate\Http\JsonResponse
     */
    public function selectChapter(Request $request, $slug) {
        $sort = $request->input('select');
        $subject = app('App\Models\Partition');
        $subjectId = $subject->getSubjectId($slug);
        $chapter = [];
        if($sort !== null) {
            $chapter = Chapter::where('name', 'LIKE', '%'.$sort.'%')->where('partition_id', $subjectId)->select('name', 'perex','slug')->get();
        }
        if(count($chapter) === 0) {
            $chapter = ['item' => 'Nic nenalezeno!'];
        }
        return response()->json(["search" => $chapter]);
    }
}
