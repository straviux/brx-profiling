<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateChristianCommunityProfileRequest;
use App\Http\Resources\ChristianCommunityResource;
use App\Models\Voter;
use App\Models\ChristianCommunity;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ChristianCommunityController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): RedirectResponse
    {
        // $voters = ChristianCommunity::all();

        // return Inertia::render(
        //     'Admin/VoterProfiles/VoterProfileIndex',
        //     ['voterprofiles' => ChristianCommunityResource::collection($voters)]
        // );
        return to_route('christiancommunity.showposition', 'all');
    }

    public function showByPosition($position, $action = null, $id = null): Response
    {

        $name = app()->request['searchname'];
        $municipality = app()->request['filtermunicipality'];
        $barangay = app()->request['filterbarangay'];
        $precinct = app()->request['filterprecinct'];

        $municipalities = Voter::distinct()->pluck('municipality_name');
        $bgy = Voter::where('municipality_name', '=', $municipality)->distinct()->pluck('barangay_name');
        $profile = ChristianCommunity::where('id', $id)->with('members')->with('leader')->first();

        $showresults = app()->request['results'] ?? 100;
        $voterprofile = $position !== 'all' ?
            ChristianCommunityResource::collection(
                ChristianCommunity::where('position', $position)->where('name', 'LIKE', "%{$name}%")
                    ->where('barangay', 'LIKE', "%{$barangay}%")->where('precinct_no', 'LIKE', "%{$precinct}%")
                    ->with('members')->with('leader')
                    ->paginate($showresults)
                    ->through(function ($voter) {
                        return $voter;
                    })
                    ->withQueryString()
            ) :
            ChristianCommunityResource::collection(
                ChristianCommunity::where('barangay', 'LIKE', "%{$barangay}%")
                    ->where('name', 'LIKE', "%{$name}%")->where('precinct_no', 'LIKE', "%{$precinct}%")
                    ->paginate($showresults)
                    ->through(function ($voter) {
                        return $voter;
                    })
                    ->withQueryString()
            );
        return Inertia::render(
            'Admin/ChristianCommunity/Index',
            [
                'q' => ['searchname' => $name, 'filtermunicipality' => $municipality, 'filterbarangay' => $barangay, 'filterprecinct' => $precinct, 'results' => $showresults],
                // 'editdownline' => app()->request['editdownline'],
                'profile' => fn() => $profile,

                'municipalities' => $municipalities,
                'barangays' => $bgy,
                'precincts' => $barangay ? Voter::where('municipality_name', '=', 'brooke\'s point')->where('barangay_name', 'LIKE', "%{$barangay}%")->distinct()->get('precinct_no')->toArray() : [],
                'voterprofiles' => fn() => $voterprofile,
                'search_count' => count($voterprofile),
                'total_count' => ChristianCommunity::count(),
                'action' => fn() => $action,
                'coordinators' => $action !== 'create' ? null : ChristianCommunity::query()
                    ->where('position', '=', 'Coordinator')->get(),
                'leaders' => fn() => $action !== 'create' ? null : ChristianCommunity::query()
                    ->where('position', '=', 'Leader')->get(),
                'subleaders' => fn() => $action !== 'create' ? null : ChristianCommunity::query()
                    ->where('position', '=', 'Subleader')->get(),

            ]
        );
    }

    // public function showDownline($id = null): Response
    // {

    //     $bgy = Voter::where('municipality_name', '=', 'brooke\'s point')->distinct()->pluck('barangay_name');
    //     $profile = ChristianCommunity::where('id', $id)->with('members')->with('leader')->first();
    //     $name = app()->request['searchname'];
    //     $barangay = app()->request['filterbarangay'];
    //     $precinct = app()->request['filterprecinct'];
    //     $downline = app()->request['showdownline'];
    //     return Inertia::render(
    //         'Admin/ChristianCommunity/Index',
    //         [
    //             'q' => ['searchname' => $name, 'filterbarangay' => $barangay, 'filterprecinct' => $precinct, 'showdownline' => $downline],
    //             // 'editdownline' => app()->request['editdownline'],
    //             // 'profile' => fn() => $profile,

    //             'barangays' => $bgy,
    //             'precincts' => $barangay ? Voter::where('barangay_name', 'LIKE', "%{$barangay}%")->distinct()->get('precinct_no')->toArray() : [],
    //         ]
    //     );
    // }

    public function viewProfile($id = null): Response
    {
        $searchname = app()->request['searchname'];
        $municipality = app()->request['filtermunicipality'];
        $barangay = app()->request['filterbarangay'];
        $query = Voter::query();

        $query->where('municipality_name', '=', $municipality)->where('barangay_name', '=', $barangay)->where('voter_name', 'like', "%{$searchname}%");

        $profile = ChristianCommunity::where('id', $id)->with('members')->with('leader')->first();
        $exclude = [];
        foreach ($profile->members as $mem) {
            $exclude[] = $mem['name'];
        };
        $exclude[] = $profile->leader->name ?? "";
        $exclude[] = $profile->name ?? "";
        // dd(implode($exclude));

        $exc_position = [];
        if ($profile->position == 'COORDINATOR') {
            $exc_position = ['COORDINATOR', 'SUBLEADER', 'MEMBER'];
            // $exclude[] = ChristianCommunity::select('name')->whereIn('position', );
        }
        if ($profile->position == 'LEADER') {
            // $exclude[] = ChristianCommunity::select('name')->whereIn('position', ['COORDINATOR', 'LEADER', 'MEMBER']);
            $exc_position = ['COORDINATOR', 'LEADER', 'MEMBER'];
        }
        if ($profile->position == 'SUBLEADER') {
            $exc_position = ['COORDINATOR', 'LEADER', 'SUBLEADER'];
            // $exclude[] = ChristianCommunity::select('name')->whereIn('position', ['COORDINATOR', 'LEADER', 'SUBLEADER']);
        }
        if ($profile->position == 'MEMBER') {
            $exc_position = ['COORDINATOR', 'LEADER', 'SUBLEADER', 'MEMBER'];
            // $exclude[] = ChristianCommunity::select('name')->whereIn('position', ['COORDINATOR', 'LEADER', 'SUBLEADER', 'MEMBER']);
        }
        $query2 = ChristianCommunity::select('name')->whereIn('position', $exc_position)->get();
        foreach ($query2 as $exclude_by_position) {
            $exclude[] = $exclude_by_position['name'];
        }
        $query->where('precinct_no', $profile->precinct_no)->whereNotIn('voter_name', $exclude);
        // if ($profile->position == 'COORDINATOR') {
        //     $query->whereIn('voter_name', ChristianCommunity::select('name')->where('position', '=', 'LEADER'));
        // }
        // if ($profile->position == 'LEADER') {
        //     $query->whereIn('voter_name', ChristianCommunity::select('name')->where('position', '=', 'SUBLEADER'));
        // }
        // if ($profile->position == 'SUBLEADER') {
        //     $query->whereIn('voter_name', ChristianCommunity::select('name')->where('position', '=', 'MEMBER'));
        // }

        return Inertia::render('Admin/ChristianCommunity/ViewProfile', [
            'profile' => $profile,
            'voters' => $query->limit(20)->get(),
            'searchquery' => $searchname
        ]);
    }


    public function addDownline(): RedirectResponse
    {

        // dd(request()->validate());
        // $id = route('votersprofile') ?? nu
        $query = ChristianCommunity::where('name', app()->request['name'])->first();
        // dd($query->id);
        if (isset($query->id)) {
            $query->update(['parent_id' => app()->request['parent_id']]);
        } else {
            $validatedData = request()->validate([
                "name" => [
                    'required',
                    'string',
                    'max:255',
                ],
                "firstname" => [
                    'required',
                    'string',
                    'max:255'
                ],
                "lastname" => [
                    'required',
                    'string',
                    'max:255'
                ],
                "middlename" => [
                    'nullable',
                    'string',
                    'max:255'
                ],
                "municipality" => [
                    'required',
                    'string',
                    'max:255'
                ],

                "barangay" => [
                    'required',
                    'string',
                    'max:255'
                ],
                "precinct_no" => [
                    'required',
                    'string',
                    'max:255'
                ],
                "position" => [
                    'required',
                    'string',
                    'max:255'
                ],
                "contact_no" => [
                    'nullable',
                    'string',
                    'max:255'
                ],
                "birthdate" => [
                    'nullable',
                    'string',
                    'max:255'
                ],
                "gender" => [
                    'nullable',
                    'string',
                    'max:255'
                ],
                "remarks" => [
                    'nullable',
                    'string',
                    'max:500'
                ],
                "purok" => [
                    'nullable',
                    'string',
                    'max:255'
                ],
                "parent_id" => [
                    'nullable',
                    'exists:christiancommunity,id'
                ],



            ]);

            ChristianCommunity::create($validatedData);
        }
        // dd($query->id);

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
        return Inertia::render('Admin/ChristianCommunity/Create', [
            'barangays' => $bgy,
            'voters' => $query->whereNotIn('voter_name', ChristianCommunity::select('name'))->limit(10)->get(),
            'coordinators' => ChristianCommunity::query()
                ->where('position', '=', 'Coordinator')->get(),
            'leaders' => ChristianCommunity::query()
                ->where('position', '=', 'Leader')->get(),
            'subleaders' => ChristianCommunity::query()
                ->where('position', '=', 'Subleader')->get(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateChristianCommunityProfileRequest $request): RedirectResponse
    {

        // Gate::authorize('create', ChristianCommunity::class);
        ChristianCommunity::create($request->validated());
        return back();
    }



    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id): Response
    {
        // dd($id);
        // Gate::authorize('update', ChristianCommunity::class);
        $profile = ChristianCommunity::find($id);
        // dd($profile);
        $bgy = Voter::distinct()->get('barangay_name')->toArray();
        return Inertia::render('Admin/ChristianCommunity/Edit', [
            'barangays' => $bgy,
            'profile' => $profile
        ]);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(CreateChristianCommunityProfileRequest $request, $id): RedirectResponse
    {
        // dd($request->validated());
        // Gate::authorize('update', ChristianCommunity::class);
        $christiancommunity = ChristianCommunity::findOrFail($id);
        $christiancommunity->update($request->validated());

        if ($request->has('redirectUrl')) {
            return redirect($request->redirectUrl);
        }
        return back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ChristianCommunity $christiancommunity)
    {
        // dd($voterprofile);
        // Gate::authorize('delete', $christiancommunity);
        $christiancommunity->delete();
        return back();
    }

    public function bulkDelete(Request $request)
    {
        // Gate::authorize('delete', ChristianCommunity::class);
        ChristianCommunity::whereIn('id', $request->ids)->delete();
        return back();
    }
}
