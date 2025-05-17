@extends('components.layouts.solar')

@section('content')

<div class="w-full max-w-6xl mx-auto mt-6">
    <div class="text-center text-xl font-bold mb-6">Solar Project: { $project->ProjectName }</div>
    <div class="tab">
        <button class="tablinks active" onclick="openTab(event, 'general_info')">yyyGeneral Info</button>
<button class="tablinks " onclick="openTab(event, 'location_info')">Location Info</button>
<button class="tablinks " onclick="openTab(event, 'developer_info')">Developer Info</button>
<button class="tablinks " onclick="openTab(event, 'owner_info')">Owner Info</button>
<button class="tablinks " onclick="openTab(event, 'supplier_info')">Supplier Info</button>
<button class="tablinks " onclick="openTab(event, 'production_info')">Production Info</button>
<button class="tablinks " onclick="openTab(event, 'interconnection_info')">Interconnection Info</button>
<button class="tablinks " onclick="openTab(event, 'epc_info')">Epc Info</button>
<button class="tablinks " onclick="openTab(event, 'other_info')">Other Info</button>

    </div>
    <div id="general_info" class="tabcontent" style='display:block;'>
    <h2 class="text-2xl font-bold mb-4">General Info</h2>
    <div class="bg-white p-6 rounded shadow grid grid-cols-1 sm:grid-cols-2 gap-6"><div class="flex justify-between border-b py-2"><div class="font-semibold">Project Type:</div><div>{{ $project->ProjectType }}</div></div>
<div class="flex justify-between border-b py-2"><div class="font-semibold">Project Status:</div><div>{{ $project->ProjectStatus }}</div></div>
<div class="flex justify-between border-b py-2"><div class="font-semibold">Project Capacity:</div><div>{{ $project->ProjectCapacityMW }}</div></div>
<div class="flex justify-between border-b py-2"><div class="font-semibold">Developer:</div><div>{{ $project->Developer }}</div></div>
<div class="flex justify-between border-b py-2"><div class="font-semibold">Owner:</div><div>{{ $project->Owner }}</div></div>
<div class="flex justify-between border-b py-2"><div class="font-semibold">Offtaker:</div><div>{{ $project->ProjectOfftaker }}</div></div>
<div class="flex justify-between border-b py-2"><div class="font-semibold">First Power Date:</div><div>{{ $project->FirstPowerDate }}</div></div></div>
</div>
<div id="location_info" class="tabcontent" >
    <h2 class="text-2xl font-bold mb-4">Location Info</h2>
    <div class="bg-white p-6 rounded shadow grid grid-cols-1 sm:grid-cols-2 gap-6"><div class="flex justify-between border-b py-2"><div class="font-semibold">Address:</div><div>{{ $project->Address }}</div></div>
<div class="flex justify-between border-b py-2"><div class="font-semibold">City:</div><div>{{ $project->City }}</div></div>
<div class="flex justify-between border-b py-2"><div class="font-semibold">Zip Code:</div><div>{{ $project->ZipCode }}</div></div>
<div class="flex justify-between border-b py-2"><div class="font-semibold">Latitude:</div><div>{{ $project->Latitude }}</div></div>
<div class="flex justify-between border-b py-2"><div class="font-semibold">Longitude:</div><div>{{ $project->Longitude }}</div></div>
<div class="flex justify-between border-b py-2"><div class="font-semibold">State:</div><div>{{ $project->StateProvince }}</div></div>
<div class="flex justify-between border-b py-2"><div class="font-semibold">County:</div><div>{{ $project->County }}</div></div></div>
</div>
<div id="developer_info" class="tabcontent" >
    <h2 class="text-2xl font-bold mb-4">Developer Info</h2>
    <div class="bg-white p-6 rounded shadow grid grid-cols-1 sm:grid-cols-2 gap-6"><div class="flex justify-between border-b py-2"><div class="font-semibold">HQ Address:</div><div>{{ $project->ENVDeveloperHQAddress }}</div></div>
