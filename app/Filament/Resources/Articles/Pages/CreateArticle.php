<?php

namespace App\Filament\Resources\Articles\Pages;

use App\Filament\Resources\Articles\ArticleResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateArticle extends CreateRecord
{
    protected static string $resource = ArticleResource::class;

    protected function handleRecordCreate(array $data): Model
    {
        // If content is present in the form data, save it to the content field
        if (isset($data['content'])) {
            $content = $data['content'];
            unset($data['content']);
        } else {
            $content = [];
        }

        // Create the record without content first
        $record = parent::handleRecordCreate($data);

        // Then update with content
        if (!empty($content)) {
            $record->content = $content;
            $record->save();
        }

        return $record;
    }
}