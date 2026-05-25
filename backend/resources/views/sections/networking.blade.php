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
							<div>{{ $method->method_value }}</div>
						@endforeach
					</td>
					<td>{{ $contact->date_met }}</td>
				</tr>
			@endforeach
		</tbody>
	</table>
</div>