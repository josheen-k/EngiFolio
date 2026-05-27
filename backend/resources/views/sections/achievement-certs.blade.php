<div class="section">
	<h2>Achievement Certificates</h2>
	@if ($profile->achievementCerts->isNotEmpty())
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
	@else
		<p>No achievement certifications to show.</p>
	@endif
</div>