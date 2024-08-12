<?php

namespace App\Http\Controllers;

use App\Enums\ToastifyStatus;
use App\Enums\UserLicences;
use App\Enums\UserRoles;
use App\Exports\ChapterExport;
use App\Exports\UsersExport;
use App\Http\Requests\ChapterRequest;
use App\Http\Resources\ChapterSelectResource;
use App\Models\Chapter;
use App\Models\Licences;
use App\Models\User;
use App\Rules\UniqueChapterName;
use Cviebrock\EloquentSluggable\Services\SlugService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;
use Maatwebsite\Excel\Facades\Excel;

class ChapterController extends Controller
{
    protected $chapterModel;

    public function __construct(Chapter $chapterModel)
    {
        $this->chapterModel = $chapterModel;
    }

    /**
     * Vrácení kapitoly
     *
     * @return \Inertia\Response
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function show(Request $request, $slug, $chapterName)
    {
        $chapter = $this->chapterModel->getChapterWithPermission($chapterName);
        if ($chapter->Partition->Users) {
            if (auth()->user()->roles->id == UserRoles::ADMIN || auth()->user()->roles->id == UserRoles::OPERATOR) {
                $chapter->Partition->Users = User::find($chapter->Partition->created_by);
                $chapter->Partition->Users['permission'] = ['permission_id' => null];
            }
        }
        $this->authorize('view', $chapter);

        return Inertia::render('Chapter/Show', ['chapter' => $chapter, 'slug' => $slug]);
    }

    /**
     * Odkázání na formulář k vytvoření kapitoly
     *
     * @return \Inertia\Response
     */
    public function create(Request $request, $slug)
    {
        $user = auth()->user()->loadMissing(['patritions' => function ($query) use ($slug) {
            $query->where('slug', $slug)->firstOrFail();
        }]);
        $this->authorize('create', $user);

        return Inertia::render('Chapter/Create', ['slug' => $slug]);
    }

    /**
     * Vytvoření kapitoly
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(ChapterRequest $chapterRequest)
    {
        $subjectModel = app('App\Models\Partition');
        $partition = $subjectModel->getSubjectBySlug($chapterRequest->slug);
        if ($this->chapterModel->getChapterByNameAndPatrition($partition->id, $chapterRequest->name) !== null) {
            return redirect()->back()->with('status', ToastifyStatus::ERROR)->withErrors(['name' => 'Jméno musí být unikátní!']);
        }
        if (auth()->user()->licences->id === UserLicences::STANDART && $partition->Chapter()->count() >= Licences::standartUserChaptersInPartitions) {
            return redirect()->back()->with('status', ToastifyStatus::ERROR)->withErrors(['message' => 'Přesáhnut limit!']);
        }
        Chapter::create([
            'name' => $chapterRequest->name,
            'perex' => $chapterRequest->perex,
            'context' => $chapterRequest->contentChapter,
            'slug' => SlugService::createSlug(Chapter::class, 'slug', $chapterRequest->name),
            'partition_id' => $partition->id,
        ]);

        return to_route('subject.show', $partition->slug)->with(['status' => ToastifyStatus::SUCCESS, 'message' => 'Kapitola úspěšně vytvořena!']);
    }

    /**
     * Odkázání na formulář k aktualizaci kapitoly
     *
     * @return \Inertia\Response
     */
    public function edit($slug, $chapter)
    {
        $chapter = $this->chapterModel->getChapterWithPermission($chapter);
        $this->authorize('update', $chapter);

        return Inertia::render('Chapter/Edit', ['chapter' => $chapter, 'slug' => $chapter->slug]);
    }

    /**
     * Aktualizace kapitoly
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(ChapterRequest $chapterRequest, $slug)
    {
        $chapter = $this->chapterModel->getChapter($chapterRequest->slug);
        $chapter->name = $chapterRequest->name;
        $uniqueChapterNameRule = new UniqueChapterName;
        if (!$uniqueChapterNameRule->passes('name', $chapter)) {
            return redirect()->back()->withErrors($uniqueChapterNameRule->message());
        }
        $this->authorize('update', $chapter);
        $subject = app('App\Models\Partition');
        $chapter->update([
            'name' => $chapterRequest->name,
            'perex' => $chapterRequest->perex,
            'context' => $chapterRequest->contentChapter,
            'slug' => SlugService::createSlug(Chapter::class, 'slug', $chapterRequest->name),
        ]);

        return to_route('subject.show', $subject->getSubjectById($chapter->partition_id)->slug)->with(['message' => __('validation.custom.update'), 'status' => ToastifyStatus::SUCCESS]);
    }

    /**
     * Vymazání kapitoly
     *
     * @return void
     */
    public function destroy($slug, $chapter)
    {
        $chapterDelete = $this->chapterModel->getChapter($chapter);
        $chapterDelete->delete();
        return to_route('subject.show', $slug)->with(['status' => ToastifyStatus::SUCCESS, 'message' => 'Kapitola byla úspěšně vymazána']);
    }

    /**
     * Vyhledání kapitol
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function selectChapter(Request $request, $slug)
    {
        $sort = $request->input('select');
        $subject = app('App\Models\Partition');
        $subjectId = $subject->getSubjectId($slug);
        $chapter = [];
        if ($sort !== null) {
            $chapter = ChapterSelectResource::collection(Chapter::where('name', 'LIKE', '%' . $sort . '%')->where('partition_id', $subjectId)->get());
        }
        if (count($chapter) === 0) {
            $chapter = ['item' => 'Nic nenalezeno!'];
        }

        return response()->json(['search' => $chapter]);
    }

    /**
     * Exportování kapitoly
     * @param Request $request
     * @param $slug
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function exportFile(Request $request, $slug, $chapterSlug)
    {
        $chapter = $this->chapterModel->getChapterWithPermission($chapterSlug);
        $this->authorize('view', $chapter);
        $extension = $request->input('export');
        switch ($extension) {
            case 'pdf':
                $sheet = Excel::download(new ChapterExport($chapter), 'uzivatele.pdf', \Maatwebsite\Excel\Excel::DOMPDF);
                break;
        }
        return $sheet;
    }
}
