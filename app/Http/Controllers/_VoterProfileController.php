<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateVoterProfileRequest;
use App\Http\Resources\VoterProfileResource;
use App\Models\VoterProfile;
use App\Models\Voter;
use Illuminate\Http\RedirectResponse;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class VoterProfileController extends Controller
{
    public function index(): RedirectResponse
    {
        // $voters = VoterProfile::all();

        // return Inertia::render(
        //     'Admin/VoterProfiles/VoterProfileIndex',
        //     ['voterprofiles' => VoterProfileResource::collection($voters)]
        // );
        return to_route('votersprofile.showposition', 'all');
    }


    public function showByPosition($position): Response
    {

        // dd($position);
        $props = [];
        // if ($position === 'all') {
        //     $props = ['voterprofiles' => Inertia::lazy(
        //         fn () =>

        //         VoterProfileResource::collection(VoterProfile::all())
        //     )];
        // } else if ($position === 'coordinator') {
        //     $props = ['coordinator' => Inertia::lazy(
        //         fn () =>

        //         VoterProfileResource::collection(VoterProfile::all())
        //     )];
        // }


        return Inertia::render(
            'Admin/VoterProfiles/VoterProfileIndex',
            ['voterprofiles' => $position !== 'all' ?
                VoterProfileResource::collection(
                    VoterProfile::where('position', $position)->get()
                ) : VoterProfileResource::collection(VoterProfile::all())]
        );
    }


    public function create(Request $request): Response
    {
        $bgy = Voter::distinct()->get('barangay_name')->toArray();
        // dd($bgy);
        return Inertia::render('Admin/VoterProfiles/Create', [
            'barangays' => $bgy,
            'voters' => Voter::query()
                ->when(
                    $request->voter,
                    function (Builder $builder) use ($request) {
                        // dd($request->voter);
                        $builder->where('voter_name', 'like', "%{$request->voter}%");
                    }
                )->whereNotIn('voter_name', VoterProfile::select('name'))->limit(10)->get(),
            'coordinators' => VoterProfile::query()
                ->where('position', '=', 'Coordinator')->get(),
            'leaders' => VoterProfile::query()
                ->where('position', '=', 'Leader')->get(),
            'subleaders' => VoterProfile::query()
                ->where('position', '=', 'Subleader')->get(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateVoterProfileRequest $request): RedirectResponse
    {
        // dd($request->input('position.name'));
        // $request->merge(['position' => $request->input('position.name')]);
        // dd($request);
        VoterProfile::create($request->validated());
        // if ($request->has('permissions')) {
        //     $role->syncPermissions($request->input('permissions.*.name'));
        // }
        return to_route('votersprofile.index');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id): Response
    {
        // dd($id);
        $profile = VoterProfile::find($id);

        $bgy = Voter::distinct()->get('barangay_name')->toArray();
        return Inertia::render('Admin/VoterProfiles/Edit', [
            'barangays' => $bgy,
            'profile' => $profile
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CreateVoterProfileRequest $request, VoterProfile $voterprofile): RedirectResponse
    {
        $voterprofile->update($request->validated());

        return to_route('permissions.index');
    }
}
