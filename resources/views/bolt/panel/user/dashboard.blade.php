@php
    $plan = Auth::user()->activePlan();
    $plan_type = 'regular';
    // $team = Auth::user()->getAttribute('team');
    $teamManager = Auth::user()->getAttribute('teamManager');

    if ($plan != null) {
        $plan_type = strtolower($plan->plan_type);
    }

    $titlebar_links = [
        [
            'label' => 'All',
            'link' => '#all',
        ],
        [
            'label' => 'AI Assistant',
            'link' => '#all',
        ],
        [
            'label' => 'Your Plan',
            'link' => '#plan',
        ],
        [
            'label' => 'Team Members',
            'link' => '#team',
        ],
        [
            'label' => 'Recent',
            'link' => '#recent',
        ],
        [
            'label' => 'Documents',
            'link' => '#documents',
        ],
        [
            'label' => 'Templates',
            'link' => '#templates',
        ],
        [
            'label' => 'Overview',
            'link' => '#all',
        ],
    ];

@endphp

@extends('panel.layout.app', ['disable_tblr' => true, 'has_sidebar' => true])
@section('title', __('Dashboard'))
@section('titlebar_title')
    {{ __('Welcome') }}, {{ auth()->user()->name }}.
@endsection

@push('css')
    <style>
        @if (setting('announcement_background_color'))
            .lqd-card.lqd-announcement-card {
                background-color: {{ setting('announcement_background_color') }};
            }
        @endif
        @if (setting('announcement_background_image'))
            .lqd-card.lqd-announcement-card {
                background-image: url({{ setting('announcement_background_image') }});
            }
        @endif
        @if (setting('announcement_background_color_dark'))
            .theme-dark .lqd-card.lqd-announcement-card {
                background-color: {{ setting('announcement_background_color_dark') }};
            }
        @endif
        @if (setting('announcement_background_image_dark'))
            .theme-dark .lqd-card.lqd-announcement-card {
                background-image: url({{ setting('announcement_background_image_dark') }});
            }
        @endif
    </style>
@endpush