<div class="flex justify-between border-b py-2"><div class="font-semibold">City:</div><div>{{ $project->ENVDeveloperHQCity }}</div></div>
<div class="flex justify-between border-b py-2"><div class="font-semibold">Zip Code:</div><div>{{ $project->ENVDeveloperHQZipCode }}</div></div>
<div class="flex justify-between border-b py-2"><div class="font-semibold">State:</div><div>{{ $project->ENVDeveloperHQState }}</div></div>
<div class="flex justify-between border-b py-2"><div class="font-semibold">Country:</div><div>{{ $project->ENVDeveloperHQCountry }}</div></div>
<div class="flex justify-between border-b py-2"><div class="font-semibold">Phone:</div><div>{{ $project->ENVDeveloperHQPhone }}</div></div>
<div class="flex justify-between border-b py-2"><div class="font-semibold">Website:</div><div>{{ $project->ENVDeveloperWebsite }}</div></div>
<div class="flex justify-between border-b py-2"><div class="font-semibold">Email:</div><div>{{ $project->ENVDeveloperGeneralEmail }}</div></div></div>
</div>
<div id="owner_info" class="tabcontent" >
    <h2 class="text-2xl font-bold mb-4">Owner Info</h2>
    <div class="bg-white p-6 rounded shadow grid grid-cols-1 sm:grid-cols-2 gap-6"><div class="flex justify-between border-b py-2"><div class="font-semibold">HQ Address:</div><div>{{ $project->ENVOwnerHQAddress }}</div></div>
<div class="flex justify-between border-b py-2"><div class="font-semibold">City:</div><div>{{ $project->ENVOwnerHQCity }}</div></div>
<div class="flex justify-between border-b py-2"><div class="font-semibold">Zip Code:</div><div>{{ $project->ENVOwnerHQZipCode }}</div></div>
<div class="flex justify-between border-b py-2"><div class="font-semibold">State:</div><div>{{ $project->ENVOwnerHQState }}</div></div>
<div class="flex justify-between border-b py-2"><div class="font-semibold">Country:</div><div>{{ $project->ENVOwnerHQCountry }}</div></div>
<div class="flex justify-between border-b py-2"><div class="font-semibold">Phone:</div><div>{{ $project->ENVOwnerHQPhone }}</div></div>
<div class="flex justify-between border-b py-2"><div class="font-semibold">Website:</div><div>{{ $project->ENVOwnerWebsite }}</div></div>
<div class="flex justify-between border-b py-2"><div class="font-semibold">Email:</div><div>{{ $project->ENVOwnerGeneralEmail }}</div></div></div>
</div>
<div id="supplier_info" class="tabcontent" >
    <h2 class="text-2xl font-bold mb-4">Supplier Info</h2>
    <div class="bg-white p-6 rounded shadow grid grid-cols-1 sm:grid-cols-2 gap-6"><div class="flex justify-between border-b py-2"><div class="font-semibold">Supplier:</div><div>{{ $project->Supplier }}</div></div>
<div class="flex justify-between border-b py-2"><div class="font-semibold">Unit Supplied:</div><div>{{ $project->UnitSupplied }}</div></div>
<div class="flex justify-between border-b py-2"><div class="font-semibold">Website:</div><div>{{ $project->SupplierWebsite }}</div></div>
<div class="flex justify-between border-b py-2"><div class="font-semibold">Phone:</div><div>{{ $project->SupplierHQPhone }}</div></div></div>
</div>
<div id="production_info" class="tabcontent" >
    <h2 class="text-2xl font-bold mb-4">Production Info</h2>
    <div class="bg-white p-6 rounded shadow grid grid-cols-1 sm:grid-cols-2 gap-6"><div class="flex justify-between border-b py-2"><div class="font-semibold">Total MWh Generated:</div><div>{{ $project->TotalMWhGenerated }}</div></div>
<div class="flex justify-between border-b py-2"><div class="font-semibold">Producing Months:</div><div>{{ $project->TotalProducingMonths }}</div></div>
<div class="flex justify-between border-b py-2"><div class="font-semibold">Avg Capacity Factor:</div><div>{{ $project->AverageCapacityFactor }}</div></div>
<div class="flex justify-between border-b py-2"><div class="font-semibold">1st 12M MWh Gen:</div><div>{{ $project->First12MonthMWhGenerated }}</div></div>
<div class="flex justify-between border-b py-2"><div class="font-semibold">1st 12M Cap. Factor:</div><div>{{ $project->First12MonthCapacityFactor }}</div></div>
<div class="flex justify-between border-b py-2"><div class="font-semibold">Last 12M MWh Gen:</div><div>{{ $project->Last12MonthMWhGenerated }}</div></div>
<div class="flex justify-between border-b py-2"><div class="font-semibold">Last 12M Cap. Factor:</div><div>{{ $project->Last12MonthCapacityFactor }}</div></div></div>
</div>
<div id="interconnection_info" class="tabcontent" >
    <h2 class="text-2xl font-bold mb-4">Interconnection Info</h2>
    <div class="bg-white p-6 rounded shadow grid grid-cols-1 sm:grid-cols-2 gap-6"><div class="flex justify-between border-b py-2"><div class="font-semibold">Point of Interconnection:</div><div>{{ $project->PointOfInterconnection }}</div></div>
