<!DOCTYPE html>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
		{{-- Style to be used by the pdf --}}
		<style>
			body { 
				font-family: 'Arial', sans-serif; 
				font-size: 10px;
				line-height: 1.5; 
				color: #293949;
				margin: 0;
				padding: 0;
			}

			h1 { 
				font-size: 22px; 
				color: #2974a3;
				border-bottom: 2px solid #2974a3;
				padding-bottom: 5px;
				margin-bottom: 20px;
			}

			h2 { 
				font-size: 16px; 
				color: #1a5276; 
				margin-top: 25px;
				border-left: 4px solid #2974a3;
				padding-left: 10px;
			}

			/* Prevents rows from splitting over pages*/
			.section { 
				margin-bottom: 30px; 
				page-break-inside: avoid;
			}

			table { 
				width: 100%; 
				border-collapse: collapse; 
				margin-top: 15px;
				table-layout: fixed;
			}

			th { 
				background-color: #f8f9fa; 
				color: #34495e;
				font-weight: bold; 
				text-transform: uppercase;
				font-size: 9px;
				border-bottom: 2px solid #dee2e6;
				padding: 10px 8px;
			}

			td { 
				border-bottom: 1px solid #eee;
				padding: 8px; 
				vertical-align: top;
				word-wrap: break-word;
			}
		</style>
	</head>
	<body>
	<h1>Engineering Portfolio - {{ $profile->user->first_name ?? '' }} {{ $profile->user->last_name ?? '' }}</h1>

	{{-- Check to see if profile option was selected --}}
	@if(!empty($selections['profile']))
		<div class="section">
			{{-- Add profile information to pdf --}}
			<h2>Profile</h2>
			@if($profile->preferred_name)
					<p><strong>Preferred name:</strong> {{ $profile->preferred_name }}</p>
			@endif
			<p><strong>Degree:</strong> {{ $profile->degree_title ?? '' }}</p>
			<p><strong>Sepcialisation:</strong> {{ $profile->specialisation ?? '' }}</p>
			<p><strong>Personal Intro:</strong> {{ $profile->personal_intro ?? '' }}</p>
		</div>
	@endif

	{{-- Show achievement certificates if selected --}}
	@if(!empty($selections['certifications']) && $profile->achievementCerts->isNotEmpty())
		<div class="section">
			<h2>Achievement Certificates</h2>
			<table>
				<thead>
					<tr>
						<th style="width: 25%;">Title</th>
						<th style="width: 60%;">Details</th>
						<th style="width: 15%;">Issued Date</th>
					</tr>
				</thead>
				<tbody>
						@foreach($profile->achievementCerts as $cert)
							<tr>
								<td>{{ $cert->title }}</td>
								<td>{{ $cert->body ?? '' }}</td>
								<td>{{ $cert->issued_date ?? '' }}</td>
							</tr>
						@endforeach
				</tbody>
			</table>
		</div>
	@endif

	{{-- Show attainment certificates if selected --}}
	@if(!empty($selections['certifications']) && $profile->attainmentCerts->isNotEmpty())
		<div class="section">
			<h2>Attainment Certificates</h2>
			<table>
					<thead>
						<tr>
							<th style="width: 25%;">Title</th>
							<th style="width: 45%;">Details</th>
							<th style="width: 15%;">Issued Date</th>
							<th style="width: 15%;">Expiry Date</th>
						</tr>
					</thead>
				<tbody>
					@foreach($profile->attainmentCerts as $cert)
						<tr>
							<td>{{ $cert->title }}</td>
							<td>{{ $cert->body ?? '' }}</td>
							<td>{{ $cert->issued_date ?? '' }}</td>
							<td>{{ $cert->expiry_date ?? '' }}</td>
						</tr>
					@endforeach
				</tbody>
			</table>
		</div>
	@endif

	{{-- Check if competencies option is selected and if there are competencies to add --}}
	@if(!empty($selections['competencies']) && $profile->competencyEntries->isNotEmpty())
		<h2>Competencies</h2>

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
	@endif

	{{-- Check if networking is selected and contains industry contacts --}}
	@if(!empty($selections['networking']) && $profile->industryContacts->isNotEmpty())
		<div class="section">
			<h2>Industry Contacts & Networking</h2>
			<table>
					<thead>
						<tr>
							<th style="width: 15%;">Contact Name</th>
							<th style="width: 15%;">Company</th>
							<th style="width: 30%;">Progress Notes</th>
							<th style="width: 25%;">Contact Methods</th>
							<th style="width: 15%;">Date Met</th>
						</tr>
					</thead>
				<tbody>
					@foreach($profile->industryContacts as $contact)
						<tr>
							<td>{{ $contact->contact_name }}</td>
							<td>{{ $contact->company }}</td>
							<td>{{ $contact->progress_notes }}</td>
							<td>
								@foreach($contact->contactMethods as $method)
									<div>{{ $method->method_type }}: {{ $method->method_value }}</div>
								@endforeach
							</td>
							<td>{{ $contact->date_met }}</td>
						</tr>
					@endforeach
				</tbody>
			</table>
		</div>
	@endif

	{{-- Check if goals is selected and contains goals --}}
	@if(!empty($selections['goals']) && $profile->careerPlans->isNotEmpty())
		<div class="section">
			<h2>Career Development & SMART Goals</h2>

			{{-- Loop for each career plan year --}}
			@foreach($profile->careerPlans as $plan)
				<div>
					<h3>
							Plan Year: {{ $plan->plan_year }}
					</h3>
					
					<p><strong>Professional Interests:</strong> {{ $plan->professional_interests }}</p>

					@if($plan->smartGoals->isEmpty())
						<p><em>No SMART goals recorded for this year.</em></p>
					@else
						<table>
							<thead>
								<tr>
									<th style="width: 35%;">Goals & Action Steps</th>
									<th style="width: 35%;">Notes & Learnings</th>
									<th style="width: 15%;">Dates</th>
									<th style="width: 15%;">Status</th>
								</tr>
							</thead>
							<tbody>
								@foreach($plan->smartGoals as $goal)
									<tr>
										<td>
											<strong>{{ $goal->goal_description }}</strong>
											<ul>
												@foreach($goal->actionSteps as $step)
														<li>{{ $step->step_description }}</li>
												@endforeach
											</ul>
										</td>
										<td>
											<div>
												<strong>Progress:</strong> {{ $goal->progress_notes }}<br>
												<strong>Learnings:</strong> {{ $goal->learnings }}
											</div>
										</td>
										<td>
											<small>
												Start: {{ $goal->start_date }}<br>
												End: {{ $goal->end_date }}<br>
												@if($goal->completion_date)
													<strong>Completed:</strong> {{ $goal->completion_date }}
												@endif
											</small>
										</td>
										<td>
											{{ $goal->status->status ?? 'Planned' }}
										</td>
									</tr>
								@endforeach
							</tbody>
						</table>
					@endif
				</div>
			@endforeach
		</div>
	@endif
	</body>
</html>