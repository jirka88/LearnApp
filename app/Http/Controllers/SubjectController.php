<?php

namespace App\Http\Controllers;

use App\Enums\ToastifyStatus;
use App\Enums\UserLicences;
use App\Http\Requests\SubjectRequest;
use App\Models\Licences;
use App\Models\Partition;
use App\Services\SubjectService;
use App\Traits\userTrait;
use Cviebrock\EloquentSluggable\Services\SlugService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;

class SubjectController extends Controller {
    use userTrait;

    protected $subjectModel;
    private $service;

    public function __construct(Partition $subjectModel, SubjectService $service) {
        $this->subjectModel = $subjectModel;
        $this->service = $service;
    }

    /**
     * Vrácení všech předmětů
     *
     * @return \Inertia\Response
     */
    public function index(Request $request) {
        $sort = $request->input('sort', 'default');
        $arr = $this->service->index($this->subjectModel, $sort, auth()->user()->id, $request->page, $request->url(), $request->query());
        return Inertia::render('subjects/subjects', $arr);
    }

    /**
     * Vrácení předmětu s kapitolamy
     *
     * @param  Partition  $subject
     * @return \Inertia\Response
     */
    public function show(Request $request, $slug) {
        $data = $this->service->show($this->subjectModel, $slug, auth()->user()->id, auth()->user()->roles->id);
        $this->authorize('view', $data['subject']);
        return Inertia::render('chapter/chapters', $data);
    }

    /**
     * Redirect k formuláři k vytvoření předmětu
     *
     * @return \Inertia\Response
     */
    public function create() {
        return Inertia::render('subjects/createSubjects');
    }

    /**
     * Vytvoření nového předmětu
     *
     * @return RedirectResponse
     */
    public function store(SubjectRequest $subjectRequest) {
        $user = auth()->user();
        if ($user->licences_id == UserLicences::STANDART && $user->patritions()->count() > Licences::standartUserPartitions) {
            return redirect()->back()->with(['status' => 'error'])->withErrors(['msg' => 'Překročen maximální počet předmětů!']);
        } elseif ($user->licences_id == UserLicences::STANDART_PLUS && $user->patritions()->count() > Licences::standartPlusUserPartitions) {
            return redirect()->back()->with(['status' => 'error'])->withErrors(['msg' => 'Překročen maximální počet předmětů!']);
        } else {
            $subject = $subjectRequest->only('name', 'icon');
            $subject['created_by'] = auth()->user()->id;
            $subject['slug'] = SlugService::createSlug(Partition::class, 'slug', $subjectRequest->name);
            $subjectT = Partition::create($subject);

            $user->patritions()->attach($subjectT->id);

            return to_route('subject.index')->with(['message' => __('validation.custom.create'), 'status' => ToastifyStatus::SUCCESS]);
        }

    }

    /**
     * Redirect k formuláři k aktualizaci předmětu
     *
     * @param  Partition  $subject
     * @return \Inertia\Response
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function edit($slug) {
        $subject = $this->subjectModel->getSubjectBySlug($slug);
        $this->authorize('update', $subject);

        return Inertia::render('subjects/editSubjects', compact('subject'));
    }

    /**
     * Aktualizace předmětu
     *
     * @return RedirectResponse
     */
    public function update(Partition $subject, SubjectRequest $subjectRequest) {
        $subject->update([
            'name' => $subjectRequest->name,
            'icon' => $subjectRequest->icon,
            'slug' => SlugService::createSlug(Partition::class, 'slug', $subjectRequest->name),
        ]);

        return to_route('subject.index')->with(['message' => __('validation.custom.update') , 'status' => ToastifyStatus::SUCCESS]);
    }

    /**
     * Vymazání předmětu a vrácení stávajících
     *
     * @return RedirectResponse
     */
    public function destroy(Request $request, Partition $subject) {
        $subject->delete();
        $sort = $request->input('sort', 'default');
        $arr = $this->service->index($this->subjectModel, $sort, auth()->user()->id, 1, $request->url(), $request->query());
        return redirect()->back()->with(['message' => __('validation.custom.deleted') , 'status' => ToastifyStatus::SUCCESS, $arr]);
    }
    public function searchSubject(Request $request) {
        $search = $request->input('search');
        $subjects = $this->service->searchSubject($search);
        return response()->json(['search' => $subjects]);
    }
}
