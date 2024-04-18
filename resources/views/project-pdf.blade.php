<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="{{config('app.style-default-mode')}}"
      data-theme="dracula">
<head>
    <title>Project PDF {{$project->title}}</title>
    <link href="https://cdn.jsdelivr.net/npm/daisyui@4.10.2/dist/full.min.css" rel="stylesheet" type="text/css" />
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="font-sans antialiased">
<div class="bg-red-500">
    <table style="border: 1px red solid;">
        <tr style="border: 1px red solid;">
            <td style="border: 1px red solid;">
                <div class="border rounded bg-amber-400">abc</div>
                <div class="border rounded bg-primary">{{$project}}</div>
                <div class="border rounded bg-neutral">abc</div>
            </td>
        </tr>
    </table>
</div>
</body>
</html>
