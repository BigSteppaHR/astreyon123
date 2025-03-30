<div class="col-md-12">
	<div class="mb-3">
		<x-card
			class="w-full"
			size="sm"
		>

			<x-forms.input
				id="realtime_voice_chat"
				type="checkbox"
				switcher
				type="checkbox"
				:checked="setting('realtime_voice_chat', 0) == 1"
				label="{{ __('Enable Realtime Voice Chat') }}"
			>
				<x-badge
					class="ms-2 text-2xs"
					variant="secondary"
				>
					@lang('New')
				</x-badge>
			</x-forms.input>
			<x-alert class="mt-2">
				<p class="text-justify">
					{{ __('Please note that enabling this feature may expose your API key because GPT uses frontend streaming for this feature. It is recommended to enable the "User API Keys" feature in the general settings so each user can use their own API key.') }}
				</p>
			</x-alert>
		</x-card>
	</div>
</div>
