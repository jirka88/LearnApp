<?php

namespace App\Http\Controllers;

use App\Http\Requests\SubjectRequest;
use App\Models\Chapter;
use App\Models\Partition;
use App\Models\User;
use Cviebrock\EloquentSluggable\Services\SlugService;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;

class SubjectController extends Controller
{

    /**
     * Vrácení všech předmětů
     * @return \Inertia\Response
     */
    public function index()
    {
        $subjects = Partition::withCount('Chapter')->paginate(20)->where('created_by', auth()->user()->id);
        $pages = ceil(count(Partition::all()->where("created_by", auth()->user()->id)) / 20);
        return Inertia::render('subjects/subjects', ['subjects' => $subjects, 'pages' => $pages]);
    }

    /**
     * Vrácení předmětu s kapitolamy
     * @param Partition $subject
     * @return \Inertia\Response
     */
    public function show($slug) {
        $subject = Partition::where('slug', $slug)->first();
        $this->authorize("view", $subject);
        $chapters = Chapter::with('Partition')
            ->whereHas('Partition', function ($query) {
            $query->where('created_by', auth()->user()->id);
        })->where('partition_id', $subject->id)->get(['name', 'perex', 'id', 'slug']);
        return Inertia::render('chapter/chapters', compact('chapters','subject'));
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
