<?php

namespace PolosHermanoz\YoutubeStudio\Tests\ChannelManager;

use PHPUnit\Framework\TestCase;
use PolosHermanoz\YoutubeStudio\ChannelManager\ChannelManager;

class ChannelManagerTest extends TestCase
{
    private ChannelManager $channelManager;

    protected function setUp(): void
    {
        $this->channelManager = new ChannelManager();
    }

    /**
     * Test mengubah informasi dasar kanal berhasil.
     */
    public function test_update_basic_info_successfully(): void
    {
        $result = $this->channelManager->updateBasicInfo(
            'Kanal Baru Saya', 
            'Deskripsi baru untuk kanal saya'
        );
        
        $this->assertTrue($result);
        
        $data = $this->channelManager->getChannelData();
        $this->assertSame('Kanal Baru Saya', $data['name']);
        $this->assertSame('Deskripsi baru untuk kanal saya', $data['description']);
    }

    /**
     * Test gagal mengubah informasi dasar dengan nama kosong.
     */
    public function test_update_basic_info_with_empty_name_returns_false(): void
    {
        $result = $this->channelManager->updateBasicInfo('', 'Deskripsi valid');
        
        $this->assertFalse($result);
    }

    /**
     * Test mengubah layout kanal berhasil.
     */
    public function test_update_layout_successfully(): void
    {
        $result = $this->channelManager->updateLayout('featured');
        
        $this->assertTrue($result);
        $this->assertSame('featured', $this->channelManager->getChannelData()['layout']);
    }

    /**
     * Test gagal mengubah layout dengan nilai tidak valid.
     */
    public function test_update_layout_with_invalid_value_returns_false(): void
    {
        $result = $this->channelManager->updateLayout('invalid_layout');
        
        $this->assertFalse($result);
    }

    /**
     * Test mengubah branding profile picture berhasil.
     */
    public function test_update_branding_profile_picture_successfully(): void
    {
        $result = $this->channelManager->updateBranding(
            'profile_picture', 
            'https://example.com/new_profile.jpg'
        );
        
        $this->assertTrue($result);
        
        $data = $this->channelManager->getChannelData();
        $this->assertSame(
            'https://example.com/new_profile.jpg', 
            $data['branding']['profile_picture']
        );
    }

    /**
     * Test mengubah branding banner berhasil.
     */
    public function test_update_branding_banner_successfully(): void
    {
        $result = $this->channelManager->updateBranding(
            'banner', 
            'https://example.com/new_banner.png'
        );
        
        $this->assertTrue($result);
        
        $data = $this->channelManager->getChannelData();
        $this->assertSame(
            'https://example.com/new_banner.png', 
            $data['branding']['banner']
        );
    }

    /**
     * Test gagal mengubah branding dengan tipe tidak valid.
     */
    public function test_update_branding_with_invalid_type_returns_false(): void
    {
        $result = $this->channelManager->updateBranding('invalid_type', 'https://example.com/image.jpg');
        
        $this->assertFalse($result);
    }

    /**
     * Test mengubah pengaturan privasi kanal.
     */
    public function test_update_privacy_successfully(): void
    {
        $result = $this->channelManager->updatePrivacy('private');
        
        $this->assertTrue($result);
        $this->assertSame('private', $this->channelManager->getChannelData()['privacy']);
    }

    /**
     * Test mendapatkan informasi spesifik kanal.
     */
    public function test_get_channel_info_successfully(): void
    {
        $name = $this->channelManager->getChannelInfo('name');
        $profilePicture = $this->channelManager->getChannelInfo('branding.profile_picture');
        
        $this->assertSame('Kanal Lama', $name);
        $this->assertSame('url/old_profile.jpg', $profilePicture);
    }

    /**
     * Test validasi URL gambar.
     */
    public function test_is_valid_image_url(): void
    {
        $this->assertTrue($this->channelManager->isValidImageUrl('https://example.com/image.jpg'));
        $this->assertTrue($this->channelManager->isValidImageUrl('https://example.com/image.png'));
        $this->assertFalse($this->channelManager->isValidImageUrl('https://example.com/image.pdf'));
    }

    /**
     * Test reset pengaturan kanal ke default.
     */
    public function test_reset_to_default(): void
    {
        // Ubah beberapa pengaturan terlebih dahulu
        $this->channelManager->updateBasicInfo('Nama Baru', 'Deskripsi Baru');
        $this->channelManager->updateLayout('featured');
        
        // Reset ke default
        $this->channelManager->resetToDefault();
        
        $data = $this->channelManager->getChannelData();
        $this->assertSame('Kanal Lama', $data['name']);
        $this->assertSame('default', $data['layout']);
    }

    /**
     * Test mendapatkan ringkasan kanal.
     */
    public function test_get_channel_summary(): void
    {
        $summary = $this->channelManager->getChannelSummary();
        
        $this->assertArrayHasKey('name', $summary);
        $this->assertArrayHasKey('description', $summary);
        $this->assertArrayHasKey('layout', $summary);
        $this->assertArrayHasKey('privacy', $summary);
        $this->assertArrayHasKey('last_updated', $summary);
    }
}