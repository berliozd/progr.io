<x-layouts.app>
    <x-slot:title>
        {{ trans('Project Idea') . ' - ' . trans('app.ideas.catalog.category.' . $project?->category?->code) . ' - ' . $project?->title }}
    </x-slot:title>
    <x-slot:metaDescription>
        {{ $project?->metaDescription->value ?? '' }}
    </x-slot:metaDescription>
    <x-slot:metaKeywords>
        {{ $project?->metaKeywords->value ?? '' }}
    </x-slot:metaKeywords>
    <x-slot:canonical>
        {{route('app.ideas.catalog.idea', ['id' => $project->id, 'title' => str_replace(' ', '-', strtolower($project->title)), 'category' => $project->category->code])}}
    </x-slot:canonical>
    @include('components.partials.breadcrumbs')
    @include('components.partials.ad')
    <div class="flex flex-col rounded-lg border p-4 shadow-2xl bg-neutral/70 text-2xl">
        <h2>{{ $project->title }}</h2>
    </div>
    <div class="flex flex-col rounded-lg border p-4 shadow-2xl bg-neutral/70 text-xl">
        <div>{{ $project->description }}</div>
    </div>
    @include('components.partials.create-account')
    @foreach($project->notes as $note)
        <div class="flex flex-col rounded-lg border shadow-2xl bg-neutral/70">
            <h3 class="bg-white/20 rounded-t-lg p-2 text-2xl">{{ ucfirst($note->type->label) }}:</h3>
            <div class="flex flex-row">
                @include('components.partials.note-logo', ['note' => $note])
                <pre class="text-wrap font-sans p-2">{{ $note->content }}</pre>
            </div>
        </div>
    @endforeach
    @if($project->competitors && count($project->competitors) > 0)
        <div class="flex flex-col rounded-lg border shadow-2xl bg-neutral/70">
            <h3 class="bg-white/20 rounded-t-lg p-2 text-2xl">{{ trans('app.project.competitors') }}:</h3>
            @foreach($project->competitors as $competitor)
                <div class="flex flex-col m-2 rounded-lg border shadow-2xl bg-neutral/70">
                    <h4 class="bg-white/30 rounded-t-lg p-2 text-xl">{{ ucfirst($competitor->name) }}:</h4>
                    <div class="">
                        <div class="m-4">{{ $competitor->description }}</div>
                        <div class="m-4">
                            <a href="{{ $competitor->url }}" target="_blank"
                               class="underline">{{ $competitor->url }}</a>
                        </div>
                        @foreach ($competitor->notes as $competitorNote)
                            <div class="flex flex-col m-2 rounded-lg border shadow-2xl bg-neutral/70">
                                <h5 class="bg-white/10 p-2 text-xl">{{ ucfirst($competitorNote->type->label) }}:</h5>
                                <div class="flex flex-row ">
                                    @include('components.partials.note-logo', ['note' => $competitorNote])
                                    <pre class="text-wrap font-sans p-2 rounded-b-lg">{{ $competitorNote->content }}</pre>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>
    @endif
    @include('components.partials.ad')
    @include('components.partials.create-account')
</x-layouts.app>
