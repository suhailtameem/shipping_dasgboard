document.addEventListener('DOMContentLoaded', function() {
    const data = window.createRequestData;

    // --- 1. Shipping Type Selection & Destination Update ---
    const shippingTypeRadios = document.querySelectorAll('.shipping-type-radio');
    
    function updateDestinations(shippingType) {
        const fromSelect = document.getElementById('fromCountry');
        const toSelect = document.getElementById('toCountry');
        
        let destinations = [];
        
        switch (shippingType) {
            case '1':
                destinations = data.airDests;
                break;
            case '2':
                destinations = data.seaDests;
                break;
            case '3':
                destinations = data.landDests;
                break;
        }

        // Clear and populate selects
        fromSelect.innerHTML = '';
        toSelect.innerHTML = '';

        destinations.forEach(dest => {
            const optionFrom = document.createElement('option');
            const optionTo = document.createElement('option');
            
            optionFrom.value = dest.id;
            optionFrom.textContent = data.lang === 'Ar' ? dest.ar : dest.destinations;
            
            optionTo.value = dest.id;
            optionTo.textContent = data.lang === 'Ar' ? dest.ar : dest.destinations;
            
            fromSelect.appendChild(optionFrom);
            toSelect.appendChild(optionTo);
        });
    }

    shippingTypeRadios.forEach(radio => {
        radio.addEventListener('change', function() {
            updateDestinations(this.value);
        });
    });

    // Initialize destinations
    const firstChecked = document.querySelector('.shipping-type-radio:checked');
    if (firstChecked) {
        updateDestinations(firstChecked.value);
    }

    // --- 2. Container Type Checkboxes & Sub-lists ---
    const containerCheckboxes = document.querySelectorAll('.container-type-checkbox');
    
    containerCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const subListId = 'subList-' + this.dataset.id;
            const subListWrapper = document.getElementById(subListId);
            
            if (subListWrapper) {
                subListWrapper.style.display = this.checked ? 'block' : 'none';
                
                // Uncheck sub-checkboxes if parent is unchecked
                if (!this.checked) {
                    const subCheckboxes = subListWrapper.querySelectorAll('.sub-list-checkbox');
                    subCheckboxes.forEach(sub => {
                        sub.checked = false;
                    });
                }
            }
        });
    });

    // --- 3. Service Type Checkboxes & Sub-lists ---
    const serviceCheckboxes = document.querySelectorAll('.service-type-checkbox');
    
    serviceCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const subListId = 'serviceSubList-' + this.dataset.id;
            const subListWrapper = document.getElementById(subListId);
            
            if (subListWrapper) {
                subListWrapper.style.display = this.checked ? 'block' : 'none';
                
                // Uncheck sub-checkboxes if parent is unchecked
                if (!this.checked) {
                    const subCheckboxes = subListWrapper.querySelectorAll('.sub-list-checkbox');
                    subCheckboxes.forEach(sub => {
                        sub.checked = false;
                    });
                }
            }
        });
    });

    // --- 4. Form Submission ---
    const submitBtns = document.querySelectorAll('#submitRequestBtn, #submitRequestBtnBottom');
    const form = document.getElementById('createRequestForm');

    submitBtns.forEach(btn => {
        btn.addEventListener('click', function(e) {
            // First, set container type (for compatibility with existing code)
            const firstContainerChecked = document.querySelector('.container-type-checkbox:checked');
            if (firstContainerChecked) {
                const hiddenInput = document.createElement('input');
                hiddenInput.type = 'hidden';
                hiddenInput.name = 'containerType';
                hiddenInput.value = firstContainerChecked.value;
                form.appendChild(hiddenInput);
            }

            // Set service type (for compatibility)
            const firstServiceChecked = document.querySelector('.service-type-checkbox:checked');
            if (firstServiceChecked) {
                const hiddenInput = document.createElement('input');
                hiddenInput.type = 'hidden';
                hiddenInput.name = 'serviceType';
                hiddenInput.value = firstServiceChecked.value;
                form.appendChild(hiddenInput);
            }

            // Submit form
            form.submit();
        });
    });
});
