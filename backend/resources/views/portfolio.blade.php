<!DOCTYPE html>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
		{{-- Style to be used by the pdf --}}
		@include('portfolioSections.pdf-style')
	</head>
	<body>
	<h1>Engineering Portfolio - {{ $profile->user->first_name ?? '' }} {{ $profile->user->last_name ?? '' }}</h1>

	{{-- Check to see if profile option was selected --}}
	@if(!empty($selections['profile']))
		@include('portfolioSections.profile', ['profile' => $profile])
	@endif

	{{-- Show achievement certificates if selected --}}
	@if(!empty($selections['certifications']))	
		@include('portfolioSections.achievement-certs', ['profile' => $profile])
		@include('portfolioSections.attainment-certs', ['profile' => $profile])			
	@endif

	{{-- Check if competencies option is selected --}}
	@if(!empty($selections['competencies']))
		@include('portfolioSections.competencies', ['profile' => $profile])
	@endif

	{{-- Check if networking is selected --}}
	@if(!empty($selections['networking']))
		@include('portfolioSections.networking', ['profile' => $profile])
	@endif

	{{-- Check if goals and plans is selected and contains goals --}}
	@if(!empty($selections['goals']))
		@include('portfolioSections.career-plans', ['profile' => $profile])
    	@include('portfolioSections.goals', ['profile' => $profile])
	@endif
	</body>
</html>