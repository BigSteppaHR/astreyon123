@php
    $items = app(\App\Services\Common\MenuService::class)->generate();

    $isAdmin = \Auth::user()?->isAdmin();

@endphp

@foreach ($items as $item)
    @php
        $theme = $item['type'] === 'item' ? 'classic' : 'default';
    @endphp
    @if (data_get($item, 'is_admin'))
        @if ($isAdmin)
            @if (data_get($item, 'show_condition', true) && data_get($item, 'is_active'))
                @if ($item['children_count'])
                    @includeIf('default.components.navbar.partials.types.item-dropdown')
                @else
                    @includeIf($theme . '.components.navbar.partials.types.' . $item['type'])
                @endif
            @endif
        @endif
    @else
        @if (data_get($item, 'show_condition', true) && data_get($item, 'is_active'))
            @if ($item['children_count'])
                @includeIf('default.components.navbar.partials.types.item-dropdown')
            @else
                @includeIf($theme . '.components.navbar.partials.types.' . $item['type'])
            @endif
        @endif
    @endif
@endforeach

{{-- Admin menu items --}}
@if (Auth::user()->isAdmin())
    {{-- <x-navbar.item>
        <x-navbar.link
            label="{{ __('ChatBot') }}"
            href="dashboard.chatbot.index"
            icon="tabler-message-2-code"
            active-condition="{{ activeRoute('dashboard.chatbot.*') }}"
            new
        />
    </x-navbar.item> --}}

    @if ($app_is_not_demo)
        <x-navbar.item>
            <x-navbar.link
                label="{{ __('Premium Support') }}"
                href="#"
                icon="tabler-diamond"
                trigger-type="modal"
            >
                <x-slot:modal>
                    @includeIf('premium-support.index')
                </x-slot:modal>
            </x-navbar.link>
        </x-navbar.item>
    @endif
@endif

<x-navbar.item>
    <x-navbar.divider />
</x-navbar.item>
