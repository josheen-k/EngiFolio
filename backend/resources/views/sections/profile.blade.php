<div class="section">
	{{-- Add profile information to pdf --}}
	<h2>Profile</h2>
	@if($profile->preferred_name)
		<p><strong>Preferred name:</strong> {{ $profile->preferred_name }}</p>
	@endif
	<p><strong>Degree:</strong> {{ $profile->degree_title ?? '' }}</p>
	<p><strong>Specialisation:</strong> {{ $profile->specialisation ?? '' }}</p>
	<p><strong>Personal Intro:</strong> {{ $profile->personal_intro ?? '' }}</p>
		@if($profile->links->isNotEmpty())
		<h3>Professional Links</h3>
		@foreach($profile->links as $link)
			<p><strong>{{ $link->link_label }}:</strong> {{ $link->link_url }}</p>
		@endforeach
    @endif
</div>