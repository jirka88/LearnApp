<?php

namespace App\Http\Controllers;

use App\Http\Components\Filters;
use App\Http\Components\FilterSubjectSort;
use App\Http\Components\globalSettings;
use App\Http\Requests\SubjectRequest;
use App\Models\Chapter;
use App\Models\Licences;
use App\Models\Partition;
use App\Models\Roles;
use App\Models\User;
use App\Traits\userTrait;
use Cviebrock\EloquentSluggable\Services\SlugService;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Pagination\Paginator;
use Inertia\Inertia;
use function Symfony\Component\String\b;

class SubjectController extends Controller
{
    use userTrait;
    /**
     * Vrácení všech předmětů
     * @return \Inertia\Response
     */
    public function index(Request $request)
    {
        $sort = $request->input('sort');
        $arr = $this->indexJson($sort);
        return Inertia::render('subjects/subjects', $arr);
    }

    /**
     * Vrácení předmětu s kapitolamy
     * @param Partition $subject
     * @return \Inertia\Response
     */
    public function show(Request $request, $slug) {
        $subject = Partition::where('slug', $slug)->first();

        $pShare = $subject->Users()->find(auth()->user()->id, ['user_id'])?->permission;

        if($pShare === null) {
            if(auth()->user()->roles->id == Roles::ADMIN || auth()->user()->roles->id == Roles::OPERATOR) {
                $subject["permission"] = $subject->Users()->find($subject->created_by)->permission;
            }
        }
        else {
            $subject["permission"] = $pShare;
        }
        $this->authorize("view", $subject);
        $chaptersSelect = Chapter::with('Partition')->where('partition_id', $subject->id)->select(['name', 'perex', 'id', 'slug'])->paginate(globalSettings::ITEMS_IN_PAGE);
        $chapters = $chaptersSelect->map(function ($chapter) {
            return $chapter->toArray();
        });
        $pages = Ceil(Count(Chapter::where('partition_id',$subject->id)->get()) / globalSettings::ITEMS_IN_PAGE);

        return Inertia::render('chapter/chapters', compact('chapters','subject', 'pages'));
    }
    /**
     * Redirect k formuláři k vytvoření předmětu
     * @return \Inertia\Response
     */
    public function create()
    {
        return Inertia::render('subjects/createSubjects');
    }

    /**
     * Vytvoření nového předmětu
     * @param SubjectRequest $subjectRequest
     * @return RedirectResponse
     */
    public function store(SubjectRequest $subjectRequest) {
        $user = User::find(auth()->user()->id);
        if($user->licences_id == 1 && $user->patritions()->count() > Licences::standartUserPartitions ) {
            return redirect()->back()->withErrors(['msg' => 'Překročen maximální počet předmětů!']);
        }
        else if($user->licences_id == 2 && $user->patritions()->count() > Licences::standartPlusUserPartitions ) {
            return redirect()->back()->withErrors(['msg' => 'Překročen maximální počet předmětů!']);
        }
        else {
            $subject = $subjectRequest->only('name', 'icon');
            $subject['created_by'] = auth()->user()->id;
            $subject['slug'] = SlugService::createSlug(Partition::class, 'slug', $subjectRequest->name);
            $subjectT = Partition::create($subject);

            $user->patritions()->attach($subjectT->id);
            return to_route('subject.index')->with(['message' => __('validation.custom.create')]);
        }

    }

    /**
     * Redirect k formuláři k aktualizaci předmětu
     * @param Partition $subject
     * @return \Inertia\Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function edit($slug) {
        $subject = Partition::where('slug', $slug)->first();
        $this->authorize('update', $subject);
        return Inertia::render('subjects/editSubjects', compact('subject'));
    }

    /**
     * Aktualizace předmětu
     * @param Partition $subject
     * @param SubjectRequest $subjectRequest
     * @return RedirectResponse
     */
    public function update(Partition $subject, SubjectRequest $subjectRequest) {
        $subject->update([
            'name' => $subjectRequest->name,
            'icon' => $subjectRequest->icon,
            'slug' => SlugService::createSlug(Partition::class, 'slug', $subjectRequest->name)
        ]);
        return to_route('subject.index');
    }

    /**
     * Vymazání předmětu a vrácení stávajících
     * @param Partition $subject
     * @return RedirectResponse
     */
    public function destroy(Request $request, Partition $subject) {
        $sort = "default";
        $subject->delete();
        $arr = $this->indexJson($sort);
        return redirect()->back()->with(['message' => __('validation.custom.deleted'), $arr]);
    }
}
