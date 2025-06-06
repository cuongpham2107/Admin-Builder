@php
use Filament\Forms\Components\Actions\Action;
@endphp
<x-dynamic-component
    :component="$getFieldWrapperView()"
    :field="$field"
>

    @php
        


        $containers = $getChildComponentContainers();

        $addAction = $getAction($getAddActionName());
        $cloneAction = $getAction($getCloneActionName());
        $deleteAction = $getAction($getDeleteActionName());
        $moveDownAction = $getAction($getMoveDownActionName());
        $moveUpAction = $getAction($getMoveUpActionName());
        $reorderAction = $getAction($getReorderActionName());
        $extraActions = $getExtraActions();
        $visibleExtraActions = [];

        $isAddable = $isAddable();
        $isCloneable = $isCloneable();
        $isCollapsible = $isCollapsible();
        $isDeletable = $isDeletable();
        $isReorderable = $isReorderable();
        $isReorderableWithButtons = $isReorderableWithButtons();
        $isReorderableWithDragAndDrop = $isReorderableWithDragAndDrop();

        $statePath = $getStatePath();

        $columnLabels = $getColumnLabels();
        $description = $getDescription();

        foreach ($extraActions as $extraAction) {
            $visibleExtraActions = array_filter(
                $extraActions,
                fn (Action $action): bool => $action->isVisible(),
            );
        }

    @endphp
    <div>
        <span class="text-sm text-gray-500 max-w-lg break-words block">
            {{ $description }}
        </span>
    </div>
    <div
        {{-- x-data="{ state: $wire.entangle('{{ $getStatePath() }}') }"  --}}
        x-data="{ isCollapsed: @js($isCollapsed()) }"
        x-on:repeater-collapse.window="$event.detail === '{{ $getStatePath() }}' && (isCollapsed = true)"
        x-on:repeater-expand.window="$event.detail === '{{ $getStatePath() }}' && (isCollapsed = false)"

        {{
            $attributes
                ->merge($getExtraAttributes(), escape: false)
                ->class(['bg-white rounded-xl relative dark:bg-gray-900'])
        }}
    >
    {{-- <div @class([
            'filament-tables-header',
            'flex items-center h-10 overflow-hidden border-b bg-gray-50 rounded-t-xl',
            'dark:bg-gray-800 dark:border-gray-700',
        ])>

            <div class="flex-1"></div>
            @if ($isCollapsible)
                <div>
                    <button
                        x-on:click="isCollapsed = !isCollapsed"
                        type="button"
                        @class([
                            'flex items-center justify-center flex-none w-10 h-10 text-gray-400 transition hover:text-gray-300',
                            'dark:text-gray-400 dark:hover:text-gray-500',
                        ])
                    >
                        <x-heroicon-s-minus-small class="w-4 h-4" x-show="! isCollapsed"/>

                        <span class="sr-only" x-show="! isCollapsed">
                            {{ __('forms::components.repeater.buttons.collapse_item.label') }}
                        </span>

                        <x-heroicon-s-plus-small class="w-4 h-4" x-show="isCollapsed" x-cloak/>

                        <span class="sr-only" x-show="isCollapsed" x-cloak>
                            {{ __('forms::components.repeater.buttons.expand_item.label') }}
                        </span>
                    </button>
                </div>
            @endif
        </div> --}}

        <div class="px-4{{ $isAddable? '' : ' py-2' }}">
            <table class="it-table-repeater w-full text-left rtl:text-right table-auto mx-4 my-2">
                <thead>
                    <tr>
                        @if($isReorderableWithDragAndDrop)
                            <th></th>
                        @endif
                        @foreach($columnLabels as $columnLabel)
                            @if($columnLabel['display'])
                            <th class="it-table-repeater-cell-label p-2 font-medium text-sm"
                            >
                                <span>
                                    {{ $columnLabel['name'] }}
                                </span>
                            </th>
                            @else
                            <th style="display: none"></th>
                            @endif
                        @endforeach

                        @if ($isReorderableWithButtons || $isCloneable || $isDeletable)
                        	<th></th>
						@endif
                    </tr>
                </thead>

                <tbody
                    @if($isReorderable)
                        {{-- :wire:end.stop="'mountFormComponentAction(\'' . $statePath . '\', \'reorder\', { items: $event.target.sortable.toArray() })'" --}}
                       x-sortable
                    @endif
                >

                    @foreach ($containers as $uuid => $item)

                        <tr
                            class="it-table-repeater-row"
                            {{-- x-on:repeater-collapse.window="$event.detail === '{{ $getStatePath() }}' && (isCollapsed = true)"
                            x-on:repeater-expand.window="$event.detail === '{{ $getStatePath() }}' && (isCollapsed = false)" --}}
                            wire:key="{{ $this->getId() }}.{{ $item->getStatePath() }}.{{ $field::class }}.item"
                            x-sortable-item="{{ $uuid }}"
                        >
                            <td>
                            @if ($isReorderableWithDragAndDrop || $isReorderableWithButtons)
                                        @if ($isReorderableWithDragAndDrop)
                                            <div x-sortable-handle>
                                                {{ $reorderAction }}
                                            </div>
                                        @endif

                                        @if ($isReorderableWithButtons)
                                            <div
                                                class="flex items-center justify-center"
                                            >
                                                {{ $moveUpAction(['item' => $uuid])->disabled($loop->first) }}
                                            </div>

                                            <div
                                                class="flex items-center justify-center"
                                            >
                                                {{ $moveDownAction(['item' => $uuid])->disabled($loop->last) }}
                                            </div>
                                        @endif

                                    @endif
                            </td>
                            @foreach($item->getComponents() as $component)
                            <td
                                class="it-table-repeater-cell  px-1 py-2 align-center @if($component instanceof \Filament\Forms\Components\Checkbox) justify-items-center @endif"
                                @if($component->isHidden() || ($component instanceof \Filament\Forms\Components\Hidden))style="display:none"@endif
                            >
                               {{ $component }}
                            </td>
                            @endforeach

                            @if ($isReorderableWithDragAndDrop || $isReorderableWithButtons || filled($itemLabel) || $isCloneable || $isDeletable || $isCollapsible)
								<td class="flex flex-row items-center gap-x-2 py-4 max-w-20">
                                   
                                    @if ($isCloneable || $isDeletable )
                                        @if ($cloneAction->isVisible())
                                            <div>
                                                {{ $cloneAction(['item' => $uuid]) }}
                                            </div>
                                        @endif

                                        @if ($isDeletable)
                                            <div>
                                                {{ $deleteAction(['item' => $uuid]) }}
                                            </div>
                                        @endif

                                    @endif

                                </td>
							@endif
                        </tr>
                    @endforeach
                </tbody>

            </table>

            {{-- <div class="p-2 text-xs text-center text-gray-400" x-show="isCollapsed" x-cloak>
                {{ __('filament-table-repeater::components.table-repeater.collapsed') }}
            </div> --}}
        </div>

        @if($isAddable || filled($visibleExtraActions))
                
            <ul class="relative flex justify-center">
                <li class="py-2 px-1">
                    {{ $addAction }}
                </li>
                @if (filled($visibleExtraActions))   
                    @foreach ($visibleExtraActions as $extraAction)
                        <li class="py-2 px-1">
                            {{ ($extraAction) }}
                        </li>
                    @endforeach
                @endif
            </ul>
        @endif
    </div>

</x-dynamic-component>