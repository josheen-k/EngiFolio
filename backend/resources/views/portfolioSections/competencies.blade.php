<div class="section">	
	<h2>Competencies</h2>
	@if ($profile->competencyEntries->isNotEmpty())
		{{-- Group entries by indicator --}}
		@foreach($profile->competencyEntries->groupBy('indicator_id') as $indicatorGroup)
			@php
				// Grab the first entry in the group to get indicator details
				$firstEntry = $indicatorGroup->first();
			@endphp
			<div>
				<h3 style="padding: 5px;">
					{{ $firstEntry->indicator?->display_id }}: {{ $firstEntry->indicator?->indicator_name }}
				</h3>
				{{-- Loop through all entries under this indicator --}}
				@foreach($indicatorGroup as $entry)
					<div class="competency-entry" style="margin-left: 20px; margin-bottom: 15px; border-bottom: 1px dashed #ccc; padding-bottom: 10px;">
						<p><strong>Competency:</strong> {{ $entry->experience_title }}</p>
						<p><strong>Experience Tasks:</strong> {{ $entry->experience_tasks }}</p>
						<p><strong>Key Learnings:</strong> {{ $entry->key_learnings }}</p>
						<p><strong>Future Applications:</strong> {{ $entry->future_applications }}</p>
						<div>
							<span><strong>Year:</strong> {{ $entry->associated_year }}</span>  |  
							<span><strong>Level:</strong> {{ $entry->entryLevel?->competency_level }}</span>
						</div>
					</div>
				@endforeach
			</div>
		@endforeach
	@else
		<p>No competency entries to show.</p>
	@endif
</div>