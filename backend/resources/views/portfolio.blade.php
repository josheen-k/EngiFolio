<!DOCTYPE html>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
		{{-- Style to be used by the pdf --}}
		@include('sections.pdf-style')
	</head>
	<body>
	<h1>Engineering Portfolio - {{ $profile->user->first_name ?? '' }} {{ $profile->user->last_name ?? '' }}</h1>

	{{-- Check to see if profile option was selected --}}
	@if(!empty($selections['profile']))
		@include('sections.profile', ['profile' => $profile])
	@endif

	{{-- Show achievement certificates if selected --}}
	@if(!empty($selections['certifications']) && $profile->achievementCerts->isNotEmpty())
		@include('sections.achievement-certs', ['profile' => $profile])
		@include('sections.attainment-certs', ['profile' => $profile])
	@endif

	{{-- Check if competencies option is selected and if there are competencies to add --}}
	@if(!empty($selections['competencies']) && $profile->competencyEntries->isNotEmpty())
		@include('sections.competencies', ['profile' => $profile])
	@endif

	{{-- Check if networking is selected and contains industry contacts --}}
	@if(!empty($selections['networking']) && $profile->industryContacts->isNotEmpty())
		@include('sections.networking', ['profile' => $profile])
	@endif

	{{-- Check if goals and plans is selected and contains goals --}}
	@if(!empty($selections['goals']) && $profile->smartGoals->isNotEmpty())
		@include('sections.career-plans', ['profile' => $profile])
    	@include('sections.goals', ['profile' => $profile])
	@endif
	</body>
</html>