<div class="flex justify-between border-b py-2"><div class="font-semibold">Primary Voltage:</div><div>{{ $project->InterconnectionPrimaryVoltage }}</div></div>
<div class="flex justify-between border-b py-2"><div class="font-semibold">Secondary Voltage:</div><div>{{ $project->InterconnectionSecondaryVoltage }}</div></div>
<div class="flex justify-between border-b py-2"><div class="font-semibold">Owner:</div><div>{{ $project->InterconnectionENVOwner }}</div></div></div>
</div>
<div id="epc_info" class="tabcontent" >
    <h2 class="text-2xl font-bold mb-4">Epc Info</h2>
    <div class="bg-white p-6 rounded shadow grid grid-cols-1 sm:grid-cols-2 gap-6"><div class="flex justify-between border-b py-2"><div class="font-semibold">HQ Address:</div><div>{{ $project->EPCHQAddress }}</div></div>
<div class="flex justify-between border-b py-2"><div class="font-semibold">City:</div><div>{{ $project->EPCHQCity }}</div></div>
<div class="flex justify-between border-b py-2"><div class="font-semibold">Zip Code:</div><div>{{ $project->EPCHQZipCode }}</div></div>
<div class="flex justify-between border-b py-2"><div class="font-semibold">State:</div><div>{{ $project->EPCHQState }}</div></div>
<div class="flex justify-between border-b py-2"><div class="font-semibold">Country:</div><div>{{ $project->EPCHQCountry }}</div></div>
<div class="flex justify-between border-b py-2"><div class="font-semibold">Website:</div><div>{{ $project->EPCWebsite }}</div></div>
<div class="flex justify-between border-b py-2"><div class="font-semibold">Email:</div><div>{{ $project->EPCGeneralEmail }}</div></div></div>
</div>
<div id="other_info" class="tabcontent" >
    <h2 class="text-2xl font-bold mb-4">Other Info</h2>
    <div class="bg-white p-6 rounded shadow grid grid-cols-1 sm:grid-cols-2 gap-6"><div class="flex justify-between border-b py-2"><div class="font-semibold">NERC Region:</div><div>{{ $project->NERC_Region }}</div></div>
<div class="flex justify-between border-b py-2"><div class="font-semibold">ISO Territory:</div><div>{{ $project->ISOTerritory }}</div></div>
<div class="flex justify-between border-b py-2"><div class="font-semibold">Country:</div><div>{{ $project->Country }}</div></div>
<div class="flex justify-between border-b py-2"><div class="font-semibold">Dockets:</div><div>{{ $project->Dockets }}</div></div>
<div class="flex justify-between border-b py-2"><div class="font-semibold">Queue Number:</div><div>{{ $project->QueueNumber }}</div></div></div>
</div>

</div>


<script>
    function openTab(evt, tabId) {
        let i, tabcontent, tablinks;
        tabcontent = document.getElementsByClassName("tabcontent");
        for (i = 0; i < tabcontent.length; i++) {
            tabcontent[i].style.display = "none";
        }
        tablinks = document.getElementsByClassName("tablinks");
        for (i = 0; i < tablinks.length; i++) {
            tablinks[i].classList.remove("active");
        }
        document.getElementById(tabId).style.display = "block";
        evt.currentTarget.classList.add("active");
    }
</script>

<style>
.tab { overflow: hidden; border-bottom: 1px solid #ccc; }
.tab button {
  background-color: inherit;
  border: none;
  outline: none;
  padding: 10px 20px;
  transition: 0.3s;
  font-weight: bold;
}
.tab button.active {
  border-bottom: 2px solid blue;
  color: blue;
}
.tabcontent {
  display: none;
  padding: 6px 0;
}
</style>
@endsection
