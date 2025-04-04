<header
    @class([
        'site-header group/header relative z-50 text-white transition-[background,shadow]',
    ])
    x-data="{ navOffsetTop: $refs.navbar.offsetTop - parseInt(getComputedStyle($refs.navbar).marginTop, 10), isSticky: false }"
    x-init="window.scrollY > navOffsetTop && (isSticky = true)"
    @resize.window.debounce.500ms="navOffsetTop = $refs.navbar.offsetTop - parseInt(getComputedStyle($refs.navbar).marginTop, 10)"
    @scroll.window="window.scrollY > navOffsetTop ? (isSticky = true) : (isSticky = false)"
    :class="{ 'lqd-is-sticky': isSticky }"
>
    @includeWhen($fSectSettings->preheader_active, 'landing-page.header.preheader')

    <div
        class="hidden"
        x-ref="navbar-placeholder"
        style="height: var(--header-height)"
        :class="{ 'hidden': !isSticky }"
    ></div>

    <nav
        class="site-header-nav relative flex items-center justify-between px-7 py-6 text-xs/tight opacity-0 transition-all duration-300 group-[.lqd-is-sticky]/header:fixed group-[.lqd-is-sticky]/header:top-0 group-[.lqd-is-sticky]/header:w-full group-[&.lqd-is-sticky]/header:bg-black group-[.page-loaded]/body:opacity-100 group-[&.lqd-is-sticky]/header:shadow-[0_4px_20px_rgba(0,0,0,0.03)] max-sm:px-2"
        id="frontend-local-navbar"
        x-ref="navbar"
        x-init="document.body.style.setProperty('--header-height', $refs.navbar.offsetHeight + 'px')"
    >
        <a
            class="site-logo relative basis-1/3 max-lg:basis-1/3"
            href="{{ route('index') }}"
        >
            @if (isset($setting->logo_sticky))
                <img
                    class="peer absolute start-0 top-1/2 -translate-y-1/2 translate-x-3 opacity-0 transition-all group-[.lqd-is-sticky]/header:translate-x-0 group-[.lqd-is-sticky]/header:opacity-100"
                    src="{{ custom_theme_url($setting->logo_sticky_path, true) }}"
                    @if (isset($setting->logo_sticky_2x_path)) srcset="/{{ $setting->logo_sticky_2x_path }} 2x" @endif
                    alt="{{ custom_theme_url($setting->site_name) }} logo"
                >
            @endif
            <img
                class="transition-all group-[.lqd-is-sticky]/header:peer-first:translate-x-2 group-[.lqd-is-sticky]/header:peer-first:opacity-0"
                src="{{ custom_theme_url($setting->logo_path, true) }}"
                @if (isset($setting->logo_2x_path)) srcset="/{{ $setting->logo_2x_path }} 2x" @endif
                alt="{{ $setting->site_name }} logo"
                x-on:load="document.body.style.setProperty('--header-height', $refs.navbar.offsetHeight + 'px')"
            >
        </a>
        <div
            class="site-nav-container basis-1/3 transition-all max-lg:absolute max-lg:right-0 max-lg:top-full max-lg:max-h-0 max-lg:w-full max-lg:overflow-hidden max-lg:bg-black max-lg:text-white [&.lqd-is-active]:max-lg:max-h-[calc(100vh-150px)]">
            <div class="max-lg:max-h-[inherit] max-lg:overflow-y-scroll">
                <ul
                    class="textxs flex items-center justify-center gap-14 whitespace-nowrap text-center font-medium uppercase max-xl:gap-10 max-lg:flex-col max-lg:items-start max-lg:gap-5 max-lg:p-10">
                    @php
                        $setting->menu_options = $setting->menu_options
                            ? $setting->menu_options
                            : '[{"title": "Home","url": "#banner","target": false},{"title": "Features","url": "#features","target": false},{"title": "How it Works","url": "#how-it-works","target": false},{"title": "Testimonials","url": "#testimonials","target": false},{"title": "Pricing","url": "#pricing","target": false},{"title": "FAQ","url": "#faq","target": false}]';
                        $menu_options = json_decode($setting->menu_options, true);
                        foreach ($menu_options as $menu_item) {
                            printf(
                                '
								<li>
									<a href="%1$s" target="%3$s" class="opacity-40 transition-all hover:opacity-100 [&.active]:opacity-100">
										%2$s
									</a>
								</li>
								',
                                Route::currentRouteName() != 'index' ? url('/') . $menu_item['url'] : $menu_item['url'],
                                __($menu_item['title']),
                                $menu_item['target'] === false ? '_self' : '_blank',
                            );
                        }
                    @endphp
                </ul>
                @if (count(explode(',', $settings_two->languages)) > 1)
                    <div class="group relative -mt-3 block border-t border-white/5 px-10 pb-5 pt-6 md:hidden">
                        <p class="mb-3 flex items-center gap-2">
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                width="22"
                                height="22"
                                viewBox="0 0 24 24"
                                stroke-width="1.5"
                                stroke="currentColor"
                                fill="none"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                            >
                                <path d="M3 12a9 9 0 1 0 18 0a9 9 0 0 0 -18 0"></path>
                                <path d="M3.6 9h16.8"></path>
                                <path d="M3.6 15h16.8"></path>
                                <path d="M11.5 3a17 17 0 0 0 0 18"></path>
                                <path d="M12.5 3a17 17 0 0 1 0 18"></path>
                            </svg>
                            {{ __('Languages') }}
                        </p>
                        @foreach (LaravelLocalization::getSupportedLocales() as $localeCode => $properties)
                            @if (in_array($localeCode, explode(',', $settings_two->languages)))
                                <a
                                    class="block border-b border-black border-opacity-5 py-3 transition-colors last:border-none hover:bg-black hover:bg-opacity-5"
                                    href="{{ LaravelLocalization::getLocalizedURL($localeCode, null, [], true) }}"
                                    rel="alternate"
                                    hreflang="{{ $localeCode }}"
                                >{{ country2flag(substr($properties['regional'], strrpos($properties['regional'], '_') + 1)) }}
                                    {{ $properties['native'] }}</a>
                            @endif
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
        <div class="flex basis-1/3 justify-end gap-2 max-lg:basis-2/3">
            @if (count(explode(',', $settings_two->languages)) > 1)
                <div class="group relative hidden md:block">
                    <button
                        class="size-10 inline-flex items-center justify-center rounded-full border-2 border-white/10 transition-colors before:absolute before:end-0 before:top-full before:h-4 before:w-full group-hover:border-opacity-100 group-hover:bg-primary group-hover:text-primary-foreground"
                    >
                        <x-tabler-world
                            class="size-6"
                            stroke-width="1.5"
                        />
                    </button>
                    <div
                        class="pointer-events-none absolute end-0 top-[calc(100%+1rem)] min-w-[145px] translate-y-2 overflow-hidden rounded-md bg-black text-white opacity-0 shadow-lg transition-all group-hover:pointer-events-auto group-hover:translate-y-0 group-hover:opacity-100">
                        @foreach (LaravelLocalization::getSupportedLocales() as $localeCode => $properties)
                            @if (in_array($localeCode, explode(',', $settings_two->languages)))
                                <a
                                    class="block border-b border-white/5 px-3 py-3 transition-colors last:border-none hover:bg-white/10"
                                    href="{{ LaravelLocalization::getLocalizedURL($localeCode, null, [], true) }}"
                                    rel="alternate"
                                    hreflang="{{ $localeCode }}"
                                >{{ country2flag(substr($properties['regional'], strrpos($properties['regional'], '_') + 1)) }}
                                    {{ $properties['native'] }}</a>
                            @endif
                        @endforeach
                    </div>
                </div>
            @endif

            @auth
                <div class="mx-3">
                    <a
                        class="relative inline-flex items-center rounded-lg border-2 border-white/10 bg-white/[2%] px-4 py-2 font-medium text-white transition-all duration-300 hover:scale-105 hover:border-primary hover:bg-primary hover:text-primary-foreground hover:shadow-lg hover:shadow-primary/5"
                        href="{{ LaravelLocalization::localizeUrl(route('dashboard.index')) }}"
                    >
                        {!! __('Dashboard') !!}

                        <x-outline-glow class="[--outline-glow-w:2px]" />
                    </a>
                </div>
            @else
                <a
                    class="relative inline-flex items-center rounded-lg border-2 border-white/10 px-4 py-2 font-medium text-white transition-all duration-300 hover:scale-105 hover:border-primary hover:bg-primary hover:text-primary-foreground hover:shadow-lg hover:shadow-primary/5"
                    href="{{ LaravelLocalization::localizeUrl(route('login')) }}"
                >
                    {!! __($fSetting->sign_in) !!}
                </a>
                <a
                    class="relative inline-flex items-center rounded-lg border-2 border-white/10 bg-white/[2%] px-4 py-2 font-medium text-white transition-all duration-300 hover:scale-105 hover:border-primary hover:bg-primary hover:text-primary-foreground hover:shadow-lg hover:shadow-primary/5"
                    href="{{ LaravelLocalization::localizeUrl(route('register')) }}"
                >
                    {!! __($fSetting->join_hub) !!}

                    <x-outline-glow class="[--outline-glow-w:2px]" />
                </a>
            @endauth

            <button class="mobile-nav-trigger size-10 group flex shrink-0 items-center justify-center rounded-full bg-white/10 lg:hidden">
                <span class="flex w-4 flex-col gap-1">
                    @for ($i = 0; $i <= 1; $i++)
                        <span
                            class="inline-flex h-[2px] w-full bg-white transition-transform first:origin-left last:origin-right group-[&.lqd-is-active]:first:-translate-y-[2px] group-[&.lqd-is-active]:first:translate-x-[3px] group-[&.lqd-is-active]:first:rotate-45 group-[&.lqd-is-active]:last:-translate-x-[2px] group-[&.lqd-is-active]:last:-translate-y-[8px] group-[&.lqd-is-active]:last:-rotate-45"
                        ></span>
                    @endfor
                </span>
            </button>
        </div>
    </nav>

    @includeWhen($fSetting->floating_button_active, 'landing-page.header.floating-button')
</header>

@includeWhen($app_is_demo, 'landing-page.header.envato-link')

@includeWhen(in_array($settings_two->chatbot_status, ['frontend', 'both']) &&
        ($settings_two->chatbot_login_require == false || ($settings_two->chatbot_login_require == true && auth()->check())),
    'panel.chatbot.widget',
    ['page' => 'landing-page']
)
