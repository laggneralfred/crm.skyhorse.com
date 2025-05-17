div>
    <h2 class="text-xl font-bold mb-4">Interconnection Info</h2>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="bg-white p-4 rounded border">
            <p><strong>Point of Interconnection:</strong> {{ $project->PointOfInterconnection }}</p>
            <p><strong>Primary Voltage:</strong> {{ $project->InterconnectionPrimaryVoltage }}</p>
            <p><strong>Secondary Voltage:</strong> {{ $project->InterconnectionSecondaryVoltage }}</p>
        </div>
        <div class="bg-white p-4 rounded border">
            <p><strong>Owner:</strong> {{ $project->InterconnectionENVOwner }}</p>
        </div>
    </div>
</div>