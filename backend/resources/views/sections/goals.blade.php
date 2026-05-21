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