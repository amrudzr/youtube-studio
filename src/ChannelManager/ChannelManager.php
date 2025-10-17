<?php

namespace PolosHermanoz\YoutubeStudio\ChannelManager;

/**
 * Kelas untuk mengelola Pengaturan Kanal (Channel)
 */
class ChannelManager
{
    private array $channelData;

    public function __construct()
    {
        $this->channelData = [
            'id' => 'UC_User123',
            'name' => 'Kanal Lama',
            'description' => 'Deskripsi lama kanal ini.',
            'layout' => 'default', // Contoh: 'default', 'featured', 'simple'
            'branding' => [
                'profile_picture' => 'url/old_profile.jpg',
                'banner' => 'url/old_banner.jpg',
            ],
            'privacy' => 'public', // public, private, unlisted
            'country' => 'ID',
            'default_language' => 'id',
            'created_at' => '2024-01-01 00:00:00',
            'updated_at' => '2024-01-01 00:00:00'
        ];
    }

    /**
     * Mengubah informasi dasar kanal (Nama dan Deskripsi).
     */
    public function updateBasicInfo(string $newName, string $newDescription): bool
    {
        if (empty(trim($newName)) || empty(trim($newDescription))) {
            return false;
        }
        
        $this->channelData['name'] = $newName;
        $this->channelData['description'] = $newDescription;
        $this->channelData['updated_at'] = date('Y-m-d H:i:s');
        
        return true;
    }

    /**
     * Mengubah tata letak (layout) kanal.
     */
    public function updateLayout(string $newLayout): bool
    {
        $validLayouts = ['default', 'featured', 'simple'];
        if (!in_array($newLayout, $validLayouts)) {
            return false;
        }
        
        $this->channelData['layout'] = $newLayout;
        $this->channelData['updated_at'] = date('Y-m-d H:i:s');
        
        return true;
    }

    /**
     * Mengubah branding kanal (Foto Profil atau Banner).
     */
    public function updateBranding(string $type, string $url): bool
    {
        $validTypes = ['profile_picture', 'banner'];
        if (!in_array($type, $validTypes) || empty(trim($url))) {
            return false;
        }
        
        $this->channelData['branding'][$type] = $url;
        $this->channelData['updated_at'] = date('Y-m-d H:i:s');
        
        return true;
    }

    /**
     * Mengubah pengaturan privasi kanal.
     */
    public function updatePrivacy(string $privacy): bool
    {
        $validPrivacy = ['public', 'private', 'unlisted'];
        if (!in_array($privacy, $validPrivacy)) {
            return false;
        }
        
        $this->channelData['privacy'] = $privacy;
        $this->channelData['updated_at'] = date('Y-m-d H:i:s');
        
        return true;
    }

    /**
     * Mengubah informasi lokalisasi kanal.
     */
    public function updateLocalization(string $country, string $language): bool
    {
        if (empty(trim($country)) || empty(trim($language))) {
            return false;
        }
        
        $this->channelData['country'] = $country;
        $this->channelData['default_language'] = $language;
        $this->channelData['updated_at'] = date('Y-m-d H:i:s');
        
        return true;
    }

    /**
     * Mendapatkan semua data kanal.
     */
    public function getChannelData(): array
    {
        return $this->channelData;
    }

    /**
     * Mendapatkan data spesifik kanal.
     */
    public function getChannelInfo(string $key): mixed
    {
        $keys = explode('.', $key);
        $value = $this->channelData;
        
        foreach ($keys as $k) {
            if (!isset($value[$k])) {
                return null;
            }
            $value = $value[$k];
        }
        
        return $value;
    }

    /**
     * Memvalidasi URL gambar untuk branding.
     */
    public function isValidImageUrl(string $url): bool
    {
        $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        $path = parse_url($url, PHP_URL_PATH);
        $extension = pathinfo($path, PATHINFO_EXTENSION);
        
        return in_array(strtolower($extension), $allowedExtensions);
    }

    /**
     * Reset pengaturan kanal ke nilai default.
     */
    public function resetToDefault(): void
    {
        $this->channelData['name'] = 'Kanal Lama';
        $this->channelData['description'] = 'Deskripsi lama kanal ini.';
        $this->channelData['layout'] = 'default';
        $this->channelData['branding'] = [
            'profile_picture' => 'url/old_profile.jpg',
            'banner' => 'url/old_banner.jpg',
        ];
        $this->channelData['privacy'] = 'public';
        $this->channelData['updated_at'] = date('Y-m-d H:i:s');
    }

    /**
     * Mendapatkan ringkasan informasi kanal.
     */
    public function getChannelSummary(): array
    {
        return [
            'name' => $this->channelData['name'],
            'description' => substr($this->channelData['description'], 0, 100) . 
                           (strlen($this->channelData['description']) > 100 ? '...' : ''),
            'layout' => $this->channelData['layout'],
            'privacy' => $this->channelData['privacy'],
            'last_updated' => $this->channelData['updated_at']
        ];
    }
}