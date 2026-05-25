<div class="section">
	<h2>SMART Goals</h2>
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
						@foreach($profile->smartGoals as $goal)
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
		</div>
</div>