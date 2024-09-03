@props(['link' => '#'])

<a wire:navigate href="{{$link}}"
    {{
      $attributes->merge([
          'class' => 'text-center font-medium capitalize aspect-[3/1] flex justify-center items-center bg-gradient-to-br from-secondary to-primary p-4 rounded-lg text-white block w-full'
       ])
     }}>
    {{$slot}}
</a>
