// resources/js/pos.js
// POS front-end logic: product search via API, checkout via API, simple offline skeleton using localStorage.

export function erpPosTerminal(options) {
    const branchId = options.branchId;

    return {
        branchId,
        search: '',
        isSearching: false,
        products: [],
        cart: [],
        isCheckingOut: false,
        message: null,
        offline: !window.navigator.onLine,

        init() {
            this.loadCart();
            this.loadOfflineQueue();

            window.addEventListener('online', () => {
                this.offline = false;
            });

            window.addEventListener('offline', () => {
                this.offline = true;
            });
        },

        get storageKey() {
            return `erp_pos_cart_branch_${this.branchId}`;
        },

        get offlineQueueKey() {
            return `erp_pos_offline_sales_branch_${this.branchId}`;
        },

        loadCart() {
            try {
                const raw = window.localStorage.getItem(this.storageKey);
                this.cart = raw ? JSON.parse(raw) : [];
            } catch (e) {
                console.error('Failed to load POS cart from storage', e);
                this.cart = [];
            }
        },

        persistCart() {
            try {
                window.localStorage.setItem(this.storageKey, JSON.stringify(this.cart));
            } catch (e) {
                console.error('Failed to persist POS cart to storage', e);
            }
        },

        loadOfflineQueue() {
            try {
                const raw = window.localStorage.getItem(this.offlineQueueKey);
                this.offlineQueue = raw ? JSON.parse(raw) : [];
            } catch (e) {
                console.error('Failed to load POS offline queue', e);
                this.offlineQueue = [];
            }
        },

        persistOfflineQueue() {
            try {
                window.localStorage.setItem(this.offlineQueueKey, JSON.stringify(this.offlineQueue ?? []));
            } catch (e) {
                console.error('Failed to persist POS offline queue', e);
            }
        },

        
        async syncOfflineQueue() {
            if (!this.offlineQueue || !this.offlineQueue.length) {
                this.message = {
                    type: 'info',
                    text: 'لا توجد طلبات Offline للمزامنة.',
                };
                return;
            }

            const queue = [...this.offlineQueue];

            for (const item of queue) {
                try {
                    await window.axios.post(`/api/v1/branches/${this.branchId}/pos/checkout`, item.payload ?? {});
                } catch (error) {
                    console.error('Failed to sync offline sale', error);
                    this.message = {
                        type: 'error',
                        text: 'حدث خطأ أثناء مزامنة بعض طلبات الـ Offline.',
                    };
                    return;
                }
            }

            this.offlineQueue = [];
            this.persistOfflineQueue();

            this.message = {
                type: 'success',
                text: 'تمت مزامنة كل الطلبات الـ Offline بنجاح.',
            };
        },

        get total() {
            return (this.cart || []).reduce((sum, item) => {
                const qty = Number(item.qty ?? 0);
                const price = Number(item.price ?? 0);
                return sum + qty * price;
            }, 0);
        },

        clearMessage() {
            this.message = null;
        },

        async fetchProducts() {
            if (!this.search || this.search.length < 2) {
                this.products = [];
                return;
            }

            if (!window.navigator.onLine) {
                this.message = {
                    type: 'info',
                    text: 'الاتصال غير متاح، لا يمكن البحث عن المنتجات الآن.',
                };
                return;
            }

            this.isSearching = true;
            this.products = [];

            try {
                const response = await window.axios.get(`/api/v1/branches/${this.branchId}/products/search`, {
                    params: { q: this.search },
                });

                let data = response.data;

                // Accept multiple possible API shapes: {data:[...]}, {data:{data:[...]}}, or plain array
                if (Array.isArray(data)) {
                    this.products = data;
                } else if (data && Array.isArray(data.data)) {
                    this.products = data.data;
                } else if (data && data.data && Array.isArray(data.data.data)) {
                    this.products = data.data.data;
                } else {
                    this.products = [];
                }
            } catch (error) {
                console.error('POS search error', error);
                this.message = {
                    type: 'error',
                    text: error?.response?.data?.message ?? 'حدث خطأ أثناء البحث عن المنتجات.',
                };
            } finally {
                this.isSearching = false;
            }
        },

        addProduct(product) {
            if (!product) {
                return;
            }

            const id = product.id ?? product.product_id;
            if (!id) {
                return;
            }

            const existingIndex = this.cart.findIndex((item) => item.product_id === id);

            if (existingIndex !== -1) {
                this.cart[existingIndex].qty = Number(this.cart[existingIndex].qty ?? 0) + 1;
            } else {
                this.cart.push({
                    product_id: id,
                    name: product.name ?? product.label ?? 'Item',
                    qty: 1,
                    price: Number(product.price ?? product.sale_price ?? 0),
                    discount: 0,
                    percent: false,
                    tax_id: product.tax_id ?? null,
                });
            }

            this.persistCart();
        },

        removeItem(index) {
            if (index < 0 || index >= this.cart.length) {
                return;
            }
            this.cart.splice(index, 1);
            this.persistCart();
        },

        updateQty(index, qty) {
            if (index < 0 || index >= this.cart.length) {
                return;
            }
            const value = Number(qty ?? 0);
            this.cart[index].qty = value > 0 ? value : 1;
            this.persistCart();
        },

        updatePrice(index, price) {
            if (index < 0 || index >= this.cart.length) {
                return;
            }
            const value = Number(price ?? 0);
            this.cart[index].price = value >= 0 ? value : 0;
            this.persistCart();
        },

        enqueueOfflineSale(payload) {
            if (!this.offlineQueue) {
                this.offlineQueue = [];
            }
            this.offlineQueue.push({
                payload,
                queued_at: new Date().toISOString(),
            });
            this.persistOfflineQueue();
        },

        async checkout() {
            if (!this.cart.length) {
                this.message = {
                    type: 'info',
                    text: 'لا توجد عناصر في السلة.',
                };
                return;
            }

            const items = this.cart.map((item) => ({
                product_id: item.product_id,
                qty: Number(item.qty ?? 1),
                price: Number(item.price ?? 0),
                discount: Number(item.discount ?? 0),
                percent: !!item.percent,
                tax_id: item.tax_id ?? null,
            }));

            const payload = {
                items,
            };

            // Offline: enqueue and clear cart
            if (this.offline) {
                this.enqueueOfflineSale(payload);
                this.cart = [];
                this.persistCart();
                this.message = {
                    type: 'info',
                    text: 'تم حفظ الطلب في وضع عدم الاتصال. سيتم مزامنته عند توفر الإنترنت (Skeleton).',
                };
                return;
            }

            this.isCheckingOut = true;
            this.clearMessage();

            try {
                const response = await window.axios.post(`/api/v1/branches/${this.branchId}/pos/checkout`, payload);

                // Try to extract a user-friendly message
                const data = response.data ?? {};
                let msg = data.message ?? data.status ?? 'تم تنفيذ عملية البيع بنجاح.';

                this.cart = [];
                this.persistCart();

                this.message = {
                    type: 'success',
                    text: msg,
                };
            } catch (error) {
                console.error('POS checkout error', error);
                this.message = {
                    type: 'error',
                    text: error?.response?.data?.message ?? 'فشل تنفيذ عملية البيع.',
                };
            } finally {
                this.isCheckingOut = false;
            }
        },
    };
}

window.erpPosTerminal = erpPosTerminal;
