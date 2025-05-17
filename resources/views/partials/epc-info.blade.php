
{{-- <div x-show="activeTab === 'epc-info'" x-cloak> --}}
    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <h2 class="text-xl font-bold mb-4">Epc Info</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            
            <div class="bg-gray-50 border rounded p-4 space-y-2">
                
                <p><span class="font-semibold">EPC:</span> {{ $project->EPC }}</p>
                
                <p><span class="font-semibold">EPCHQAddress:</span> {{ $project->EPCHQAddress }}</p>
                
            </div>
            
            <div class="bg-gray-50 border rounded p-4 space-y-2">
                
                <p><span class="font-semibold">EPCHQCity:</span> {{ $project->EPCHQCity }}</p>
                
                <p><span class="font-semibold">EPCHQZipCode:</span> {{ $project->EPCHQZipCode }}</p>
                
            </div>
            
            <div class="bg-gray-50 border rounded p-4 space-y-2">
                
                <p><span class="font-semibold">EPCHQState:</span> {{ $project->EPCHQState }}</p>
                
                <p><span class="font-semibold">EPCHQCountry:</span> {{ $project->EPCHQCountry }}</p>
                
            </div>
            
            <div class="bg-gray-50 border rounded p-4 space-y-2">
                
                <p><span class="font-semibold">EPCGeneralEmail:</span> {{ $project->EPCGeneralEmail }}</p>
                
                <p><span class="font-semibold">EPCWebsite:</span> {{ $project->EPCWebsite }}</p>
                
            </div>
            
        </div>
    </div>
{{-- </div> --}}