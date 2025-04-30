document.addEventListener('DOMContentLoaded', () => {
    const BASE_URL = window.BASE_URL || 'http://localhost/debt-management-system';
    setupSidebar();

    if (document.getElementById('addDebtForm')) {
        // loadDebts();
        document.getElementById('addDebtForm').addEventListener('submit', async (e) => {
            e.preventDefault();
            const formData = new FormData(e.target);
            try {
                const response = await fetch(`${BASE_URL}/api.php?action=addDebt`, {
                    method: 'POST',
                    body: formData
                });
                const result = await response.json();
                document.getElementById('debtError').textContent = result.message;
                if (result.success) {
                    e.target.reset();
                    // loadDebts();
                }
            } catch (error) {
                console.error('Error adding debt:', error);
                document.getElementById('debtError').textContent = 'เกิดข้อผิดพลาดในการบันทึกหนี้';
            }
        });
    }

    if (document.getElementById('addPaymentForm')) {
        loadDebtsForPayment();
        document.getElementById('addPaymentForm').addEventListener('submit', async (e) => {
            e.preventDefault();
            const formData = new FormData(e.target);
            try {
                const response = await fetch(`${BASE_URL}/api.php?action=addPayment`, {
                    method: 'POST',
                    body: formData
                });
                const result = await response.json();
                document.getElementById('paymentError').textContent = result.message;
                if (result.success) {
                    e.target.reset();
                }
            } catch (error) {
                console.error('Error adding payment:', error);
                document.getElementById('paymentError').textContent = 'เกิดข้อผิดพลาดในการบันทึกการชำระ';
            }
        });

        document.getElementById('payment_type').addEventListener('change', (e) => {
            const fields = document.getElementById('principal_interest_fields');
            fields.classList.toggle('hidden', e.target.value !== 'both');
        });
    }

    if (document.getElementById('debtTable')) {
        loadDebts();
    }

    if (document.getElementById('paymentTable')) {
        loadPayments();
    }

    if (document.getElementById('dashboard')) {
        loadDashboard();
    }

    async function loadDebts() {
        try {
            const response = await fetch(`${BASE_URL}/api.php?action=getDebts`);
            const result = await response.json();
            if (result.success) {
                const tbody = document.querySelector('#debtTable tbody');
                tbody.innerHTML = '';
                result.data.forEach(debt => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td class="p-3">${debt.debtor_name}</td>
                        <td class="p-3">${parseFloat(debt.total_amount).toFixed(2)}</td>
                        <td class="p-3">${parseFloat(debt.interest_rate).toFixed(2)}</td>
                        <td class="p-3">${debt.start_date}</td>
                        <td class="p-3 debt-remaining" data-debt-id="${debt.debt_id}">กำลังโหลด...</td>
                        <td class="p-3"><a href="${BASE_URL}/views/add_payment.php?debt_id=${debt.debt_id}" class="text-blue-500 hover:underline">ชำระ</a></td>
                    `;
                    tbody.appendChild(row);
                    loadRemainingDebt(debt.debt_id);
                });
            }
        } catch (error) {
            console.error('Error loading debts:', error);
        }
    }

    async function loadRemainingDebt(debtId) {
        try {
            const response = await fetch(`${BASE_URL}/api.php?action=remainingDebt&debt_id=${debtId}`);
            const result = await response.json();
            if (result.success) {
                const cell = document.querySelector(`.debt-remaining[data-debt-id="${debtId}"]`);
                cell.textContent = parseFloat(result.data.remaining).toFixed(2);
            }
        } catch (error) {
            console.error('Error loading remaining debt:', error);
        }
    }

    async function loadPayments() {
        try {
            const response = await fetch(`${BASE_URL}/api.php?action=getPayments`);
            const result = await response.json();
            if (result.success) {
                const tbody = document.querySelector('#paymentTable tbody');
                tbody.innerHTML = '';
                result.data.forEach(payment => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td class="p-3">${payment.payment_date}</td>
                        <td class="p-3">${parseFloat(payment.payment_amount).toFixed(2)}</td>
                        <td class="p-3">${payment.principal_amount ? parseFloat(payment.principal_amount).toFixed(2) : '-'}</td>
                        <td class="p-3">${payment.interest_amount ? parseFloat(payment.interest_amount).toFixed(2) : '-'}</td>
                        <td class="p-3">${payment.payment_type === 'principal' ? 'เงินต้น' : payment.payment_type === 'interest' ? 'ดอกเบี้ย' : 'ทั้งสอง'}</td>
                        <td class="p-3">${payment.debtor_name}</td>
                    `;
                    tbody.appendChild(row);
                });
            }
        } catch (error) {
            console.error('Error loading payments:', error);
        }
    }

    async function loadDebtsForPayment() {
        try {
            const response = await fetch(`${BASE_URL}/api.php?action=getDebts`);
            const result = await response.json();
            if (result.success) {
                const select = document.getElementById('debt_id');
                select.innerHTML = '<option value="">-- เลือกหนี้ --</option>';
                result.data.forEach(debt => {
                    const option = document.createElement('option');
                    option.value = debt.debt_id;
                    option.textContent = `${debt.debtor_name} - ${parseFloat(debt.total_amount).toFixed(2)} บาท`;
                    select.appendChild(option);
                });
            }
        } catch (error) {
            console.error('Error loading debts for payment:', error);
        }
    }

    async function loadDashboard() {
        try {
            const response = await fetch(`${BASE_URL}/api.php?action=getDashboard`);
            const result = await response.json();
            if (result.success) {
                document.getElementById('total-debt').textContent = parseFloat(result.data.total_debt || 0).toFixed(2) + ' บาท';
                document.getElementById('debt-count').textContent = result.data.debt_count || 0 + ' รายการ';
                document.getElementById('paid-off-count').textContent = result.data.paid_off_count || 0 + ' รายการ';
                const tbody = document.querySelector('#recent-payments');
                tbody.innerHTML = '';
                result.data.recent_payments.forEach(payment => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td class="p-3">${payment.payment_date}</td>
                        <td class="p-3">${parseFloat(payment.payment_amount).toFixed(2)}</td>
                        <td class="p-3">${payment.payment_type === 'principal' ? 'เงินต้น' : payment.payment_type === 'interest' ? 'ดอกเบี้ย' : 'ทั้งสอง'}</td>
                        <td class="p-3">${payment.debtor_name}</td>
                    `;
                    tbody.appendChild(row);
                });
            }
        } catch (error) {
            console.error('Error loading dashboard:', error);
        }
    }

    function setupSidebar() {
        const sidebar = document.getElementById('sidebar');
        const toggleButton = document.getElementById('toggle-sidebar');
        const sidebarLinks = document.querySelectorAll('.sidebar-link');

        toggleButton.addEventListener('click', () => {
            sidebar.classList.toggle('hidden');
        });

        sidebarLinks.forEach(link => {
            link.addEventListener('click', (e) => {
                e.preventDefault();
                sidebarLinks.forEach(l => l.classList.remove('bg-gray-700'));
                link.classList.add('bg-gray-700');
                window.location.href = link.href;
            });

            if (link.dataset.section === document.body.dataset.section) {
                link.classList.add('bg-gray-700');
            }
        });
    }
});