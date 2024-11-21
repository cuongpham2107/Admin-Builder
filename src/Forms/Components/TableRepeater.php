<?php

namespace CuongPham2107\AdminBuilder\Forms\Components;

use Filament\Forms\Components\Repeater;

class TableRepeater extends Repeater
{
    use Concerns\HasExtraActions;

    protected string $view = 'admin-builder::forms.components.table-repeater';

    protected ?string $description = null;

    protected ?array $columnLabels = [];

    protected function setUp(): void
    {
        $this->columnSpanFull();
        parent::setUp();

        $this->registerActions([
            fn (TableRepeater $component): array => $component->getExtraActions(),
        ]);
    }

    public function getColumnLabels(): ?array
    {
        $this->setColumnLabels();

        return $this->columnLabels;
    }

    protected function setColumnLabels(): void
    {
        $components = $this->getChildComponents();

        foreach ($components as $component) {
            $this->columnLabels[] = [
                'component' => $component->getName(),
                'name' => $component->getLabel(),
                'display' => ($component->isHidden() || ($component instanceof \Filament\Forms\Components\Hidden)) ? false : true,
            ];
        }
    }

    public function description(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function childComponents(array | \Closure $components): static
    {
        foreach ($components as $component) {
            $component->hiddenLabel(); //Disable Label, only show Inputs inside table
            $this->childComponents[] = $component;
        }

        return $this;
    }
}
