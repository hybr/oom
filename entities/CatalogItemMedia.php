<?php

namespace Entities;

/**
 * CatalogItemMedia Entity
 * Images, videos, and documents for catalog items
 */
class CatalogItemMedia extends BaseEntity
{
    protected ?int $item_id = null;
    protected ?string $media_type = null; // Image, Video, PDF, SpecSheet, 3D
    protected ?string $media_url = null;
    protected ?string $caption = null;
    protected bool $is_primary = false;

    // ENUM for media_type
    public const TYPE_IMAGE = 'Image';
    public const TYPE_VIDEO = 'Video';
    public const TYPE_PDF = 'PDF';
    public const TYPE_SPEC_SHEET = 'SpecSheet';
    public const TYPE_3D = '3D';

    public static function getTableName(): string
    {
        return 'catalog_item_media';
    }

    protected function getFillableAttributes(): array
    {
        return ['item_id', 'media_type', 'media_url', 'caption', 'is_primary'];
    }

    protected function getValidationRules(): array
    {
        return [
            'item_id' => ['required', 'numeric'],
            'media_type' => ['required'],
            'media_url' => ['required', 'max:500'],
        ];
    }

    /**
     * Get the catalog item
     */
    public function getCatalogItem(): ?CatalogItem
    {
        return CatalogItem::find($this->item_id);
    }

    /**
     * Get media by item
     */
    public static function getByItem(int $itemId): array
    {
        return static::where('item_id = :item_id', ['item_id' => $itemId]);
    }

    /**
     * Get images for an item
     */
    public static function getImagesByItem(int $itemId): array
    {
        return static::where('item_id = :item_id AND media_type = :type', [
            'item_id' => $itemId,
            'type' => self::TYPE_IMAGE
        ]);
    }

    /**
     * Get videos for an item
     */
    public static function getVideosByItem(int $itemId): array
    {
        return static::where('item_id = :item_id AND media_type = :type', [
            'item_id' => $itemId,
            'type' => self::TYPE_VIDEO
        ]);
    }

    /**
     * Get primary media for an item
     */
    public static function getPrimaryMediaByItem(int $itemId): ?CatalogItemMedia
    {
        $media = static::where('item_id = :item_id AND is_primary = 1', ['item_id' => $itemId], 1);
        return $media[0] ?? null;
    }

    /**
     * Set as primary media (unsets other primary media for the item)
     */
    public function setAsPrimary(?int $userId = null): bool
    {
        // Unset other primary media
        $sql = "UPDATE " . static::getTableName() . " SET is_primary = 0 WHERE item_id = :item_id AND deleted_at IS NULL";
        \App\Database::execute($sql, ['item_id' => $this->item_id]);

        // Set this as primary
        $this->is_primary = true;
        return $this->save($userId);
    }

    /**
     * Check if media is an image
     */
    public function isImage(): bool
    {
        return $this->media_type === self::TYPE_IMAGE;
    }

    /**
     * Check if media is a video
     */
    public function isVideo(): bool
    {
        return $this->media_type === self::TYPE_VIDEO;
    }

    /**
     * Get media file extension
     */
    public function getFileExtension(): string
    {
        return pathinfo($this->media_url, PATHINFO_EXTENSION);
    }

    /**
     * Get media by type
     */
    public static function getByType(string $type): array
    {
        return static::where('media_type = :type', ['type' => $type]);
    }

    /**
     * Count media for an item
     */
    public static function countByItem(int $itemId): int
    {
        return static::count('item_id = :item_id', ['item_id' => $itemId]);
    }

    /**
     * Count media by type for an item
     */
    public static function countByItemAndType(int $itemId, string $type): int
    {
        return static::count('item_id = :item_id AND media_type = :type', [
            'item_id' => $itemId,
            'type' => $type
        ]);
    }
}
