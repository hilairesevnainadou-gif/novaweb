<?php
// app/Http/Controllers/Webhook/GithubWebhookController.php

namespace App\Http\Controllers\Webhook;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class GithubWebhookController extends Controller
{
    public function handle(Request $request)
    {
        $event = $request->header('X-GitHub-Event');
        $payload = $request->all();

        switch ($event) {
            case 'push':
                $this->handlePush($payload);
                break;
            case 'pull_request':
                $this->handlePullRequest($payload);
                break;
            default:
                Log::info('Unhandled GitHub webhook event', ['event' => $event]);
        }

        return response()->json(['status' => 'success']);
    }

    private function handlePush($payload)
    {
        // Logique pour gérer les pushes
        Log::info('GitHub push event', [
            'repository' => $payload['repository']['name'],
            'commits' => count($payload['commits'])
        ]);
    }

    private function handlePullRequest($payload)
    {
        // Logique pour gérer les pull requests
        Log::info('GitHub pull request event', [
            'repository' => $payload['repository']['name'],
            'action' => $payload['action']
        ]);
    }
}
