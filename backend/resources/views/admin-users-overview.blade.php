{{--
  Admin User Management PDF (DomPDF).
  Variables: $users (from AdminController::buildUsersOverview), $stats (totals).
  Optional search is applied in the controller before render; not shown in this template.
--}}
<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	{{-- Inline styles only; DomPDF does not load external CSS reliably. --}}
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
		.email {
			font-size: 9px;
			color: #666;
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

	<table>
		<thead>
			<tr>
				<th style="width: 22%;">User</th>
				<th style="width: 12%;">Role</th>
				<th style="width: 12%;">ID</th>
				<th style="width: 10%;">Goals</th>
				<th style="width: 12%;">Completed</th>
				<th style="width: 14%;">Last Updated</th>
			</tr>
		</thead>
		<tbody>
			@forelse($users as $user)
				<tr>
					<td>
						<strong>{{ $user['name'] }}</strong><br>
						<span class="email">{{ $user['email'] }}</span>
					</td>
					<td>{{ $user['role'] }}</td>
					<td>{{ $user['username'] ?? '-' }}</td>
					<td>{{ $user['goals'] }}</td>
					<td>{{ $user['completedGoals'] }}</td>
					<td>{{ $user['updatedAt'] ?? '-' }}</td>
				</tr>
			@empty
				<tr>
					<td colspan="6">No users match this filter.</td>
				</tr>
			@endforelse
		</tbody>
	</table>
</body>
</html>