@section('content')
    <div class="lqd-titlebar-secondary mb-20 text-center lg:-mx-4">
        <div
            class="bg-cover bg-center px-4 pb-20 pt-8"
            style="background-image: url({{ custom_theme_url('assets/bg/titlebar-bg.jpg') }})"
        >
            <h1 class="m-0 flex items-center justify-center gap-3 text-center text-black">
                <svg
                    width="20"
                    height="20"
                    viewBox="0 0 20 20"
                    fill="currentColor"
                    xmlns="http://www.w3.org/2000/svg"
                >
                    <path
                        d="M15.8877 11.0742C10.6901 11.9289 9.76613 13.0146 9.07312 19.3441C9.05002 19.552 8.74972 19.552 8.72662 19.3441C8.03361 13.0146 7.1096 11.952 1.91203 11.0742C1.70413 11.0511 1.70413 10.7508 1.91203 10.7277C7.1096 9.87298 8.03361 8.81036 8.72662 2.48088C8.74972 2.27298 9.05002 2.27298 9.07312 2.48088C9.76613 8.81036 10.6901 9.84988 15.8877 10.7277C16.0725 10.7508 16.0725 11.028 15.8877 11.0742Z"
                    />
                    <path
                        d="M18.1053 3.77447C16.4883 4.09787 16.0263 4.62918 15.7491 6.5234C15.726 6.73131 15.4256 6.73131 15.4025 6.5234C15.1253 4.62918 14.6633 4.09787 13.0463 3.75137C12.8615 3.70517 12.8615 3.45106 13.0463 3.40486C14.6402 3.08146 15.1253 2.55015 15.4025 0.655927C15.4256 0.448024 15.726 0.448024 15.7491 0.655927C16.0263 2.55015 16.4883 3.08146 18.1053 3.42796C18.2901 3.47416 18.2901 3.75137 18.1053 3.77447Z"
                    />
                </svg>
                @lang('Hey, How can I help you?')
            </h1>
        </div>

        <div class="container relative z-2 -mt-10 flex flex-col">
            <div
                class="mx-auto flex w-full max-w-[650px] items-center gap-5 rounded-2xl bg-background/40 px-4 py-3 shadow-[0_4px_10px_hsl(0_0%_0%/4%)] backdrop-blur-2xl dark:shadow-[0_4px_20px_rgba(255,255,255,0.05)]">
                <x-header-search
                    class="w-full [&_.header-search-border]:rounded-2xl"
                    class:input="bg-background border-none h-11 placeholder:text-heading-foreground rounded-xl text-foreground focus:text-heading-foreground"
                    size="lg"
                    in-content
                />
                <x-button
                    class="group text-[12px] font-medium text-heading-foreground"
                    variant="link"
                    href="{{ $setting->feature_ai_advanced_editor ? LaravelLocalization::localizeUrl(route('dashboard.user.generator.index')) : LaravelLocalization::localizeUrl(route('dashboard.user.openai.list')) }}"
                >
                    <span class="sr-only">
                        @lang('Create a Blank Document')
                    </span>
                    <span
                        class="inline-flex size-[30px] items-center justify-center rounded-full bg-background shadow transition-all group-hover:scale-110 group-hover:bg-heading-foreground group-hover:text-header-background"
                    >
                        <x-tabler-plus class="size-4" />
                    </span>
                </x-button>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="flex flex-wrap justify-between gap-8 py-5">
            @if ($ongoingPayments != null)
                <div class="w-full">
                    @include('panel.user.finance.ongoingPayments')
                </div>
            @endif

            <div
                class="grid w-full grid-cols-1 gap-6 xl:grid-cols-2"
                id="all"
            >
                @if (setting('announcement_active', 0) && !auth()->user()->dash_notify_seen)
                    <div
                        class="lqd-announcement"
                        x-data="{ show: true }"
                        x-ref="announcement"
                    >
                        <script>
                            const announcementDismissed = localStorage.getItem("lqd-announcement-dismissed");
                            if (announcementDismissed) {
                                document.querySelector(".lqd-announcement").style.display = "none";
                            }
                        </script>

                        <x-card
                            class="lqd-announcement-card relative bg-cover bg-center"
                            class:body="pt-8"
                            size="none"
                            x-ref="announcementCard"
                        >
                            <div class="flex flex-wrap items-center justify-between gap-4">
                                <div class="px-10">
                                    <h3 class="mb-5 text-[21px]">
                                        @lang(setting('announcement_title', 'Welcome'))
                                    </h3>
                                    <p class="mb-8 text-base">
                                        @lang(setting('announcement_description', 'We are excited to have you here. Explore the marketplace to find the best AI models for your needs.'))
                                    </p>
                                    <div class="flex flex-wrap gap-2">
                                        <x-button
                                            class="font-medium"
                                            href="{{ setting('announcement_url', '#') }}"
                                        >
                                            <x-tabler-plus class="size-4" />
                                            {{ setting('announcement_button_text', 'Try it Now') }}
                                        </x-button>
                                        <x-button
                                            class="font-medium"
                                            href="javascript:void(0)"
                                            variant="ghost-shadow"
                                            hover-variant="danger"
                                            @click.prevent="{{ $app_is_demo ? 'toastr.info(\'This feature is disabled in Demo version.\')' : ' dismiss()' }}"
                                        >
                                            @lang('Dismiss')
                                        </x-button>
                                    </div>
                                </div>
                                <div class="w-full">
                                    @if (setting('announcement_image_dark'))
                                        <img
                                            class="announcement-img announcement-img-dark peer hidden shrink-0 dark:block"
                                            src="{{ setting('announcement_image_dark', '/upload/images/speaker.png') }}"
                                            alt="@lang(setting('announcement_title', 'Welcome to MagicAI!'))"
                                        >
                                    @endif
                                    <img
                                        class="announcement-img announcement-img-light shrink-0 dark:peer-[&.announcement-img-dark]:hidden"
                                        src="{{ setting('announcement_image', '/upload/images/speaker.png') }}"
                                        alt="@lang(setting('announcement_title', 'Welcome to MagicAI!'))"
                                    >
                                </div>
                            </div>
                        </x-card>
                    </div>
                @endif

                <x-card
                    class="w-full"
                    id="plan"
                    size="lg"
                >
                    @include('panel.user.finance.subscriptionStatus')
                </x-card>

                @if (showTeamFunctionality())
                    <x-card
                        class="w-full"
                        id="team"
                        size="lg"
                    >
                        @if ($team)
                            <p class="mb-6 text-[21px]/[1.33em] font-medium text-heading-foreground lg:w-9/12">
                                @lang('Add your team members’ email address to start collaborating.')
                            </p>
                            <figure class="mb-8">
                                <img
                                    class="mx-auto"
                                    width="137"
                                    height="116"
                                    src="{{ custom_theme_url('assets/img/team/team.png') }}"
                                    alt="Team"
                                >
                            </figure>
                            <form
                                class="flex flex-col gap-3"
                                action="{{ route('dashboard.user.team.invitation.store', $team->id) }}"
                                method="post"
                            >
                                @csrf
                                <input
                                    type="hidden"
                                    name="team_id"
                                    value="{{ $team?->id }}"
                                >
                                <x-forms.input
                                    class="min-h-12 rounded"
                                    id="email"
                                    size="lg"
                                    type="email"
                                    name="email"
                                    placeholder="{{ __('Email address') }}"
                                    required
                                >
                                    <x-slot:icon>
                                        <x-tabler-mail class="absolute end-3 top-1/2 size-5 -translate-y-1/2" />
                                    </x-slot:icon>
                                </x-forms.input>
                                @if ($app_is_demo)
                                    <x-button onclick="return toastr.info('This feature is disabled in Demo version.')">
                                        @lang('Invite Friends')
                                    </x-button>
                                @else
                                    <x-button
                                        data-name="{{ \App\Enums\Introduction::AFFILIATE_SEND }}"
                                        type="submit"
                                    >
                                        @lang('Invite Friends')
                                    </x-button>
                                @endif
                            </form>
                        @else
                            <h3 class="mb-6 text-[21px]">
                                {{ __('How it Works') }}
                            </h3>

                            <ol class="mb-12 flex flex-col gap-4 text-heading-foreground">
                                <li>
                                    <span class="me-2 inline-flex size-7 items-center justify-center rounded-full bg-primary/10 font-extrabold text-primary">
                                        1
                                    </span>
                                    {!! __('You <strong>send your invitation link</strong> to your friends.') !!}
                                </li>
                                <li>
                                    <span class="me-2 inline-flex size-7 items-center justify-center rounded-full bg-primary/10 font-extrabold text-primary">
                                        2
                                    </span>
                                    {!! __('<strong>They subscribe</strong> to a paid plan by using your refferral link.') !!}
                                </li>
                                <li>
                                    <span class="me-2 inline-flex size-7 items-center justify-center rounded-full bg-primary/10 font-extrabold text-primary">
                                        3
                                    </span>
									@if($is_onetime_commission)
										{!! __('From their first purchase, you will begin <strong>earning one-time commissions</strong>.') !!}
									@else
                                    	{!! __('From their first purchase, you will begin <strong>earning recurring commissions</strong>.') !!}
									@endif
								</li>
                            </ol>

                            <form
                                class="flex flex-col gap-3"
                                id="send_invitation_form"
                                onsubmit="return sendInvitationForm();"
                            >
                                <x-forms.input
                                    class:label="text-heading-foreground"
                                    id="to_mail"
                                    label="{{ __('Affiliate Link') }}"
                                    size="sm"
                                    type="email"
                                    name="to_mail"
                                    placeholder="{{ __('Email address') }}"
                                    required
                                >
                                    <x-slot:icon>
                                        <x-tabler-mail class="absolute end-3 top-1/2 size-5 -translate-y-1/2" />
                                    </x-slot:icon>
                                </x-forms.input>

                                <x-button
                                    class="w-full rounded-xl"
                                    id="send_invitation_button"
                                    type="submit"
                                    form="send_invitation_form"
                                >
                                    {{ __('Send') }}
                                </x-button>
                            </form>
                        @endif
                    </x-card>
                @endif

                <div
                    class="grow basis-full md:basis-0"
                    id="documents"
                >
                    <x-card size="none">
                        <x-slot:head>
                            <h2 class="m-0">
                                {{ __('Documents') }}
                            </h2>
                        </x-slot:head>
                        @foreach (Auth::user()->openai()->with('generator')->take(4)->get() as $entry)
                            @if ($entry->generator != null)
                                <x-documents.item :$entry />
                            @endif
                        @endforeach
                    </x-card>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    @includeFirst([
        'onboarding::include.introduction',
        'panel.admin.onboarding.include.introduction',
        'vendor.empty'
    ])
	@includeFirst([
        'onboarding-pro::include.introduction',
        'panel.admin.onboarding-pro.include.introduction',
        'vendor.empty'
    ])
    <script>
        function dismiss() {
            // localStorage.setItem('lqd-announcement-dismissed', true);
            document.querySelector(".lqd-announcement").style.display = "none";
            $.ajax({
                url: '{{ route('dashboard.user.dash_notify_seen') }}',
                type: "POST",
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    /* console.log(response); */
                }
            });
        }
    </script>
@endpush
