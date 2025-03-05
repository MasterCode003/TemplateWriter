<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Letter extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'subject',
        'letter_type',
        'content',
        'sender_name',
        'sender_position',
        'is_deleted',
    ];

    /**
     * Override the toArray method to reorder attributes.
     */
    public function toArray()
    {
        $array = parent::toArray();

        // Reorder keys to move 'id' to the beginning
        return [
            'id' => $this->id,
            'name' => $this->name,
            'subject' => $this->subject,
            'letter_type' => $this->letter_type,
            'content' => $this->content,
            'sender_name' => $this->sender_name,
            'sender_position' => $this->sender_position,
            'is_deleted' => $this->is_deleted,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
