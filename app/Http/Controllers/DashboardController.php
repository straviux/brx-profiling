<?php

namespace App\Http\Controllers;


use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    //
    public function index(): Response
    {
        return Inertia::render('Dashboard', [
            'dashboard_links' => [
                'dashboard',
                'voterslist.index',
                'votersprofile.index',
                'christiancommunity.index'
            ]
        ]);
    }
}
