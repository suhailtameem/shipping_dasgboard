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

    function showError(message) {
        const alertBox = document.getElementById('validationAlert');
        const msgSpan = document.getElementById('validationMsg');
        if (alertBox && msgSpan) {
            msgSpan.textContent = message;
            alertBox.style.display = 'block';
            alertBox.scrollIntoView({ behavior: 'smooth', block: 'center' });
        } else {
            alert(message);
        }
    }

    function hideError() {
        const alertBox = document.getElementById('validationAlert');
        if (alertBox) {
            alertBox.style.display = 'none';
        }
    }

    if (form) {
        form.addEventListener('change', hideError);
    }

    submitBtns.forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();

            // 1. Validate Shipping Type
            const shippingType = document.querySelector('input[name="shippType"]:checked');
            if (!shippingType) {
                showError(data.translations.selectShippingType);
                return;
            }

            // 2. Validate Container Type
            const checkedContainers = document.querySelectorAll('.container-type-checkbox:checked');
            if (checkedContainers.length === 0) {
                showError(data.translations.selectContainerType);
                return;
            }

            // Validate sub-containers for each selected container type
            let containerSubValid = true;
            checkedContainers.forEach(cb => {
                const subListWrapper = document.getElementById('subList-' + cb.dataset.id);
                if (subListWrapper) {
                    const checkedSubs = subListWrapper.querySelectorAll('.sub-list-checkbox[name="subContainerType[]"]:checked');
                    if (checkedSubs.length === 0) {
                        containerSubValid = false;
                    }
                }
            });
            if (!containerSubValid) {
                showError(data.translations.selectSubContainerType);
                return;
            }

            // 3. Validate Service Type (Additional Services optional, but if selected, sub-service is required)
            const checkedServices = document.querySelectorAll('.service-type-checkbox:checked');
            let serviceSubValid = true;
            checkedServices.forEach(cb => {
                const subListWrapper = document.getElementById('serviceSubList-' + cb.dataset.id);
                if (subListWrapper) {
                    const checkedSubs = subListWrapper.querySelectorAll('.sub-list-checkbox[name="subServiceType[]"]:checked');
                    if (checkedSubs.length === 0) {
                        serviceSubValid = false;
                    }
                }
            });
            if (!serviceSubValid) {
                showError(data.translations.selectSubServiceType);
                return;
            }

            // Clear any alerts
            hideError();

            // Remove any previously appended hidden inputs to avoid duplicates
            form.querySelectorAll('input.dynamic-selection').forEach(el => el.remove());

            // Collect ALL checked container type values
            checkedContainers.forEach(cb => {
                const hidden = document.createElement('input');
                hidden.type  = 'hidden';
                hidden.name  = 'containerType[]';
                hidden.value = cb.value;
                hidden.className = 'dynamic-selection';
                form.appendChild(hidden);
            });

            // Collect ALL checked sub-container type IDs
            document.querySelectorAll('.sub-list-checkbox[name="subContainerType[]"]:checked').forEach(cb => {
                const hidden = document.createElement('input');
                hidden.type  = 'hidden';
                hidden.name  = 'containerType[]';
                hidden.value = cb.value;
                hidden.className = 'dynamic-selection';
                form.appendChild(hidden);
            });

            // Collect ALL checked service type values
            checkedServices.forEach(cb => {
                const hidden = document.createElement('input');
                hidden.type  = 'hidden';
                hidden.name  = 'serviceType[]';
                hidden.value = cb.value;
                hidden.className = 'dynamic-selection';
                form.appendChild(hidden);
            });

            // Collect ALL checked sub-service type IDs
            document.querySelectorAll('.sub-list-checkbox[name="subServiceType[]"]:checked').forEach(cb => {
                const hidden = document.createElement('input');
                hidden.type  = 'hidden';
                hidden.name  = 'serviceType[]';
                hidden.value = cb.value;
                hidden.className = 'dynamic-selection';
                form.appendChild(hidden);
            });

            // Submit form
            form.submit();
        });
    });
});
