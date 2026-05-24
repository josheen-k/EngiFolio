{{--
  Admin User Management PDF (DomPDF).
  Variables: $stats, $roleSections (Students / Staffs / Admins — each with title + users collection).
--}}
<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<style>
		body {
			font-family: Arial, sans-serif;
			font-size: 10px;
			color: #293949;
			margin: 0;
			padding: 0;
		}
		h1 {
			font-size: 20px;
			color: #2974a3;
			border-bottom: 2px solid #2974a3;
			padding-bottom: 6px;
			margin-bottom: 12px;
		}
		h2 {
			font-size: 14px;
			color: #1a5276;
			margin: 18px 0 8px;
		}
		.role-section {
			margin-bottom: 22px;
			page-break-inside: avoid;
		}
		.role-section + .role-section {
			page-break-before: auto;
		}
		.stats {
			margin-bottom: 18px;
		}
		.stats span {
			display: inline-block;
			margin-right: 18px;
		}
		table {
			width: 100%;
			border-collapse: collapse;
			margin-bottom: 8px;
		}
		th, td {
			border: 1px solid #ccc;
			padding: 6px 8px;
			text-align: left;
			vertical-align: top;
		}
		th {
			background: #f3f3f3;
			font-weight: bold;
		}
		tr:nth-child(even) td {
			background: #fafafa;
		}
		.empty-row {
			color: #707070;
			font-style: italic;
		}
	</style>
</head>
<body>
	<h1>User Management Export</h1>

	<div class="stats">
		<span><strong>Total Users:</strong> {{ $stats['totalUsers'] }}</span>
		<span><strong>Total Goals:</strong> {{ $stats['totalGoals'] }}</span>
		<span><strong>Completed Goals:</strong> {{ $stats['totalCompletedGoals'] }}</span>
		<span><strong>Open Goals:</strong> {{ max(0, $stats['totalGoals'] - $stats['totalCompletedGoals']) }}</span>
	</div>

	@foreach($roleSections as $section)
		<div class="role-section">
			<h2>{{ $section['title'] }} ({{ $section['users']->count() }})</h2>
			<table>
				<thead>
					<tr>
						<th style="width: 18%;">Name</th>
						<th style="width: 26%;">Email</th>
						<th style="width: 12%;">ID</th>
						<th style="width: 10%;">Goals</th>
						<th style="width: 12%;">Completed</th>
						<th style="width: 14%;">Last Updated</th>
					</tr>
				</thead>
				<tbody>
					@forelse($section['users'] as $user)
						<tr>
							<td>{{ $user['name'] }}</td>
							<td>{{ $user['email'] }}</td>
							<td>{{ $user['username'] ?? '-' }}</td>
							<td>{{ $user['goals'] }}</td>
							<td>{{ $user['completedGoals'] }}</td>
							<td>{{ $user['updatedAt'] ?? '-' }}</td>
						</tr>
					@empty
						<tr>
							<td colspan="6" class="empty-row">No users in this group.</td>
						</tr>
					@endforelse
				</tbody>
			</table>
		</div>
	@endforeach
</body>
</html>
