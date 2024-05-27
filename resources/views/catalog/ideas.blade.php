<x-layouts.app>
    <x-slot:title>
        {{ trans('app.ideas.catalog.ideas_catalog') . ' - ' . (!empty($categoryCode) ?
                 trans('app.ideas.catalog.category.' . $categoryCode)
                 : trans('app.ideas.catalog.categories.all_categories'))}}
    </x-slot:title>
    <x-slot:metaDescription>
        {{ $description ?? '' }}
    </x-slot:metaDescription>
    <x-slot:metaKeywords>
        {{ $keywords ?? '' }}
    </x-slot:metaKeywords>
    <x-partials.collapsable title="{{ trans('app.ideas.catalog.ideas_catalog') }}">
        <div class="grid sm:grid-cols-4 grid-cols-3 text-xs sm:text-base grid-flow-row gap-4">
            @foreach($categories as $category)
                <div>
                    <div class="border h-8 align-middle flex items-center justify-around text-center px-2">
                        <a href="{{route('app.ideas.catalog.category', ['category' => $category->code])}}">
                            {{ trans('app.ideas.catalog.category.' . $category->code) }}
                        </a>
                    </div>
                </div>
            @endforeach
            <div class="h-8">
                <a href="{{route('app.ideas.catalog')}}" class="underline">
                    {{ trans('app.ideas.catalog.categories.all_categories') }}
                </a>
            </div>
        </div>
    </x-partials.collapsable>
    <h1 class="text-3xl font-extrabold pb-6">
        {{!empty($categoryCode)
                ? trans('app.ideas.catalog.categories.category_projects',['category'=> trans('app.ideas.catalog.category.' . $categoryCode)])
                : trans('app.ideas.catalog.categories.all_categories_projects')}}
    </h1>
    @include('components.partials.ad')
    @if(count($projects) > 0)
        <div class="text-info">{{trans('app.ideas.catalog.categories.nb_projects',['nb'=> count($projects)])}}</div>
        <div class="grid sm:grid-cols-4 grid-cols-3 text-xs sm:text-base grid-flow-row gap-4">
            @foreach($projects as $project)
                <div class="">
                    <div class="border h-20 align-middle flex items-center justify-around text-center px-2">
                        <a href="{{route('app.ideas.catalog.idea', ['id' => $project->id, 'title' => str_replace(' ', '-', strtolower($project->title)), 'category' => $project->category->code])}}">
                            {{ $project->title }}
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div>{{ trans('app.ideas.catalog.categories.no_projects') }}</div>
    @endif
</x-layouts.app>
