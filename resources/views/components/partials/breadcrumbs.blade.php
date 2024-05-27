<div class="text-sm breadcrumbs">
    <ul>
        <li>
            <a href="{{route('app.ideas.catalog')}}">{{ trans('app.ideas.catalog.ideas_catalog') }}</a>
        </li>
        <li>
            <a href="{{route('app.ideas.catalog.category', ['category' => $project?->category?->code])}}">
                {{ trans('app.ideas.catalog.category.' . $project?->category?->code) }}
            </a>
        </li>
    </ul>
</div>