<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateVoterProfileRequest;
use App\Http\Resources\VoterProfileResource;
use App\Models\Voter;
use App\Models\VoterProfile;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class VoterProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): RedirectResponse
    {
        // $voters = VoterProfile::all();

        // return Inertia::render(
        //     'Admin/VoterProfiles/VoterProfileIndex',
        //     ['voterprofiles' => VoterProfileResource::collection($voters)]
        // );
        return to_route('votersprofile.showposition', 'all');
    }

    public function showByPosition($position, $id = null): Response
    {
        $bgy = Voter::distinct()->get('barangay_name')->toArray();
        $profile = VoterProfile::where('id', $id)->with('members')->with('leader')->first();
        return Inertia::render(
            'Admin/VoterProfiles/VoterProfileIndex',
            [
                'profile' => fn () => $profile,
                'barangays' => $bgy,
                'voterprofiles' => $position !== 'all' ?
                    fn () => VoterProfileResource::collection(
                        VoterProfile::where('position', $position)->with('members')->with('leader')->get()
                    ) : VoterProfileResource::collection(VoterProfile::all())
            ]
        );
    }



    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request): Response
    {
        $bgy = Voter::distinct()->get('barangay_name')->toArray();

        $query = Voter::query();

        if ($request->voter) {
            $voter = $request->voter;
            $query->where('voter_name', 'like', "%{$voter}%");
        }
        // dd($bgy);
        return Inertia::render('Admin/VoterProfiles/Create', [
            'barangays' => $bgy,
            'voters' => $query->whereNotIn('voter_name', VoterProfile::select('name'))->limit(10)->get(),
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

        VoterProfile::create($request->validated());
        return to_route('votersprofile.index');
    }



    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id): Response
    {
        // dd($id);
        $profile = VoterProfile::find($id);
        // dd($profile);
        $bgy = Voter::distinct()->get('barangay_name')->toArray();
        return Inertia::render('Admin/VoterProfiles/Edit', [
            'barangays' => $bgy,
            'profile' => $profile
        ]);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(CreateVoterProfileRequest $request, $id): RedirectResponse
    {
        // dd($request->validated());
        $voterprofile = VoterProfile::findOrFail($id);
        $voterprofile->update($request->validated());

        if ($request->has('redirectUrl')) {
            return redirect($request->redirectUrl);
        }
        return back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
