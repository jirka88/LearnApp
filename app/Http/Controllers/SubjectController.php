<?php

namespace App\Http\Controllers;

use App\Http\Requests\SubjectRequest;
use App\Models\Partition;
use App\Models\User;
use App\Models\UserPartition;
use Illuminate\Http\Request;
use Inertia\Inertia;

class SubjectController extends Controller
{

    public function index()
    {
        return Inertia::render('subjects');
    }
    public function create()
    {
        return Inertia::render('createSubjects');
    }
    public function store(SubjectRequest $subjectRequest) {
        $subject = $subjectRequest->only('name');
        $subject['created_by'] = auth()->user()->id;
        $subject['icon'] = $subjectRequest->icon["iconName"];
        $subjectT = Partition::create($subject);

        $user = User::find(auth()->user()->id);
        $user->patritions()->attach($subjectT->id);
        return to_route('subject.index');
    }
    public function edit(Partition $subject) {
        if(auth()->user()->id == $subject->created_by) {
            return Inertia::render('editSubjects', compact('subject'));
        }
        else {
            abort(401);
        }
    }
    public function update(Partition $subject, SubjectRequest $subjectRequest) {
        $subject->update($subjectRequest->validated());
        return to_route('subject.index');
    }
    public function destroy(Partition $subject) {
        $subject->delete();
    }
}
