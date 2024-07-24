<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Voter;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class VoterController extends Controller
{

    public function initVoters()
    {
        $records = File::json(base_path('storage/app/voters_6.json'));
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

        return 'voters initiated successfully';
    }
}
