<div class="section">
    <h2>Career Development Plans</h2>
    @if ($profile->careerPlans->isNotEmpty())
        @foreach($profile->careerPlans as $plan)
            <h3>{{ $plan->plan_year }}</h3>
            <table>
                <thead>
                    <tr>
                        <th style="width: 20%;">Interests</th>
                        <th style="width: 20%;">Employers</th>
                        <th style="width: 20%;">Networking</th>
                        <th style="width: 20%;">Values & Activities</th>
                        <th style="width: 20%;">Development Focus</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>{{ $plan->professional_interests }}</td>
                        <td>{{ $plan->employers_of_interest }}</td>
                        <td>{{ $plan->networking_plan }}</td>
                        <td>
                            @if($plan->personal_values)
                                <strong>Values:</strong> {{ $plan->personal_values }}<br>
                            @endif
                            @if($plan->extracurriculars)
                                <strong>Extracurriculars:</strong> {{ $plan->extracurriculars }}
                            @endif
                        </td>
                        <td>{{ $plan->development_focus }}</td>
                    </tr>
                </tbody>
            </table>
        @endforeach
    @else
		<p>No career plans to show.</p>
	@endif
</div>