// Coupons Panel JavaScript

class CouponsPanel {
    constructor() {
        this.currentPage = 1;
        this.itemsPerPage = 10;
        this.currentFilters = {
            search: '',
            type: '',
            status: ''
        };
        
        this.init();
    }

    init() {
        this.bindEvents();
        this.loadCouponsData();
    }

    bindEvents() {
        const searchInput = document.querySelector('input[placeholder="Search coupons..."]');
        if (searchInput) {
            searchInput.addEventListener('input', (e) => {
                this.currentFilters.search = e.target.value;
                this.debounce(() => this.searchCoupons(), 300)();
            });
        }

        const typeFilter = document.getElementById('coupon-type-filter');
        if (typeFilter) {
            typeFilter.addEventListener('change', (e) => {
                this.currentFilters.type = e.target.value;
            });
        }

        const statusFilter = document.getElementById('coupon-status-filter');
        if (statusFilter) {
            statusFilter.addEventListener('change', (e) => {
                this.currentFilters.status = e.target.value;
            });
        }

        const filterBtn = document.getElementById('filter-coupons-btn');
        if (filterBtn) {
            filterBtn.addEventListener('click', () => this.filterCoupons());
        }

        const newCouponBtn = document.getElementById('new-coupon-btn');
        if (newCouponBtn) {
            newCouponBtn.addEventListener('click', () => this.openCouponModal());
        }

        const refreshBtn = document.getElementById('refresh-coupons');
        if (refreshBtn) {
            refreshBtn.addEventListener('click', () => this.loadCouponsData());
        }
    }

    async loadCouponsData() {
        try {
            const response = await fetch('/admin/coupons-data', { headers: { 'Accept': 'application/json' } });
            const data = await response.json();
            this.updateStats(data.stats || {});
            this.updateCouponsTable(data.coupons || []);
        } catch (error) {
            console.error('Error loading coupons data:', error);
            this.showError('Failed to load coupons data');
        }
    }

    async searchCoupons() {
        try {
            const response = await fetch(`/admin/search-coupons?q=${encodeURIComponent(this.currentFilters.search)}`, { headers: { 'Accept': 'application/json' } });
            const data = await response.json();
            this.updateCouponsTable(data.coupons || []);
        } catch (error) {
            console.error('Error searching coupons:', error);
            this.showError('Failed to search coupons');
        }
    }

    async filterCoupons() {
        try {
            const response = await fetch(`/admin/filter-coupons?type=${encodeURIComponent(this.currentFilters.type)}&status=${encodeURIComponent(this.currentFilters.status)}`, { headers: { 'Accept': 'application/json' } });
            const data = await response.json();
            this.updateCouponsTable(data.coupons || []);
        } catch (error) {
            console.error('Error filtering coupons:', error);
            this.showError('Failed to filter coupons');
        }
    }

    updateStats(stats) {
        const totalCoupons = document.querySelector('.bg-blue-100 + div p.text-2xl');
        const activeCoupons = document.querySelector('.bg-green-100 + div p.text-2xl');
        const totalUsage = document.querySelector('.bg-purple-100 + div p.text-2xl');
        const totalSavings = document.querySelector('.bg-yellow-100 + div p.text-2xl');

        if (totalCoupons) totalCoupons.textContent = stats.totalCoupons || 0;
        if (activeCoupons) activeCoupons.textContent = stats.activeCoupons || 0;
        if (totalUsage) totalUsage.textContent = stats.totalUsage || 0;
        if (totalSavings) totalSavings.textContent = `₱${stats.totalSavings || 0}`;
    }

    updateCouponsTable(coupons) {
        const tbody = document.querySelector('tbody');
        if (!tbody) return;

        if (coupons.length === 0) {
            tbody.innerHTML = `
                <tr class="text-center">
                    <td colspan="7" class="px-6 py-12 text-gray-500">
                        <i class="fas fa-ticket-alt text-4xl mb-4 block"></i>
                        <p class="text-lg font-medium">No coupons found</p>
                        <p class="text-sm">Create your first coupon to get started</p>
                    </td>
                </tr>
            `;
            return;
        }

        tbody.innerHTML = coupons.map(coupon => `
            <tr class="hover:bg-gray-50 transition-colors">
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 h-10 w-10">
                            <div class="h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center">
                                <i class="fas fa-ticket-alt text-blue-600"></i>
                            </div>
                        </div>
                        <div class="ml-4">
                            <div class="text-sm font-medium text-gray-900">${coupon.code}</div>
                            <div class="text-sm text-gray-500">${coupon.description || ''}</div>
                        </div>
                    </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <span class="type-${coupon.type}">${coupon.type}</span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                    ${coupon.formatted_value || (coupon.type === 'percentage' ? `${coupon.value}%` : `₱${coupon.value}`)}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                    ${coupon.used_count || 0} / ${coupon.max_usage || '∞'}
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <span class="status-${coupon.status}">${coupon.status}</span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                    ${coupon.expiry_date || 'No expiry'}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                    <div class="flex space-x-2">
                        <button class="text-blue-600 hover:text-blue-900" onclick="couponsPanel.editCoupon('${coupon.id}')" title="Edit">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="text-${coupon.status === 'active' ? 'yellow' : 'green'}-600 hover:text-${coupon.status === 'active' ? 'yellow' : 'green'}-900" onclick="couponsPanel.toggleCouponStatus('${coupon.id}', '${coupon.status === 'active' ? 'inactive' : 'active'}')" title="${coupon.status === 'active' ? 'Disable' : 'Enable'}">
                            <i class="fas fa-power-off"></i>
                        </button>
                        <button class="text-red-600 hover:text-red-900" onclick="couponsPanel.deleteCoupon('${coupon.id}')" title="Delete">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </td>
            </tr>
        `).join('');
    }

