<div x-data="{ tab: 'overview' }">
    <!-- Tab Headers in use -->
    <div class="flex flex-wrap justify-center gap-6 mb-6 text-sm font-semibold border-b pb-2">
        <button @click="tab = 'overview'" 
                :class="tab === 'overview' ? 'text-blue-600 border-b-2 border-blue-600' : 'text-gray-600'" 
                class="pb-2">
            Overview
        </button>
        <button @click="tab = 'timeline'" 
                :class="tab === 'timeline' ? 'text-blue-600 border-b-2 border-blue-600' : 'text-gray-600'" 
                class="pb-2">
            Timeline
        </button>
        <button @click="tab = 'ownership'" 
                :class="tab === 'ownership' ? 'text-blue-600 border-b-2 border-blue-600' : 'text-gray-600'" 
                class="pb-2">
            Ownership
        </button>
        <button @click="tab = 'offtaker_/_purchaser'" 
                :class="tab === 'offtaker_/_purchaser' ? 'text-blue-600 border-b-2 border-blue-600' : 'text-gray-600'" 
                class="pb-2">
            Offtaker / Purchaser
        </button>
        <button @click="tab = 'supplier_/_epc'" 
                :class="tab === 'supplier_/_epc' ? 'text-blue-600 border-b-2 border-blue-600' : 'text-gray-600'" 
                class="pb-2">
            Supplier / EPC
        </button>
        <button @click="tab = 'performance'" 
                :class="tab === 'performance' ? 'text-blue-600 border-b-2 border-blue-600' : 'text-gray-600'" 
                class="pb-2">
            Performance
        </button>
        <button @click="tab = 'location'" 
                :class="tab === 'location' ? 'text-blue-600 border-b-2 border-blue-600' : 'text-gray-600'" 
                class="pb-2">
            Location
        </button>
        <button @click="tab = 'connection_/_substation'" 
                :class="tab === 'connection_/_substation' ? 'text-blue-600 border-b-2 border-blue-600' : 'text-gray-600'" 
                class="pb-2">
            Connection / Substation
        </button>
        <button @click="tab = 'queue_/_iso'" 
                :class="tab === 'queue_/_iso' ? 'text-blue-600 border-b-2 border-blue-600' : 'text-gray-600'" 
                class="pb-2">
            Queue / ISO
        </button>
        <button @click="tab = 'key_company_contacts'" 
                :class="tab === 'key_company_contacts' ? 'text-blue-600 border-b-2 border-blue-600' : 'text-gray-600'" 
                class="pb-2">
            Key Company Contacts
        </button>
        <button @click="tab = 'project_contacts'" 
                :class="tab === 'project_contacts' ? 'text-blue-600 border-b-2 border-blue-600' : 'text-gray-600'" 
                class="pb-2">
            Project Contacts
        </button>
    </div>
    <div x-show="tab === 'overview'" x-cloak>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-y-4 gap-x-8">
            <div><strong>ENVProjectID:</strong> {{ $project->ENVProjectID }}</div>
            <div><strong>ProjectName:</strong> {{ $project->ProjectName }}</div>
            <div><strong>ProjectType:</strong> {{ $project->ProjectType }}</div>
            <div><strong>ProjectStatus:</strong> {{ $project->ProjectStatus }}</div>
            <div><strong>ProjectCapacityMW:</strong> {{ $project->ProjectCapacityMW }}</div>
            <div><strong>CurrentOperatingCapacityMW:</strong> {{ $project->CurrentOperatingCapacityMW }}</div>
            <div><strong>CommunitySolar:</strong> {{ $project->CommunitySolar }}</div>
            <div><strong>CoLocatedProject:</strong> {{ $project->CoLocatedProject }}</div>
            <div><strong>BatteryType:</strong> {{ $project->BatteryType }}</div>
        </div>
    </div>
    <div x-show="tab === 'timeline'" x-cloak>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-y-4 gap-x-8">
            <div><strong>FirstPowerDate:</strong> {{ $project->FirstPowerDate }}</div>
            <div><strong>FirstYearPower:</strong> {{ $project->FirstYearPower }}</div>
            <div><strong>ConstructionDate:</strong> {{ $project->ConstructionDate }}</div>
            <div><strong>RepoweringDate:</strong> {{ $project->RepoweringDate }}</div>
            <div><strong>SuspendedDate:</strong> {{ $project->SuspendedDate }}</div>
            <div><strong>ProjectLastUpdatedDate:</strong> {{ $project->ProjectLastUpdatedDate }}</div>
        </div>
    </div>
    <div x-show="tab === 'ownership'" x-cloak>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-y-4 gap-x-8">
            <div><strong>Developer:</strong> {{ $project->Developer }}</div>
            <div><strong>Owner:</strong> {{ $project->Owner }}</div>
            <div><strong>PercentOwned:</strong> {{ $project->PercentOwned }}</div>
            <div><strong>FormerOwner:</strong> {{ $project->FormerOwner }}</div>
        </div>
    </div>
    <div x-show="tab === 'offtaker_/_purchaser'" x-cloak>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-y-4 gap-x-8">
            <div><strong>ProjectOfftaker:</strong> {{ $project->ProjectOfftaker }}</div>
            <div><strong>PowerPurchaser:</strong> {{ $project->PowerPurchaser }}</div>
            <div><strong>PowerPurchaseAgreementCapacityMW:</strong> {{ $project->PowerPurchaseAgreementCapacityMW }}</div>
            <div><strong>PowerPurchaseAgreementYears:</strong> {{ $project->PowerPurchaseAgreementYears }}</div>
        </div>
    </div>
    <div x-show="tab === 'supplier_/_epc'" x-cloak>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-y-4 gap-x-8">
            <div><strong>Supplier:</strong> {{ $project->Supplier }}</div>
            <div><strong>UnitType:</strong> {{ $project->UnitType }}</div>
            <div><strong>UnitSupplied:</strong> {{ $project->UnitSupplied }}</div>
            <div><strong>EPC:</strong> {{ $project->EPC }}</div>
        </div>
    </div>
    <div x-show="tab === 'performance'" x-cloak>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-y-4 gap-x-8">
            <div><strong>TotalProducingMonths:</strong> {{ $project->TotalProducingMonths }}</div>
            <div><strong>TotalMWhGenerated:</strong> {{ $project->TotalMWhGenerated }}</div>
            <div><strong>AverageCapacityFactor:</strong> {{ $project->AverageCapacityFactor }}</div>
            <div><strong>First3MonthMWhGenerated:</strong> {{ $project->First3MonthMWhGenerated }}</div>
            <div><strong>First3MonthCapacityFactor:</strong> {{ $project->First3MonthCapacityFactor }}</div>
            <div><strong>First6MonthMWhGenerated:</strong> {{ $project->First6MonthMWhGenerated }}</div>
            <div><strong>First6MonthCapacityFactor:</strong> {{ $project->First6MonthCapacityFactor }}</div>
            <div><strong>First9MonthMWhGenerated:</strong> {{ $project->First9MonthMWhGenerated }}</div>
            <div><strong>First9MonthCapacityFactor:</strong> {{ $project->First9MonthCapacityFactor }}</div>
            <div><strong>First12MonthMWhGenerated:</strong> {{ $project->First12MonthMWhGenerated }}</div>
            <div><strong>First12MonthCapacityFactor:</strong> {{ $project->First12MonthCapacityFactor }}</div>
            <div><strong>First24MonthMWhGenerated:</strong> {{ $project->First24MonthMWhGenerated }}</div>
            <div><strong>First24MonthCapacityFactor:</strong> {{ $project->First24MonthCapacityFactor }}</div>
            <div><strong>First36MonthMWhGenerated:</strong> {{ $project->First36MonthMWhGenerated }}</div>
            <div><strong>First36MonthCapacityFactor:</strong> {{ $project->First36MonthCapacityFactor }}</div>
            <div><strong>Last3MonthMWhGenerated:</strong> {{ $project->Last3MonthMWhGenerated }}</div>
            <div><strong>Last3MonthCapacityFactor:</strong> {{ $project->Last3MonthCapacityFactor }}</div>
            <div><strong>Last6MonthMWhGenerated:</strong> {{ $project->Last6MonthMWhGenerated }}</div>
            <div><strong>Last6MonthCapacityFactor:</strong> {{ $project->Last6MonthCapacityFactor }}</div>
            <div><strong>Last9MonthMWhGenerated:</strong> {{ $project->Last9MonthMWhGenerated }}</div>
            <div><strong>Last9MonthCapacityFactor:</strong> {{ $project->Last9MonthCapacityFactor }}</div>
            <div><strong>Last12MonthMWhGenerated:</strong> {{ $project->Last12MonthMWhGenerated }}</div>
            <div><strong>Last12MonthCapacityFactor:</strong> {{ $project->Last12MonthCapacityFactor }}</div>
            <div><strong>Last24MonthMWhGenerated:</strong> {{ $project->Last24MonthMWhGenerated }}</div>
            <div><strong>Last24MonthCapacityFactor:</strong> {{ $project->Last24MonthCapacityFactor }}</div>
            <div><strong>Last36MonthMWhGenerated:</strong> {{ $project->Last36MonthMWhGenerated }}</div>
            <div><strong>Last36MonthCapacityFactor:</strong> {{ $project->Last36MonthCapacityFactor }}</div>
        </div>
    </div>
    <div x-show="tab === 'location'" x-cloak>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-y-4 gap-x-8">
            <div><strong>Country:</strong> {{ $project->Country }}</div>
            <div><strong>StateProvince:</strong> {{ $project->StateProvince }}</div>
            <div><strong>County:</strong> {{ $project->County }}</div>
            <div><strong>City:</strong> {{ $project->City }}</div>
            <div><strong>ZipCode:</strong> {{ $project->ZipCode }}</div>
            <div><strong>Address:</strong> {{ $project->Address }}</div>
            <div><strong>Latitude:</strong> {{ $project->Latitude }}</div>
            <div><strong>Longitude:</strong> {{ $project->Longitude }}</div>
            <div><strong>EstimatedCoordinates:</strong> {{ $project->EstimatedCoordinates }}</div>
            <div><strong>EstimateSource:</strong> {{ $project->EstimateSource }}</div>
        </div>
    </div>
    <div x-show="tab === 'connection_/_substation'" x-cloak>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-y-4 gap-x-8">
            <div><strong>PointOfInterconnection:</strong> {{ $project->PointOfInterconnection }}</div>
            <div><strong>InterconnectionPrimaryVoltage:</strong> {{ $project->InterconnectionPrimaryVoltage }}</div>
            <div><strong>InterconnectionSecondaryVoltage:</strong> {{ $project->InterconnectionSecondaryVoltage }}</div>
            <div><strong>InterconnectionENVOwner:</strong> {{ $project->InterconnectionENVOwner }}</div>
            <div><strong>ENVSubstationID:</strong> {{ $project->ENVSubstationID }}</div>
        </div>
    </div>
    <div x-show="tab === 'queue_/_iso'" x-cloak>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-y-4 gap-x-8">
            <div><strong>ISO:</strong> {{ $project->ISO }}</div>
            <div><strong>QueueNumber:</strong> {{ $project->QueueNumber }}</div>
            <div><strong>FirstQueueDate:</strong> {{ $project->FirstQueueDate }}</div>
            <div><strong>NERC_Region:</strong> {{ $project->NERC_Region }}</div>
            <div><strong>ISOTerritory:</strong> {{ $project->ISOTerritory }}</div>
        </div>
    </div>
    <div x-show="tab === 'key_company_contacts'" x-cloak>
        <div class="overflow-x-auto">
            <table class="min-w-full text-left text-sm">
                <thead>
                    <tr>
                        <th class="border-b p-2">Name</th>
                        <th class="border-b p-2">Company</th>
                        <th class="border-b p-2">Title</th>
                        <th class="border-b p-2">Email</th>
                        <th class="border-b p-2">Phone</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($project->keyCompanyContacts as $contact)
                    <tr>
                        <td class="border-b p-2">{{ $contact->keycontactname }}</td>
                        <td class="border-b p-2">{{ $contact->keycontactcompanyname }}</td>
                        <td class="border-b p-2">{{ $contact->keycontacttitle }}</td>
                        <td class="border-b p-2">{{ $contact->keycontactemail }}</td>
                        <td class="border-b p-2">{{ $contact->keycontactphone }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <div x-show="tab === 'project_contacts'" x-cloak>
        <div class="overflow-x-auto">
            <table class="min-w-full text-left text-sm">
                <thead>
                    <tr>
                        <th class="border-b p-2">Name</th>
                        <th class="border-b p-2">Company</th>
                        <th class="border-b p-2">Title</th>
                        <th class="border-b p-2">Email</th>
                        <th class="border-b p-2">Phone</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($project->projectContacts as $contact)
                    <tr>
                        <td class="border-b p-2">{{ $contact->contactname }}</td>
                        <td class="border-b p-2">{{ $contact->contactcompanyname }}</td>
                        <td class="border-b p-2">{{ $contact->contacttitle }}</td>
                        <td class="border-b p-2">{{ $contact->contactemail }}</td>
                        <td class="border-b p-2">{{ $contact->contactphone }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
