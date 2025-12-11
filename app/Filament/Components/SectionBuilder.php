<?php

namespace App\Filament\Components;

use Filament\Forms\Components\Field;

class SectionBuilder extends Field
{
    protected string $view = 'filament.components.section-builder';

    protected array $sectionTypes = [
        'paragraph_text' => 'Paragraph Text',
        'heading' => 'Heading',
        'images' => 'Images',
        'list' => 'List',
        'q_multiple_choice' => 'Multiple Choice Question',
        'q_true_false' => 'True/False Question',
        'quote' => 'Quote',
        'video' => 'Video',
    ];

    public function sectionTypes(array $sectionTypes): static
    {
        $this->sectionTypes = $sectionTypes;
        return $this;
    }

    public function getSectionTypes(): array
    {
        return $this->sectionTypes;
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->dehydrateStateUsing(function ($state) {
            if (is_array($state)) {
                // Clean up the data structure before saving
                return array_values(array_filter($state, function ($section) {
                    return isset($section['type']) && !empty($section['type']);
                }));
            }
            
            return $state;
        });
    }
}