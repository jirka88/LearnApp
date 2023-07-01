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

        $subject['icon'] = $subjectRequest->icon;
        $subjectT = Partition::create($subject);

        $user = User::find(auth()->user()->id);
        $user->patritions()->attach($subjectT->id);
    }
    public function edit(Partition $subject) {
        return Inertia::render('editSubjects', compact('subject'));
    }
}
