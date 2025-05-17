
<div x-show="activeTab === 'power-purchase-info'" x-cloak>
    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <h2 class="text-xl font-bold mb-4">Power Purchase Info</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            
            <div class="bg-gray-50 border rounded p-4 space-y-2">
                
                <p><span class="font-semibold">PowerPurchaseAgreementCapacity MW:</span> {{ $project->PowerPurchaseAgreementCapacityMW }}</p>
                
                <p><span class="font-semibold">PowerPurchaseAgreementYears:</span> {{ $project->PowerPurchaseAgreementYears }}</p>
                
            </div>
            
            <div class="bg-gray-50 border rounded p-4 space-y-2">
                
                <p><span class="font-semibold">PowerPurchaser:</span> {{ $project->PowerPurchaser }}</p>
                
                <p><span class="font-semibold">PowerPurchaserHQAddress:</span> {{ $project->PowerPurchaserHQAddress }}</p>
                
            </div>
            
            <div class="bg-gray-50 border rounded p-4 space-y-2">
                
                <p><span class="font-semibold">PowerPurchaserHQCity:</span> {{ $project->PowerPurchaserHQCity }}</p>
                
                <p><span class="font-semibold">PowerPurchaserHQZipCode:</span> {{ $project->PowerPurchaserHQZipCode }}</p>
                
            </div>
            
            <div class="bg-gray-50 border rounded p-4 space-y-2">
                
                <p><span class="font-semibold">PowerPurchaserHQState:</span> {{ $project->PowerPurchaserHQState }}</p>
                
                <p><span class="font-semibold">PowerPurchaserHQCountry:</span> {{ $project->PowerPurchaserHQCountry }}</p>
                
            </div>
            
            <div class="bg-gray-50 border rounded p-4 space-y-2">
                
                <p><span class="font-semibold">PowerPurchaserGeneralEmail:</span> {{ $project->PowerPurchaserGeneralEmail }}</p>
                
                <p><span class="font-semibold">PowerPurchaserWebsite:</span> {{ $project->PowerPurchaserWebsite }}</p>
                
            </div>
            
            <div class="bg-gray-50 border rounded p-4 space-y-2">
                
                <p><span class="font-semibold">PowerPurchaserHQPhone:</span> {{ $project->PowerPurchaserHQPhone }}</p>
                
            </div>
            
        </div>
    </div>
</div>