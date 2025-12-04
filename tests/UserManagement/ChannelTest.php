<?php

namespace PolosHermanoz\YoutubeStudio\Tests\UserManagement;

use PHPUnit\Framework\TestCase;
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
        $this->channel->changeRole('viewer@example.com', 'Manager');

        $members = $this->channel->getMembers();
        $this->assertEquals('Manager', $members['viewer@example.com']->getRole());
    }

    public function testRemoveUser(): void
    {
        $this->channel->invite('test@example.com', 'Editor');
        $this->channel->removeAccess('test@example.com');

        $members = $this->channel->getMembers();
        $this->assertArrayNotHasKey('test@example.com', $members);
    }

    public function testInvalidEmailThrowsException(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->channel->invite('invalid', 'Viewer');
    }

    public function testInvalidRole(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->channel->invite('valid@example.com', 'SuperAdmin');
    }

    public function testDuplicateInviteThrows(): void
    {
        $this->channel->invite('dupe@example.com', 'Viewer');

        $this->expectException(\RuntimeException::class);
        $this->channel->invite('dupe@example.com', 'Editor');
    }

    public function testOwnerCannotBeRemoved(): void
    {
        $this->expectException(\RuntimeException::class);
        $this->channel->removeAccess('owner@example.com');
    }

    public function testOwnerRoleCannotChange(): void
    {
        $this->expectException(\RuntimeException::class);
        $this->channel->changeRole('owner@example.com', 'Editor');
    }

    public function testRemoveNonexistentUser(): void
    {
        $this->expectException(\RuntimeException::class);
        $this->channel->removeAccess('ghost@example.com');
    }

    public function testChangeRoleForNonexistentUser(): void
    {
        $this->expectException(\RuntimeException::class);
        $this->channel->changeRole('ghost@example.com', 'Manager');
    }

    public function testChangingToSameRoleIsAllowed(): void
    {
        $this->channel->invite('sam@example.com', 'Viewer');
        $result = $this->channel->changeRole('sam@example.com', 'Viewer');

        $this->assertTrue($result);
    }
}