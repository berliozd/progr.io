@if(!auth()->user())
    <div class="flex flex-col sm:flex-row rounded-lg border p-4 shadow-2xl bg-primary/30 text-xl w-full justify-between">
        <div class="flex flex-col">
            <div class="text-2xl">Would you like to give it a try?</div>
            How about creating your own project ideas and experiencing the benefits of our
            AI-assisted tools for defining and refining your project ideas?
        </div>
        <div class="p-2 flex justify-center">
            <a href="{{route('register')}}">
                <button href="{{route('register')}}" class="inline-flex items-center px-4 py-2 border rounded-md font-semibold text-xs
  uppercase tracking-widest
  focus:outline-none
  focus:ring-2 focus:ring-secondary
  transition ease-in-out duration-150
  text-white
  bg-primary
  hover:bg-neutral
  active:bg-base-200 border-base-100">Create an account now!
                </button>
            </a>
        </div>
    </div>
@endif