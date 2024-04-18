<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="{{config('app.style-default-mode')}}"
      data-theme="dracula">
<head>
    <title>Project PDF {{$project->title}}</title>
    <link href="https://cdn.jsdelivr.net/npm/daisyui@4.10.2/dist/full.min.css" rel="stylesheet" type="text/css"/>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        html {
            -webkit-print-color-adjust: exact;
        }
    </style>
</head>
<body class="font-sans antialiased">

<div class="p-4 mx-auto">
    <div class="space-y-2">

        <div class="flex flex-row justify-center">
            <div class="mt-1 flex flex-col p-1 border h-auto rounded">
                <img src="{{asset('/img/icon.png')}}" width="30px" height="30px">
                Progr.io
            </div>
        </div>

        <div class="flex flex-col rounded-lg border p-4 shadow-2xl text-2xl">
            <div>{{ $project->title }}</div>
        </div>

        <div class="flex flex-col rounded-lg border p-4 shadow-2xl text-xl">
            <div>{{ $project->description }}</div>
        </div>

        @foreach ($project->notes as $note)
            <div class="flex flex-col rounded-lg border shadow-2xl ">
                <h2 class=" rounded-t-lg p-2 text-2xl">{{ ucfirst($note->type->label) }}:</h2>
                <div class="flex flex-row">
                    <div class="p-2">
                        @include('project.note-logo', ['note' => $note])
                    </div>
                    <pre class="text-wrap font-sans p-2">{{ $note->content }}</pre>
                </div>
            </div>
        @endforeach

        @if ($project->competitors->count() > 0)
            <div class="flex flex-col rounded-lg border shadow-2xl ">
                <h2 class=" rounded-t-lg p-2 text-2xl">{{ __('app.project.competitors') }}:</h2>
                @foreach ($project->competitors as $competitor)
                    <div class="flex flex-col m-2 rounded-lg border shadow-2xl ">
                        <h2 class=" rounded-t-lg p-2 text-xl">{{ ucfirst($competitor->name) }}:</h2>
                        <div class="m-4">{{ $competitor->description }}</div>
                        <div class="m-4">
                            <a href="{{$competitor->url}}" target="_blank"
                               class="underline">{{ $competitor->url }}</a>
                        </div>
                        @foreach ($competitor->notes as $competitorNote)
                            <div class="flex flex-col m-2 rounded-lg border-t shadow-2xl ">
                                <h2 class="p-2 text-xl">{{ ucfirst($competitorNote->type->label) }}
                                    :</h2>
                                <div class="flex flex-row ">
                                    @include('project.note-logo', ['note' => $competitorNote])
                                    <pre class="text-wrap font-sans p-2 rounded-b-lg">{{ $competitorNote->content }}</pre>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>

</body>
</html>
