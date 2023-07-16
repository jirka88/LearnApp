<?php

namespace App\Http\Controllers;

use App\Http\Requests\SubjectRequest;
use App\Models\Partition;
use App\Models\User;
use App\Models\UserPartition;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;

class SubjectController extends Controller
{

    /**
     * Vrácení všech předmětů
     * @return \Inertia\Response
     */
    public function index()
    {
        $subjects = Partition::all()->where('created_by', auth()->user()->id);
        return Inertia::render('subjects/subjects', compact('subjects'));
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
    public function edit(Partition $subject) {

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
        $subject->update($subjectRequest->validated());
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
