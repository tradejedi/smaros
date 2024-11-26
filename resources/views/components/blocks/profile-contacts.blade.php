@foreach($contacts as $contact)
    <x-blocks.contact-buttons :contact="$contact" />
@endforeach
