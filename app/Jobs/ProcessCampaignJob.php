<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Cache;
use App\Models\Campaign;
use App\Models\CampaignContact;
use Illuminate\Support\Facades\Mail;
use App\Mail\CampaignSuccessMail;

class ProcessCampaignJob implements ShouldQueue
{
    use Queueable;
    protected $campaign_data;
    protected $campaign_id;
    /**
     * Create a new job instance.
     */
    public function __construct($campaign_data, $campaign_id)
    {
        $this->campaign_data = $campaign_data;
        $this->campaign_id = $campaign_id;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            $total_data_count = count($this->campaign_data);
            $cache_key = "campaign_{$this->campaign_id}_progress";
            $processed_data = 0;
            Cache::put($cache_key, ['total' => $total_data_count, 'processed' => $processed_data, 'status' => 'processing']);
            Campaign::where('id', $this->campaign_id)->update([ 'status' => 'processing']);
            foreach ($this->campaign_data as $user) {
                CampaignContact::create([
                    'campaign_id' => $this->campaign_id,
                    'name' => $user[0],
                    'email' => $user[1],
                ]);
                $processed_data++;
                Cache::put($cache_key, ['total' => $total_data_count, 'processed' => $processed_data, 'status' => 'processing']);
                sleep(10);
            }
            Campaign::where('id', $this->campaign_id)->update([ 'status' => 'completed']);
            Cache::put($cache_key, ['total' => $total_data_count, 'processed' => $processed_data, 'status' => 'completed']);
            $camp = Campaign::where('id', $this->campaign_id)->first();
            $user = $camp->user;
            Mail::to($user->email)->send(new CampaignSuccessMail($camp, $user));
        } catch(\Exception $e) {
            Campaign::where('id', $this->campaign_id)->update([ 'status' => 'failed']);
        }
    }
}
