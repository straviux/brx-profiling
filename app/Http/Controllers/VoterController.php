<?php

namespace App\Http\Controllers;

use App\Http\Resources\VoterResource;
use Illuminate\Http\Request;
use App\Models\Voter;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Inertia\Response;

class VoterController extends Controller
{

    public function initVoters($file)
    {
        $records = File::json(base_path('storage/app/' . $file));
        // dd($records);
        foreach ($records['voters'] as $record) {
            $voter = new Voter;
            $voter->voter_name = $record['voter_name'];
            $voter->precinct_no = $record['precinct_no'];
            $voter->barangay_name = $record['barangay_name'];
            $voter->municipality_name = $record['municipality_name'];
            $voter->pro_voter_id = $record['pro_voter_id'];
            $voter->save();
        }

        return $file . ' initiated successfully';
    }

    public function findVoter(): Response
    {
        $bgy = Voter::distinct()->get('barangay_name')->toArray();
        $name = app()->request['searchname'];
        $barangay = app()->request['filterbarangay'];
        $precinct = app()->request['filterprecinct'];
        $showresults = app()->request['results'] ?? 100;
        $query = Voter::where('barangay_name', 'LIKE', "%{$barangay}%")
            ->where('voter_name', 'LIKE', "%{$name}%")->where('precinct_no', 'LIKE', "%{$precinct}%");

        return Inertia::render('VotersList/VotersListIndex', [
            'q' => ['searchname' => $name, 'filterbarangay' => $barangay, 'filterprecinct' => $precinct, 'results' => $showresults],
            'barangays' => $bgy,
            'precincts' => $barangay ? Voter::where('barangay_name', 'LIKE', "%{$barangay}%")->distinct()->get('precinct_no')->toArray() : [],
            'voters' => VoterResource::collection(
                $query->paginate($showresults)
                    ->through(function ($voter) {
                        return $voter;
                    })
                    ->withQueryString()
            ),
            'search_count' => $query->count(),
            'total_count' => Voter::count(),

        ]);
    }
}
