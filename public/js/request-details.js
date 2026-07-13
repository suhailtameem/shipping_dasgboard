(function () {
    // ---- Data from window.expenseDetailsData ----
    const data = window.expenseDetailsData || { expenseTypes: [], currencies: [], lang: 'en' };
    const expenseTypes = data.expenseTypes;
    const currencies = data.currencies;
    const packagesTypes = data.packagesTypes || [];
    const lang = data.lang;

    // ---- Expense Row Template ----
    function buildExpenseRow() {
        const options = expenseTypes.map(et =>
            `<option value="${et.id}">${et.name}</option>`
        ).join('');
        
        const orderCurrency = data.orderCurrency || 'USD';

        return `
        <tr class="expense-row">
            <td class="ps-3">
                <div class="d-flex align-items-center gap-2">
                    <select name="expense_type_id[]" class="form-select exp-type-select">
                        <option value="">-- Select --</option>
                        ${options}
                    </select>
                    <button type="button" class="btn btn-sm btn-link text-primary p-0 open-type-modal" title="Quick Select">
                        <i class="bi bi-grid-3x3-gap-fill"></i>
                    </button>
                </div>
            </td>
            <td>
                <input type="number" name="amount[]" class="form-control exp-amount" value="0" step="0.01" min="0">
            </td>
            <td>
                <span class="badge bg-soft-secondary border-0">${orderCurrency}</span>
            </td>
            <td>
                <input type="text" name="notes[]" class="form-control" placeholder="Optional">
            </td>
            <td class="text-end pe-2">
                <button type="button" class="btn btn-sm btn-link text-danger p-0 delExpRow">
                    <i class="bi bi-dash-circle-fill"></i>
                </button>
            </td>
        </tr>`;
    }

    // ---- Package Row Template ----
    function buildPackageRow() {
        const options = packagesTypes.map(pt =>
            `<option value="${pt.value}">${pt.name}</option>`
        ).join('');

        return `
        <tr>
            <td class="ps-4">
                <input type="text" name="name[]" class="form-control border-0 bg-transparent p-0" placeholder="Item Name">
            </td>
            <td>
                <select name="type[]" class="form-select border-0 bg-transparent py-0 ps-0">
                    ${options}
                </select>
            </td>
            <td>
                <input type="number" name="weight[]" class="form-control border-0 bg-transparent p-0 text-center Weights" value="0" min="0" step="0.01">
            </td>
            <td class="text-end pe-3">
                <button type="button" class="btn btn-sm btn-link text-danger p-0 delContentRow"><i class="bi bi-dash-circle-fill"></i></button>
            </td>
        </tr>`;
    }

    // ---- Add Expense row ----
    const addExpenseRowBtn = document.getElementById('addExpenseRow');
    if (addExpenseRowBtn) {
        addExpenseRowBtn.addEventListener('click', function () {
            const tbody = document.getElementById('expensesTbody');
            if (tbody) {
                tbody.insertAdjacentHTML('beforeend', buildExpenseRow());
                recalcTotal();
            }
        });
    }

    // ---- Add Package row ----
    const addContentRowBtn = document.getElementById('addContentRow');
    if (addContentRowBtn) {
        addContentRowBtn.addEventListener('click', function () {
            const tbody = document.getElementById('contentTbodyModal');
            if (tbody) {
                tbody.insertAdjacentHTML('beforeend', buildPackageRow());
            }
        });
    }

    // ---- Delete Expense row (delegated) ----
    const expensesTbody = document.getElementById('expensesTbody');
    if (expensesTbody) {
        expensesTbody.addEventListener('click', function (e) {
            const btn = e.target.closest('.delExpRow');
            if (btn) {
                const rows = document.querySelectorAll('#expensesTbody .expense-row');
                if (rows.length > 1) {
                    btn.closest('tr').remove();
                } else {
                    const typeSelect = btn.closest('tr').querySelector('.exp-type-select');
                    const amountInput = btn.closest('tr').querySelector('.exp-amount');
                    if (typeSelect) typeSelect.value = '';
                    if (amountInput) amountInput.value = 0;
                }
                recalcTotal();
            }
        });
        expensesTbody.addEventListener('input', recalcTotal);
    }

    // ---- Delete Package row (delegated) ----
    const contentTbodyModal = document.getElementById('contentTbodyModal');
    if (contentTbodyModal) {
        contentTbodyModal.addEventListener('click', function (e) {
            const btn = e.target.closest('.delContentRow');
            if (btn) {
                const rows = document.querySelectorAll('#contentTbodyModal tr');
                if (rows.length > 1) {
                    btn.closest('tr').remove();
                } else {
                    const nameInput = btn.closest('tr').querySelector('input[name="name[]"]');
                    const weightInput = btn.closest('tr').querySelector('input[name="weight[]"]');
                    if (nameInput) nameInput.value = '';
                    if (weightInput) weightInput.value = 0;
                }
            }
        });
    }

    function recalcTotal() {
        const weights = Array.from(document.querySelectorAll('input[name="weight[]"], .Weights')).map(inp => inp.value);
        // Also check for individual weight inputs in the main list
        const mainWeights = Array.from(document.querySelectorAll('input[name="weight"]')).map(inp => inp.value);
        const allWeights = [...weights, ...mainWeights];

        const expenses = Array.from(document.querySelectorAll('.exp-amount, input[name^="amount_"]')).map(inp => inp.value);

        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;

        fetch(data.calcUrl, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify({
                rid: data.rid,
                currency_id: data.currency_id,
                weights: allWeights,
                expenses: expenses
            })
        })
        .then(response => response.json())
        .then(result => {
            // Update Total Weight
            const weightDisplays = document.querySelectorAll('.TotalWeight');
            weightDisplays.forEach(el => {
                el.textContent = result.totalWeight.toFixed(2);
            });

            // Update Total Price
            const priceDisplays = document.querySelectorAll('.TotalPrice');
            priceDisplays.forEach(el => {
                el.textContent = new Intl.NumberFormat().format(result.finalTotal.toFixed(2));
            });
        })
        .catch(err => console.error('Error calculating totals:', err));
    }

    // Add event listeners to all existing weight and expense inputs
    document.addEventListener('input', function(e) {
        if (e.target.matches('.Weights, .exp-amount, input[name="weight"], input[name^="amount_"]')) {
            recalcTotal();
        }
    });

    // ---- Quick-select modal ----
    let activeSelect = null;
    const expenseTypesModal = document.getElementById('expenseTypesModal');
    let expModal = null;
    if (expenseTypesModal && typeof bootstrap !== 'undefined') {
        expModal = new bootstrap.Modal(expenseTypesModal);
    }

    if (expensesTbody) {
        expensesTbody.addEventListener('click', function (e) {
            const btn = e.target.closest('.open-type-modal');
            if (btn) {
                activeSelect = btn.closest('tr').querySelector('.exp-type-select');
                if (expModal) expModal.show();
            }
        });
    }

    const expTypeGrid = document.getElementById('expTypeGrid');
    if (expTypeGrid) {
        expTypeGrid.addEventListener('click', function (e) {
            const btn = e.target.closest('.exp-type-pick');
            if (btn && activeSelect) {
                activeSelect.value = btn.dataset.id;
                activeSelect = null;
                if (expModal) expModal.hide();
            }
        });

        // ---- Hover effect for type buttons ----
        expTypeGrid.addEventListener('mouseover', function (e) {
            const btn = e.target.closest('.exp-type-pick');
            if (btn) btn.style.background = 'rgba(10,132,255,0.15)';
        });
        expTypeGrid.addEventListener('mouseout', function (e) {
            const btn = e.target.closest('.exp-type-pick');
            if (btn) btn.style.background = 'rgba(255,255,255,0.05)';
        });
    }

    // ---- Select all checkboxes ----
    const selectAllCheckbox = document.getElementById('selectAllExpenses');
    if (selectAllCheckbox) {
        selectAllCheckbox.addEventListener('change', function () {
            const checkboxes = document.querySelectorAll('.expense-checkbox');
            checkboxes.forEach(cb => {
                cb.checked = selectAllCheckbox.checked;
            });
        });
    }

    // Initial total calc
    recalcTotal();

    // Expose recalcTotal to window for external use
    window.recalcTotal = recalcTotal;
})();
