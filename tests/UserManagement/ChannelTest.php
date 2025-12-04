<?php

namespace PolosHermanoz\YoutubeStudio\Tests\AudioLibrary;

use PHPUnit\Framework\TestCase;
use PolosHermanoz\YoutubeStudio\UserManagement\User;
use PolosHermanoz\YoutubeStudio\UserManagement\Channel;

class ChannelTest extends TestCase
{
    private Channel $channel;

    protected function setUp(): void
    {
        $this->channel = new Channel('owner@example.com');
    }

    public function testInviteValidUser(): void
    {
        $result = $this->channel->invite('editor@example.com', 'Editor');
        $this->assertTrue($result);

        $members = $this->channel->getMembers();
        $this->assertArrayHasKey('editor@example.com', $members);
        $this->assertEquals('Editor', $members['editor@example.com']->getRole());
    }

    public function testChangeUserRole(): void
    {
        $this->channel->invite('viewer@example.com', 'Viewer');
        $result = $this->channel->changeRole('viewer@example.com', 'Manager');

        $this->assertTrue($result);
        $this->assertEquals('Manager', $this->channel->getMembers()['viewer@example.com']->getRole());
    }

    public function testRemoveAccess(): void
    {
        $this->channel->invite('test@example.com', 'Editor');
        $result = $this->channel->removeAccess('test@example.com');

        $this->assertTrue($result);
        $this->assertArrayNotHasKey('test@example.com', $this->channel->getMembers());
    }

    public function testInvalidEmailThrowsException(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->channel->invite('invalid-email', 'Viewer');
    }

    public function testInvalidRoleThrowsException(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->channel->invite('valid@example.com', 'SuperAdmin');
    }
}
