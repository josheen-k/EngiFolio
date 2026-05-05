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
			<p><strong>Preferred name:</strong> {{ $profile->preferred_name ?? 'N/A' }}</p>
			<p><strong>Degree:</strong> {{ $profile->degree_title ?? 'N/A' }}</p>
			<p><strong>Sepcialisation:</strong> {{ $profile->specialisation ?? 'N/A' }}</p>
			<p><strong>Personal Intro:</strong> {{ $profile->personal_intro ?? 'N/A' }}</p>
		</div>
	@endif

	{{-- Check if competencies option is selected and if there are competencies to add --}}
	@if(!empty($selections['competencies']) && $profile->competencyEntries->isNotEmpty())
		{{-- Add each competency individually so they fit better than a table --}}
		<h2>Competencies</h2>
		@foreach($profile->competencyEntries as $entry)
			<div class="section">
				<h3>
					{{ $entry->indicator?->display_id }}: {{ $entry->indicator?->indicator_name }}
				</h3>
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
	@endif

	{{-- Check if networking is selected and contains industry contacts --}}
	@if(!empty($selections['networking']) && $profile->industryContacts->isNotEmpty())
			<div class="section">
					<h2>Industry Contacts & Networking</h2>
					<table>
							<thead>
									<tr>
											<th>Contact Name</th>
											<th>Company</th>
											<th>Progress Notes</th>
											<th>Contact Methods</th>
											<th>Date Met</th>
									</tr>
							</thead>
							<tbody>
									@foreach($profile->industryContacts as $contact)
											<tr>
													<td>{{ $contact->contact_name }}</td>
													<td>{{ $contact->company }}</td>
													<td>{{ $contact->progress_notes }}</td>
													<td>{{ $contact->progress_notes }}</td>
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
									<th>Goal & Action Steps</th>
									<th>Dates</th>
									<th>Notes & Learnings</th>
									<th>Status</th>
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
											<small>
												Start: {{ $goal->start_date }}<br>
												End: {{ $goal->end_date }}<br>
												@if($goal->completion_date)
													<strong>Completed:</strong> {{ $goal->completion_date }}
												@endif
											</small>
										</td>
										<td>
											<div>
												<strong>Progress:</strong> {{ $goal->progress_notes }}<br>
												<strong>Learnings:</strong> {{ $goal->learnings }}
											</div>
										</td>
										<td>
											{{-- Accessing status via the relationship defined in your schema --}}
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