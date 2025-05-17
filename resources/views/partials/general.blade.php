<div class="grid grid-cols-1 md:grid-cols-2 gap-6 bg-white p-4 rounded shadow">
    <div>
        <p><strong>Project Type:</strong> {{ $project->ProjectType }}</p>
        <p><strong>Project Capacity:</strong> {{ $project->ProjectCapacityMW }} MW</p>
        <p><strong>Developer:</strong> {{ $project->Developer }}</p>
        <p><strong>First Power Date:</strong> {{ $project->FirstPowerDate }}</p>
    </div>
    <div>
        <p><strong>Project Status:</strong> {{ $project->ProjectStatus }}</p>
        <p><strong>Offtaker:</strong> {{ $project->ProjectOfftaker }}</p>
        <p><strong>Owner:</strong> {{ $project->Owner }}</p>
    </div>
</div>
