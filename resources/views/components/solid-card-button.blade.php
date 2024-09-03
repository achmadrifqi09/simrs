<button
    {{
      $attributes->merge([
          'class' => 'text-center font-medium capitalize aspect-[3/1] flex flex-wrap justify-center items-center bg-gradient-to-br from-secondary to-primary p-4 rounded-lg text-white block w-full disabled:opacity-60'
       ])
     }} {{$attributes}}>
    {{$slot}}
</button>
