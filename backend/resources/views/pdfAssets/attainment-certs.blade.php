<div class="section">
	<h2>Attainment Certificates</h2>
	@if ($profile->attainmentCerts->isNotEmpty())
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
	@else
		<p>No achievement certifications to show.</p>
	@endif
</div>