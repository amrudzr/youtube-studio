<?php

namespace PolosHermanoz\YoutubeStudio\Tests\Api;

use PHPUnit\Framework\TestCase;

class ApiTest extends TestCase
{
    private function callApi(string $action, array $postData): array
    {
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_GET['action'] = $action;
        $_POST = $postData;

        ob_start();
        require __DIR__ . '/../../api.php';
        $output = ob_get_clean();

        return json_decode($output, true) ?? [];
    }

    // TC-01: Upload Shorts
    public function test_api_upload_shorts()
    {
        $res = $this->callApi('upload_shorts', [
            'role' => 'Editor',
            'title' => 'Shorts Keren',
            'duration' => 59
        ]);
        $this->assertEquals('success', $res['status']);
    }

    // TC-02: Add Audio
    public function test_api_add_audio()
    {
        $res = $this->callApi('add_audio', [
            'track_name' => 'Calm Waves'
        ]);
        $this->assertEquals('success', $res['status']);
        $this->assertEquals('Calm Waves', $res['data']['audio']);
    }

    // TC-03: Start Stream
    public function test_api_start_stream()
    {
        $res = $this->callApi('start_stream', [
            'role' => 'Editor',
            'is_eligible' => 'true'
        ]);
        $this->assertEquals('success', $res['status']);
    }

    // TC-04: Approve Comment
    public function test_api_approve_comment()
    {
        $res = $this->callApi('approve_comment', [
            'role' => 'Editor',
            'comment_id' => 10
        ]);
        $this->assertEquals('success', $res['status']);
    }

    // TC-05: Playlist
    public function test_api_playlist()
    {
        $res = $this->callApi('add_to_playlist', [
            'playlist_name' => 'Liburan',
            'video_id' => 'v-2024'
        ]);
        $this->assertEquals('success', $res['status']);
        $this->assertContains('v-2024', $res['data']['videos']);
    }
}