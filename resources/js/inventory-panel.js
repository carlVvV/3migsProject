class InventoryPanelManager {
    constructor() {
        this.currentPage = 1;
        this.itemsPerPage = 10;
        this.currentFilter = 'all';
        this.currentSearch = '';
        this.variantCounter = 0;
        this.init();
    }

    init() {
        this.loadInventoryData();
        this.setupEventListeners();
        this.loadCategories();
    }

    setupEventListeners() {
        // Search functionality
        const searchInput = document.getElementById('inventory-search');
        if (searchInput) {
            searchInput.addEventListener('input', (e) => {
                this.currentSearch = e.target.value;
                this.searchInventory();
            });
        }

        // Filter functionality
        const filterSelect = document.getElementById('inventory-status-filter');
        if (filterSelect) {
            filterSelect.addEventListener('change', (e) => {
                this.currentFilter = e.target.value;
                this.filterByStatus();
            });
        }

        // Refresh button removed

        // Select all checkbox
        const selectAllCheckbox = document.getElementById('select-all-inventory');
        if (selectAllCheckbox) {
            selectAllCheckbox.addEventListener('change', (e) => {
                const checkboxes = document.querySelectorAll('.inventory-checkbox');
                checkboxes.forEach(checkbox => {
                    checkbox.checked = e.target.checked;
                });
            });
        }



        // New item button
        const newItemBtn = document.getElementById('inventory-new-item');
        if (newItemBtn) {
            newItemBtn.addEventListener('click', () => this.openNewItemModal());
        }

        // Export button
        const exportBtn = document.getElementById('inventory-export');
        if (exportBtn) {
            exportBtn.addEventListener('click', () => this.exportInventory());
        }

        // Modal close buttons
        const cancelNewItemBtn = document.getElementById('cancel-new-item');
        if (cancelNewItemBtn) {
            cancelNewItemBtn.addEventListener('click', () => this.closeNewItemModal());
        }

        const cancelEditItemBtn = document.getElementById('cancel-edit-item');
        if (cancelEditItemBtn) {
            cancelEditItemBtn.addEventListener('click', () => this.closeEditModal());
        }

        // Modal X close buttons
        const newItemModalClose = document.getElementById('close-new-item-modal');
        if (newItemModalClose) {
            newItemModalClose.addEventListener('click', () => this.closeNewItemModal());
        }

        const editItemModalClose = document.getElementById('close-edit-item-modal');
        if (editItemModalClose) {
            editItemModalClose.addEventListener('click', () => this.closeEditModal());
        }

        const cancelDeleteBtn = document.getElementById('cancel-delete');
        if (cancelDeleteBtn) {
            cancelDeleteBtn.addEventListener('click', () => this.closeDeleteModal());
        }

        // Form submissions
        const newItemForm = document.getElementById('new-item-form');
        if (newItemForm) {
            newItemForm.addEventListener('submit', (e) => this.handleNewItemSubmit(e));
        }

        const editItemForm = document.getElementById('edit-item-form');
        if (editItemForm) {
            editItemForm.addEventListener('submit', (e) => this.handleEditItemSubmit(e));
        }



        // Add variant buttons
        const addVariantBtn = document.getElementById('add-variant-btn');
        if (addVariantBtn) {
            addVariantBtn.addEventListener('click', () => this.addVariantRow());
        }

        const addEditVariantBtn = document.getElementById('edit-add-variant-btn');
        if (addEditVariantBtn) {
            addEditVariantBtn.addEventListener('click', () => this.addEditVariantRow());
        }
    }

    async loadInventoryData() {
        try {
            const response = await fetch('/admin/inventory-data');
            const data = await response.json();
            
            if (!data || data.success === false) {
                console.error('Failed to load inventory data:', data.message);
                return;
            }
            this.renderInventoryTable(data.inventory || []);
            this.updateStatistics(data.statistics || {});
            this.populateCategorySelects(data.categories || []);
        } catch (error) {
            console.error('Error loading inventory data:', error);
        }
    }

    async searchInventory() {
        if (this.currentSearch.trim() === '') {
            this.loadInventoryData();
            return;
        }

        try {
            const response = await fetch(`/admin/search-inventory?query=${encodeURIComponent(this.currentSearch)}`);
            const data = await response.json();
            
            if (!data || data.success === false) {
                console.error('Search failed:', data?.message);
                return;
            }
            this.renderInventoryTable(data.data || []);
        } catch (error) {
            console.error('Error searching inventory:', error);
        }
    }

    async filterByStatus() {
        if (this.currentFilter === 'all') {
            this.loadInventoryData();
            return;
        }

        try {
            const response = await fetch(`/admin/filter-inventory-status?status=${encodeURIComponent(this.currentFilter)}`);
            const data = await response.json();
            
            if (!data || data.success === false) {
                console.error('Filter failed:', data?.message);
                return;
            }
            this.renderInventoryTable(data.data || []);
        } catch (error) {
            console.error('Error filtering inventory:', error);
        }
    }

    renderInventoryTable(inventory) {
        const tbody = document.querySelector('#inventory-table tbody');
        if (!tbody) return;

        if (inventory.length === 0) {
            tbody.innerHTML = `
                <tr>
                    <td colspan="9" class="text-center py-8 text-gray-500">
                        <i class="fas fa-box-open text-4xl mb-4"></i>
                        <p class="text-lg">No inventory items found</p>
                        <p class="text-sm">Add your first product to get started</p>
                    </td>
                </tr>
            `;
            return;
        }

        tbody.innerHTML = inventory.map(item => `
            <tr class="hover:bg-gray-50 transition-colors">
                <td class="px-4 py-3">
                    <input type="checkbox" class="inventory-checkbox" value="${item.id}">
                </td>
                <td class="px-4 py-3">
                    <div class="flex items-center space-x-3">
                        <div class="w-12 h-12 bg-gray-200 rounded-lg flex items-center justify-center">
                            <i class="fas fa-image text-gray-400"></i>
                        </div>
                        <div>
                            <div class="font-medium text-gray-900">${item.product_name || item.name || '—'}</div>
                            <div class="text-sm text-gray-500">${item.category}</div>
                        </div>
                    </div>
                </td>
                <td class="px-4 py-3">
                    <span class="px-2 py-1 text-xs font-medium rounded-full ${this.getStatusBadgeClass(item.status)}">
                        ${item.status}
                    </span>
                </td>
                <td class="px-4 py-3 text-sm text-gray-900">${item.current_stock}</td>
                <td class="px-4 py-3 text-sm text-gray-900">₱${item.price}</td>
                <td class="px-4 py-3 text-sm text-gray-900">${item.product_type || item.product_type_label || 'Gown'}</td>
                <td class="px-4 py-3">
                    ${this.renderStatusBadges(item.status_badges || [])}
                </td>
                <td class="px-4 py-3 text-sm text-gray-500">${item.last_updated || ''}</td>
                <td class="px-4 py-3">
                    <div class="flex items-center space-x-2">
                        <button onclick="inventoryPanel.editItem(${item.id})" class="text-blue-600 hover:text-blue-800">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button onclick="inventoryPanel.deleteItem(${item.id})" class="text-red-600 hover:text-red-800">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </td>
            </tr>
        `).join('');

        // Initialize delete handlers
        this.initializeDeleteHandlers();
    }

    renderStatusBadges(badges) {
        if (!badges || badges.length === 0) return '-';
        
        return badges.map(badge => 
            `<span class="inline-block px-2 py-1 text-xs font-medium rounded-full ${badge.class} mr-1">
                ${badge.text}
            </span>`
        ).join('');
    }

    getStatusBadgeClass(status) {
        const classes = {
            'available': 'bg-green-100 text-green-800',
            'rented': 'bg-blue-100 text-blue-800',
            'out_of_stock': 'bg-red-100 text-red-800',
            'low_stock': 'bg-yellow-100 text-yellow-800'
        };
        return classes[status] || 'bg-gray-100 text-gray-800';
    }

    updateStatistics(statistics) {
        const totalItems = document.getElementById('total-inventory-items');
        const availableItems = document.getElementById('available-inventory-items');
        const rentedItems = document.getElementById('rented-inventory-items');
        const lowStockItems = document.getElementById('low-stock-inventory-items');

        if (totalItems) totalItems.textContent = statistics.total_items || 0;
        if (availableItems) availableItems.textContent = statistics.available_items || 0;
        if (rentedItems) rentedItems.textContent = statistics.rented_items || 0;
        if (lowStockItems) lowStockItems.textContent = statistics.low_stock_items || 0;
    }

    async loadCategories() {
        try {
            // If categories already populated, skip
            const selects = document.querySelectorAll('select[name="category_id"], select[name="edit_category_id"]');
            let alreadyHasOptions = false;
            selects.forEach(s => { if (s && s.options && s.options.length > 1) alreadyHasOptions = true; });
            if (alreadyHasOptions) return;

            const response = await fetch('/admin/inventory/categories', {
                headers: { 'Accept': 'application/json' },
                credentials: 'same-origin'
            });

            const contentType = response.headers.get('content-type') || '';
            if (!contentType.includes('application/json')) {
                // Avoid throwing; just bail quietly and rely on initial payload
                const text = await response.text();
                console.warn('Categories endpoint did not return JSON. Status:', response.status, 'Body snippet:', text?.slice(0, 120));
                return;
            }

            const data = await response.json();
            if (data && data.success) {
                this.populateCategorySelects(data.categories || []);
            }
        } catch (error) {
            console.error('Error loading categories:', error);
        }
    }

    populateCategorySelects(categories) {
        const selects = document.querySelectorAll('select[name="category_id"], select[name="edit_category_id"]');
        selects.forEach(select => {
            select.innerHTML = '<option value="">Select Category</option>';
            categories.forEach(category => {
                const option = document.createElement('option');
                option.value = category.id;
                option.textContent = category.name;
                select.appendChild(option);
            });
        });
    }

    openNewItemModal() {
        const modal = document.getElementById('new-item-modal');
        if (modal) {
            modal.classList.remove('hidden');
            this.resetNewItemForm();
        }
    }

    closeNewItemModal() {
        const modal = document.getElementById('new-item-modal');
        if (modal) {
            modal.classList.add('hidden');
        }
    }

    resetNewItemForm() {
        const form = document.getElementById('new-item-form');
        if (form) {
            form.reset();
            document.getElementById('image-preview').style.backgroundImage = 'none';
            document.getElementById('image-preview').innerHTML = '<i class="fas fa-image text-gray-400 text-2xl"></i>';
        }
        this.resetVariants();
    }

    openEditModal() {
        const modal = document.getElementById('edit-item-modal');
        if (modal) {
            modal.classList.remove('hidden');
        }
    }

    closeEditModal() {
        const modal = document.getElementById('edit-item-modal');
        if (modal) {
            modal.classList.add('hidden');
        }
    }

    async editItem(itemId) {
        try {
            const response = await fetch(`/admin/inventory-item/${itemId}`);
            const data = await response.json();
            
            if (data.success) {
                this.populateEditForm(data.product);
                this.openEditModal();
            } else {
                this.showError('Failed to load product details');
            }
        } catch (error) {
            console.error('Error loading product details:', error);
            this.showError('Failed to load product details');
        }
    }

    populateEditForm(product) {
        // Set product ID
        document.getElementById('edit-product-id').value = product.id;
        
        // Populate basic fields
        document.getElementById('edit-name').value = product.product_name || product.name;
        document.getElementById('edit-description').value = product.description || '';
        document.getElementById('edit-base-price').value = product.base_price || product.price;
        document.getElementById('edit-sale-price').value = product.sale_price || '';
        document.getElementById('edit-status').value = product.status || 'available';
        document.getElementById('edit-product-type').value = product.product_type || 'gown';
        
        // Set category (will be populated when categories are loaded)
        if (product.category_id) {
            document.getElementById('edit-category-id').value = product.category_id;
        }
        
        // Set status indicators
        document.getElementById('edit-is-new-arrival').checked = product.is_new_arrival || false;
        document.getElementById('edit-is-new-design').checked = product.is_new_design || false;
        document.getElementById('edit-is-featured').checked = product.is_featured || false;
        document.getElementById('edit-is-customizable').checked = product.is_customizable || false;
        
        // Show current image
        const imagePreview = document.getElementById('edit-image-preview');
        if (product.main_image && product.main_image !== 'default.jpg') {
            imagePreview.style.backgroundImage = `url('/${product.main_image}')`;
            imagePreview.style.backgroundSize = 'cover';
            imagePreview.style.backgroundPosition = 'center';
            imagePreview.innerHTML = '';
        } else {
            imagePreview.style.backgroundImage = 'none';
            imagePreview.innerHTML = '<i class="fas fa-image text-gray-400 text-2xl"></i>';
        }
        
        // Populate variants
        this.populateEditVariants(product.variants || []);
    }

    populateEditVariants(variants) {
        const container = document.getElementById('edit-variants-container');
        if (!container) return;

        container.innerHTML = '';
        
        if (variants.length === 0) {
            this.addEditVariantRow();
            return;
        }

        variants.forEach(variant => {
            this.addEditVariantRow(variant);
        });
    }

    addEditVariantRow(variant = null) {
        const container = document.getElementById('edit-variants-container');
        if (!container) return;

        const variantId = this.variantCounter++;
        const row = document.createElement('div');
        row.className = 'variant-row border rounded-lg p-4 mb-4';
        row.dataset.variantId = variantId;
        if (variant && variant.id) {
            row.dataset.originalId = variant.id;
        }

        row.innerHTML = `
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Variant Name</label>
                    <input type="text" name="edit_variants[${variantId}][name]" value="${variant ? variant.name : ''}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">SKU</label>
                    <input type="text" name="edit_variants[${variantId}][sku]" value="${variant ? variant.sku : ''}" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Stock Quantity</label>
                    <input type="number" name="edit_variants[${variantId}][stock_quantity]" value="${variant ? variant.stock_quantity : 0}" min="0" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Price Adjustment</label>
                    <input type="number" name="edit_variants[${variantId}][price_adjustment]" value="${variant ? variant.price_adjustment : 0}" step="0.01" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
            </div>
            <div class="flex items-center justify-between mt-3">
                <label class="flex items-center">
                    <input type="checkbox" name="edit_variants[${variantId}][is_active]" ${variant && variant.is_active ? 'checked' : 'checked'} class="mr-2">
                    <span class="text-sm text-gray-700">Active</span>
                </label>
                <button type="button" onclick="this.closest('.variant-row').remove()" class="text-red-600 hover:text-red-800 text-sm">
                    <i class="fas fa-trash mr-1"></i>Remove
                </button>
            </div>
        `;

        container.appendChild(row);
    }

    async handleNewItemSubmit(e) {
        e.preventDefault();
        
        const formData = new FormData(e.target);

        // Normalize checkbox values to 1/0 strings so Laravel 'boolean' rule passes
        try {
            const formEl = e.target;
            const boolFields = ['is_featured','is_customizable','is_new_arrival','is_new_design'];
            boolFields.forEach((name) => {
                const el = formEl.querySelector(`[name="${name}"]`);
                if (el) {
                    formData.set(name, el.checked ? '1' : '0');
                }
            });
        } catch (err) {
            console.warn('Failed normalizing checkbox values (new item):', err);
        }
        
        // Handle variants
        const variants = [];
        const variantRows = document.querySelectorAll('.variant-row');
        variantRows.forEach(row => {
            const variantId = row.dataset.variantId;
            const variantData = {
                name: formData.get(`variants[${variantId}][name]`),
                sku: formData.get(`variants[${variantId}][sku]`),
                stock_quantity: parseInt(formData.get(`variants[${variantId}][stock_quantity]`)),
                price_adjustment: parseFloat(formData.get(`variants[${variantId}][price_adjustment]`) || 0),
                is_active: formData.has(`variants[${variantId}][is_active]`)
            };
            variants.push(variantData);
        });
        
        // Add variants to form data
        formData.append('variants', JSON.stringify(variants));
        
        try {
            const response = await fetch('/admin/inventory-item', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                },
                body: formData,
                credentials: 'same-origin'
            });

            const isJson = (response.headers.get('content-type') || '').includes('application/json');
            if (!isJson) {
                const text = await response.text();
                console.error('Non-JSON response when adding item:', text);
                this.showError('Failed to add item: Server returned HTML (likely a validation or auth redirect).');
                return;
            }

            const result = await response.json();
            if (result && result.success) {
                this.showSuccess(result.message || 'Item added');
                this.closeNewItemModal();
                this.loadInventoryData();
            } else {
                const extra = result && result.error ? `\n${result.error}` : '';
                this.showError(((result && result.message) || 'Failed to add item') + extra);
            }
        } catch (error) {
            console.error('Error adding item:', error);
            this.showError('Failed to add item: ' + error.message);
        }
    }

    async handleEditItemSubmit(e) {
        e.preventDefault();
        
        const formData = new FormData(e.target);
        const productId = document.getElementById('edit-product-id').value;

        // Normalize checkbox values to 1/0 strings for edit form
        try {
            const formEl = e.target;
            const boolFields = ['edit_is_featured','edit_is_customizable','edit_is_new_arrival','edit_is_new_design'];
            boolFields.forEach((name) => {
                const el = formEl.querySelector(`[name="${name}"]`);
                if (el) {
                    formData.set(name, el.checked ? '1' : '0');
                }
            });
        } catch (err) {
            console.warn('Failed normalizing checkbox values (edit item):', err);
        }
        
        // Handle variants
        const variants = [];
        const variantRows = document.querySelectorAll('#edit-variants-container .variant-row');
        variantRows.forEach(row => {
            const variantId = row.dataset.variantId;
            const originalId = row.dataset.originalId;
            const variantData = {
                id: originalId || null, // null for new variants
                name: formData.get(`edit_variants[${variantId}][name]`),
                sku: formData.get(`edit_variants[${variantId}][sku]`),
                stock_quantity: parseInt(formData.get(`edit_variants[${variantId}][stock_quantity]`)),
                price_adjustment: parseFloat(formData.get(`edit_variants[${variantId}][price_adjustment]`) || 0),
                is_active: formData.has(`edit_variants[${variantId}][is_active]`)
            };
            variants.push(variantData);
        });
        
        // Add variants to form data
        formData.append('variants', JSON.stringify(variants));
        
        try {
            const response = await fetch(`/admin/inventory-item/${productId}`, {
                method: 'POST', // Use POST for file uploads
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                },
                body: formData,
                credentials: 'same-origin'
            });

            const isJson = (response.headers.get('content-type') || '').includes('application/json');
            if (!isJson) {
                const text = await response.text();
                console.error('Non-JSON response when updating item:', text);
                this.showError('Failed to update item: Server returned HTML (likely a validation or auth redirect).');
                return;
            }

            const result = await response.json();
            if (result && result.success) {
                this.showSuccess(result.message || 'Item updated');
                this.closeEditModal();
                this.loadInventoryData();
            } else {
                const extra = result && result.error ? `\n${result.error}` : '';
                this.showError(((result && result.message) || 'Failed to update item') + extra);
            }
        } catch (error) {
            console.error('Error updating item:', error);
            this.showError('Failed to update item: ' + error.message);
        }
    }

    deleteItem(itemId) {
        if (confirm('Are you sure you want to delete this product? This action cannot be undone.')) {
            this.confirmDelete(itemId);
        }
    }

    async confirmDelete(itemId) {
        try {
            const response = await fetch(`/admin/inventory-item/${itemId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            });
            
            const result = await response.json();
            
            if (result.success) {
                this.showSuccess(result.message);
                this.loadInventoryData();
            } else {
                this.showError(result.message || 'Failed to delete item');
            }
        } catch (error) {
            console.error('Error deleting item:', error);
            this.showError('Failed to delete item: ' + error.message);
        }
    }

    initializeDeleteHandlers() {
        // Delete handlers are now set up in renderInventoryTable
    }





    closeDeleteModal() {
        const modal = document.getElementById('delete-confirmation-modal');
        if (modal) {
            modal.classList.add('hidden');
        }
    }

    async addStock(variantId, quantity) {
        try {
            const response = await fetch('/admin/inventory/add-stock', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    variant_id: variantId,
                    quantity: quantity
                })
            });
            
            const result = await response.json();
            
            if (result.success) {
                this.showSuccess(result.message);
                this.loadInventoryData();
            } else {
                this.showError(result.message || 'Failed to add stock');
            }
        } catch (error) {
            console.error('Error adding stock:', error);
            this.showError('Failed to add stock: ' + error.message);
        }
    }

    async exportInventory() {
        try {
            const response = await fetch('/admin/export-inventory');
            const blob = await response.blob();
            
            const url = window.URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = 'inventory-export.xlsx';
            document.body.appendChild(a);
            a.click();
            window.URL.revokeObjectURL(url);
            document.body.removeChild(a);
            
            this.showSuccess('Inventory exported successfully');
        } catch (error) {
            console.error('Error exporting inventory:', error);
            this.showError('Failed to export inventory');
        }
    }

    resetVariants() {
        const container = document.getElementById('variants-container');
        if (container) {
            container.innerHTML = '';
            this.addVariantRow();
        }
    }

    addVariantRow() {
        const container = document.getElementById('variants-container');
        if (!container) return;

        const variantId = this.variantCounter++;
        const row = document.createElement('div');
        row.className = 'variant-row border rounded-lg p-4 mb-4';
        row.dataset.variantId = variantId;

        row.innerHTML = `
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Variant Name</label>
                    <input type="text" name="variants[${variantId}][name]" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">SKU</label>
                    <input type="text" name="variants[${variantId}][sku]" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Stock Quantity</label>
                    <input type="number" name="variants[${variantId}][stock_quantity]" value="0" min="0" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Price Adjustment</label>
                    <input type="number" name="variants[${variantId}][price_adjustment]" value="0" step="0.01" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
            </div>
            <div class="flex items-center justify-between mt-3">
                <label class="flex items-center">
                    <input type="checkbox" name="variants[${variantId}][is_active]" checked class="mr-2">
                    <span class="text-sm text-gray-700">Active</span>
                </label>
                <button type="button" onclick="this.closest('.variant-row').remove()" class="text-red-600 hover:text-red-800 text-sm">
                    <i class="fas fa-trash mr-1"></i>Remove
                </button>
            </div>
        `;

        container.appendChild(row);
    }

    previewImage(input) {
        const preview = document.getElementById('image-preview');
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.style.backgroundImage = 'url(' + e.target.result + ')';
                preview.style.backgroundSize = 'cover';
                preview.style.backgroundPosition = 'center';
                preview.innerHTML = ''; // Clear the icon
            }
            reader.readAsDataURL(input.files[0]);
        } else {
            preview.style.backgroundImage = 'none';
            preview.innerHTML = '<i class="fas fa-image text-gray-400 text-2xl"></i>';
        }
    }

    previewEditImage(input) {
        const preview = document.getElementById('edit-image-preview');
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.style.backgroundImage = 'url(' + e.target.result + ')';
                preview.style.backgroundSize = 'cover';
                preview.style.backgroundPosition = 'center';
                preview.innerHTML = ''; // Clear the icon
            }
            reader.readAsDataURL(input.files[0]);
        } else {
            preview.style.backgroundImage = 'none';
            preview.innerHTML = '<i class="fas fa-image text-gray-400 text-2xl"></i>';
        }
    }

    showSuccess(message) {
        // Create a simple toast notification
        const toast = document.createElement('div');
        toast.className = 'fixed top-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg z-50';
        toast.textContent = message;
        document.body.appendChild(toast);
        
        setTimeout(() => {
            toast.remove();
        }, 3000);
    }

    showError(message) {
        // Create a simple toast notification
        const toast = document.createElement('div');
        toast.className = 'fixed top-4 right-4 bg-red-500 text-white px-6 py-3 rounded-lg shadow-lg z-50';
        toast.textContent = message;
        document.body.appendChild(toast);
        
        setTimeout(() => {
            toast.remove();
        }, 3000);
    }


}

// Initialize the inventory panel when the DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    window.inventoryPanel = new InventoryPanelManager();
});