    openCouponModal(coupon = null) {
        const modal = document.getElementById('coupon-modal');
        if (!modal) return;
        modal.classList.remove('hidden');
        document.getElementById('coupon-modal-title').textContent = coupon ? 'Edit Coupon' : 'New Coupon';
        document.getElementById('coupon-id').value = coupon?.id || '';
        document.getElementById('coupon-code').value = coupon?.code || '';
        document.getElementById('coupon-type').value = coupon?.type || 'percentage';
        document.getElementById('coupon-value').value = coupon?.value || '';
        document.getElementById('coupon-description').value = coupon?.description || '';
        document.getElementById('coupon-max-usage').value = coupon?.max_usage || '';
        document.getElementById('coupon-min-amount').value = coupon?.min_order_amount || '';
        document.getElementById('coupon-expiry').value = coupon?.expiry_date_raw || '';
        document.getElementById('coupon-status').value = coupon?.status || 'active';

        const close = () => modal.classList.add('hidden');
        const closeBtn = document.getElementById('coupon-modal-close');
        const cancelBtn = document.getElementById('coupon-modal-cancel');
        if (closeBtn) closeBtn.onclick = close;
        if (cancelBtn) cancelBtn.onclick = close;

        const saveBtn = document.getElementById('coupon-modal-save');
        if (saveBtn) {
            saveBtn.onclick = async () => {
                const payload = {
                    code: document.getElementById('coupon-code').value.trim(),
                    type: document.getElementById('coupon-type').value,
                    value: parseFloat(document.getElementById('coupon-value').value),
                    description: document.getElementById('coupon-description').value.trim() || null,
                    max_usage: document.getElementById('coupon-max-usage').value ? parseInt(document.getElementById('coupon-max-usage').value, 10) : null,
                    min_order_amount: document.getElementById('coupon-min-amount').value ? parseFloat(document.getElementById('coupon-min-amount').value) : null,
                    expiry_date: document.getElementById('coupon-expiry').value || null,
                    status: document.getElementById('coupon-status').value
                };

                if (!payload.code || !payload.type || isNaN(payload.value)) {
                    alert('Please provide valid code, type and value');
                    return;
                }

                try {
                    const id = document.getElementById('coupon-id').value;
                    if (id) {
                        await this.updateCoupon(id, payload);
                    } else {
                        await this.createCoupon(payload);
                    }
                    close();
                    this.loadCouponsData();
                } catch (e) {
                    console.error(e);
                    this.showError('Failed to save coupon');
                }
            };
        }
    }

    async createCoupon(payload) {
        try {
            const res = await fetch('/admin/coupon', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify(payload)
            });

            if (!res.ok) {
                // Try to read JSON error, else text (HTML error page)
                let err;
                try { err = await res.json(); } catch { err = await res.text(); }
                throw new Error(typeof err === 'string' ? err : (err.message || JSON.stringify(err)));
            }

            const data = await res.json();
            alert('Coupon saved');
            await this.loadCouponsData();
            return data;
        } catch (e) {
            console.error(e);
            this.showError('Failed to create coupon');
        }
    }

    async updateCoupon(couponId, payload) {
        try {
            const res = await fetch(`/admin/coupon/${couponId}`, {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify(payload)
            });

            if (!res.ok) {
                let err;
                try { err = await res.json(); } catch { err = await res.text(); }
                throw new Error(typeof err === 'string' ? err : (err.message || JSON.stringify(err)));
            }

            await this.loadCouponsData();
        } catch (e) {
            console.error(e);
            this.showError('Failed to update coupon');
        }
    }

    async editCoupon(couponId) {
        try {
            const res = await fetch(`/admin/coupon/${couponId}`, { headers: { 'Accept': 'application/json' } });
            const data = await res.json();
            if (res.ok && data.coupon) {
                const c = data.coupon;
                c.expiry_date_raw = c.expiry_date && c.expiry_date.length === 10 ? c.expiry_date : '';
                this.openCouponModal(c);
            } else {
                this.showError('Coupon not found');
            }
        } catch (e) {
            console.error(e);
            this.showError('Failed to load coupon');
        }
    }

    async deleteCoupon(couponId) {
        if (!confirm('Are you sure you want to delete this coupon?')) return;
        try {
            const res = await fetch(`/admin/coupon/${couponId}`, {
                method: 'DELETE',
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            });

            if (!res.ok) {
                let err;
                try { err = await res.json(); } catch { err = await res.text(); }
                throw new Error(typeof err === 'string' ? err : (err.message || JSON.stringify(err)));
            }

            await this.loadCouponsData();
        } catch (e) {
            console.error(e);
            this.showError('Failed to delete coupon');
        }
    }

    async toggleCouponStatus(couponId, newStatus) {
        try {
            const res = await fetch(`/admin/coupon/${couponId}`, {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ status: newStatus })
            });

            if (!res.ok) {
                let err;
                try { err = await res.json(); } catch { err = await res.text(); }
                throw new Error(typeof err === 'string' ? err : (err.message || JSON.stringify(err)));
            }

            await this.loadCouponsData();
        } catch (e) {
            console.error(e);
            this.showError('Failed to update status');
        }
    }

    showError(message) {
        console.error(message);
        alert(message);
    }

    debounce(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    }
}

document.addEventListener('DOMContentLoaded', () => {
    window.couponsPanel = new CouponsPanel();
});
