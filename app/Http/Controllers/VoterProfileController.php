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

    public function showByPosition($position, $id = null, $downline = false): Response
    {

        $bgy = Voter::distinct()->get('barangay_name')->toArray();
        $profile = VoterProfile::where('id', $id)->with('members')->with('leader')->first();
        $name = app()->request['searchname'];
        $barangay = app()->request['filterbarangay'];
        $precinct = app()->request['filterprecinct'];
        $showresults = app()->request['results'] ?? 100;

        return Inertia::render(
            'Admin/VoterProfiles/VoterProfileIndex',
            [
                'q' => ['searchname' => $name, 'filterbarangay' => $barangay, 'filterprecinct' => $precinct, 'results' => $showresults],
                // 'editdownline' => app()->request['editdownline'],
                'profile' => fn() => $profile,
                'downline' => fn() => $downline,
                'barangays' => $bgy,
                'precincts' => $barangay ? Voter::where('barangay_name', 'LIKE', "%{$barangay}%")->distinct()->get('precinct_no')->toArray() : [],
                'voterprofiles' => $position !== 'all' ?
                    VoterProfileResource::collection(
                        VoterProfile::where('position', $position)->where('name', 'LIKE', "%{$name}%")
                            ->where('barangay', 'LIKE', "%{$barangay}%")->where('precinct_no', 'LIKE', "%{$precinct}%")
                            ->with('members')->with('leader')
                            ->paginate($showresults)
                            ->through(function ($voter) {
                                return $voter;
                            })
                            ->withQueryString()
                    ) :
                    VoterProfileResource::collection(
                        VoterProfile::where('barangay', 'LIKE', "%{$barangay}%")
                            ->where('name', 'LIKE', "%{$name}%")->where('precinct_no', 'LIKE', "%{$precinct}%")
                            ->paginate($showresults)
                            ->through(function ($voter) {
                                return $voter;
                            })
                            ->withQueryString()
                    ),
                'urlPrev'    => function () {
                    if (url()->previous() !== route('login') && url()->previous() !== '' && url()->previous() !== url()->current()) {
                        return url()->previous();
                    } else {
                        return 'empty'; // used in javascript to disable back button behavior
                    }
                },
            ]
        );
    }

    public function showDownline($id = null): Response
    {

        $bgy = Voter::distinct()->get('barangay_name')->toArray();
        $profile = VoterProfile::where('id', $id)->with('members')->with('leader')->first();
        $name = app()->request['searchname'];
        $barangay = app()->request['filterbarangay'];
        $precinct = app()->request['filterprecinct'];
        $downline = app()->request['showdownline'];
        return Inertia::render(
            'Admin/VoterProfiles/VoterProfileIndex',
            [
                'q' => ['searchname' => $name, 'filterbarangay' => $barangay, 'filterprecinct' => $precinct, 'showdownline' => $downline],
                // 'editdownline' => app()->request['editdownline'],
                // 'profile' => fn() => $profile,

                'barangays' => $bgy,
                'precincts' => $barangay ? Voter::where('barangay_name', 'LIKE', "%{$barangay}%")->distinct()->get('precinct_no')->toArray() : [],
            ]
        );
    }

    public function viewProfile($id = null): Response
    {
        $searchname = app()->request['searchname'];
        $query = Voter::query();

        $query->where('voter_name', 'like', "%{$searchname}%");

        $profile = VoterProfile::where('id', $id)->with('members')->with('leader')->first();
        return Inertia::render('Admin/VoterProfiles/ViewProfile', [
            'profile' => $profile,
            'voters' => $query->whereNotIn('voter_name', VoterProfile::select('name'))->where('precinct_no', $profile->precinct_no)->limit(20)->get(),
            'searchquery' => $searchname
        ]);
    }


    public function addDownline(CreateVoterProfileRequest $request): RedirectResponse
    {
        // dd($request->validated());
        VoterProfile::create($request->validated());
        return redirect()->back();
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
    public function destroy(VoterProfile $votersprofile)
    {
        // dd($voterprofile);
        $votersprofile->delete();
        return back();
    }

    public function bulkDelete(Request $request)
    {
        VoterProfile::whereIn('id', $request->ids)->delete();
        return back();
    }
}
