<div class="flex flex-wrap items-center justify-between gap-y-6">
    <div class="w-full lg:w-5/12">
        <img
            class="-my-10 h-auto w-full"
            src="{{ custom_theme_url($item->image, true) }}"
            alt="{!! __($item->title) !!}"
            width="696"
            height="426"
        >
    </div>
    <div class="w-full lg:w-6/12">
        <h3 class="relative mb-10 text-4xl font-semibold tracking-normal">
            {!! __($item->title) !!}
            <svg
                class="-mb-10 inline-block -translate-y-full align-text-bottom"
                width="60"
                height="58"
                viewBox="0 0 60 58"
                fill="none"
                xmlns="http://www.w3.org/2000/svg"
            >
                <path
                    d="M57.5399 50.2632C47.5529 50.8316 37.2649 51.7008 27.6128 54.4787C25.8744 54.9786 26.4883 57.6413 28.2351 57.5304C38.0529 56.9154 47.8108 55.451 57.6402 54.8281C60.411 55.1258 60.8544 50.0744 57.5399 50.2632ZM33.9037 31.1061C31.1392 31.2968 28.5923 32.9318 26.106 34.1026C23.5598 35.3004 20.9863 36.4748 18.568 37.9211C16.7213 39.0242 18.2737 41.8515 20.2259 41.2222C23.1425 40.2836 25.9473 38.993 28.7521 37.764C31.1329 36.7213 33.565 35.9627 35.4332 34.1171C36.589 32.974 35.3781 31.0023 33.9037 31.1061ZM23.0385 4.33132C19.8617 8.71794 15.8012 12.5245 12.277 16.6447C9.13353 20.3226 6.29882 24.3661 2.58143 27.4789C1.49151 28.3949 0.0677951 26.9186 0.580655 25.7729C2.70452 21.0255 6.36616 16.9877 9.65991 13.0054C13.0024 8.96752 16.3161 4.71969 20.3764 1.38362C22.2418 -0.146591 24.2711 2.62904 23.0385 4.33132Z"
                    fill="#231F20"
                />
            </svg>
        </h3>
        <p class="text-xl leading-7">
            {!! __($item->description) !!}
        </p>
    </div>
</div>
