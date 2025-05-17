<?php
    {
        // Load the project along with its related contacts
        $this->project = SolarProject::with(['projectContacts', 'keyCompanyContacts'])->findOrFail($id);
    }

    public function render()
    {
        return view('livewire.solar-project-viewer');
    }