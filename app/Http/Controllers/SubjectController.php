<?php

namespace App\Http\Controllers;

use App\Http\Requests\SubjectRequest;
use App\Models\Chapter;
use App\Models\Partition;
use App\Models\Roles;
use App\Models\User;
use Cviebrock\EloquentSluggable\Services\SlugService;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;

class SubjectController extends Controller
{
    public $ItemsInPages = 20;

    /**
     * Vrácení všech předmětů
     * @return \Inertia\Response
     */
    public function index()
    {
        $subjects = Partition::withCount('Chapter')->paginate(20)->where('created_by', auth()->user()->id);
        $pages = ceil(count(Partition::all()->where("created_by", auth()->user()->id)) / $this->ItemsInPages);
        return Inertia::render('subjects/subjects', ['subjects' => $subjects, 'pages' => $pages]);
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
        $chaptersSelect = Chapter::with('Partition')->where('partition_id', $subject->id)->select(['name', 'perex', 'id', 'slug'])->paginate($this->ItemsInPages);
        $chapters = $chaptersSelect->map(function ($chapter) {
            return $chapter->toArray();
        });
        $pages = Ceil(Count(Chapter::where('partition_id',$subject->id)->get()) / $this->ItemsInPages);
        $loadedSelectedChapter = Chapter::where('name', $request->select)->first();

        if($loadedSelectedChapter == null) {
            $loadedSelectedChapter = '';
        }
        $AllChapter = $subject->Chapter()->pluck("name");
        return Inertia::render('chapter/chapters', compact('chapters','subject', 'pages', 'AllChapter', 'loadedSelectedChapter'));
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
        $subject = $subjectRequest->only('name');
        $subject['created_by'] = auth()->user()->id;
        $subject['icon'] = $subjectRequest->icon["iconName"];
        $subject['slug'] = SlugService::createSlug(Partition::class, 'slug', $subjectRequest->name);
        $subjectT = Partition::create($subject);
        $user = User::find(auth()->user()->id);

        $user->patritions()->attach($subjectT->id);
        return to_route('subject.index');
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
     * Vymazání předmětu
     * @param Partition $subject
     * @return void
     */
    public function destroy(Partition $subject) {
        $subject->delete();
    }
}
