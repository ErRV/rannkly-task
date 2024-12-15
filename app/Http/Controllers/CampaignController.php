<?php

namespace App\Http\Controllers;

use App\Models\Campaign;
use App\Models\CampaignContact;
use App\Http\Requests\CampaignRequest;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Jobs\ProcessCampaignJob;
use Illuminate\Support\Facades\Cache;
use Auth;

class CampaignController extends Controller
{
    public function campaignList(Request $request) 
    {
        $user = Auth::user();
        $campaigns = Campaign::with('contacts')->where('user_id', $user->id)->get();
        return Inertia::render('Campaigns/CampaignList', [
            'campaigns' => $campaigns,
            'status' => session('status'),
        ]);
    }

    public function campaignForm(Request $request) 
    {
        return Inertia::render('Campaigns/CampaignForm', [
            'status' => session('status'),
        ]);
    }

    public function viewCampaign($id) 
    {
        $campaign = Campaign::with('contacts')->where('id', $id)->first();
        return Inertia::render('Campaigns/CampaignView', [
            'campaign' => $campaign,
            'status' => session('status'),
        ]);
    }

    public function store(CampaignRequest $request)
    {
        try{
            $request->validate([
                'campaign_name' => 'required|string|max:255',
                'csv_file' => 'required|file|mimes:csv,txt',
            ]);
    
            $campaign = Campaign::create([
                'name' => $request->campaign_name,
                'user_id' => auth()->id(),
            ]);
    
            $data = $request->getCsvData();
    
            ProcessCampaignJob::dispatch($data, $campaign->id);
            return response()->json(['data' => $campaign, 'message' => 'Campaign created successfully!', 'status' => 200]);
        } catch (\Exception $e) {
            return response()->json(['data' => $e, 'message' => $e->getMessage(), 'status' => $e->getCode()]);
        }
    }

    public function getCampaignProcessingCount($id)
    {
        try{
            $cache_key = "campaign_{$id}_progress";
            $progress = Cache::get($cache_key, ['total' => 0, 'processed' => 0, 'status' => 'pending']);

            return response()->json(['data' => $progress, 'message' => 'Campaign Progress!', 'status' => 200]);
        } catch (\Exception $e) {
            return response()->json(['data' => $e, 'message' => $e->getMessage(), 'status' => $e->getCode()]);
        }
    }
}
