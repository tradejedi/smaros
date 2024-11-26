<x-guest-layout>
    <x-blocks.profile-list :profiles="$profiles" />

    <div>
        {{ $profiles->links() }}
    </div>

    <div>
        <h1>{{ $page->name }}</h1>
        {{ $page->content }}
    </div>
</x-guest-layout>
