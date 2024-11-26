<div class="grid md:grid-cols-2 xl:grid-cols-2 sm:grid-cols-2 gap-4">
    @foreach($profiles as $profile)
        <x-blocks.profile-item :profile="$profile" />
    @endforeach
</div>
