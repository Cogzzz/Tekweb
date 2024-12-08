document.addEventListener('DOMContentLoaded', () => {
    const orderContainer = document.querySelector('body');
    let history = JSON.parse(localStorage.getItem('purchaseHistory')) || [];

    console.log("Data awal di localStorage:", history);

    function isValidTransaction(transaction) {
        return transaction && typeof transaction === 'object' && transaction.orderNumber && Array.isArray(transaction.items);
    }

    history = history.filter(isValidTransaction);

    function removeDuplicateOrders(history) {
        const uniqueOrders = [];
        const orderNumbers = new Set();

        history.forEach(transaction => {
            if (!orderNumbers.has(transaction.orderNumber)) {
                orderNumbers.add(transaction.orderNumber);
                uniqueOrders.push(transaction);
            }
        });

        return uniqueOrders;
    }

    history = removeDuplicateOrders(history);
    console.log("Data setelah menghapus duplikat:", history);

    localStorage.setItem('purchaseHistory', JSON.stringify(history));

    if (history.length === 0) {
        orderContainer.innerHTML = '<p>Tidak ada riwayat pembelian.</p>';
        return;
    }

    history.reverse(); 
    history.forEach(transaction => {
        const totalPrice = parseFloat(transaction.totalPrice) || 0;

        const transactionElement = document.createElement('div');
        transactionElement.classList.add('order-container');
        transactionElement.innerHTML = `
            <div class="order-header">
                <div class="order-info">
                    <p>No. <span class="order-number">${transaction.orderNumber}</span></p>
                    <p class="order-status shipped">${transaction.status}</p>
                    <p class="order-price">IDR ${totalPrice.toLocaleString('id-ID')}</p>
                </div>
            </div>
            <div class="order-body">
                ${transaction.items
                    .map(item => {
                        const imgPath = item.img.startsWith('asset/') ? `../${item.img}` : item.img;
                        return `
                            <div class="order-item">
                                <img src="${imgPath}" alt="${item.name}" class="product-image" style="width: 50px; height: 50px; object-fit: cover;">
                                <p>${item.name} x${item.quantity}</p>
                            </div>
                        `;
                    })
                    .join('')}
            </div>
            <div class="order-footer">
                <p class="return-info">
                    <i class="info-icon">i</i>
                    Periode pengembalian berakhir pada <span>${transaction.returnDeadline}</span>
                </p>
            </div>
        `;
        orderContainer.appendChild(transactionElement);
    });
});

// untuk menambahkan transaksi baru ke localStorage
function addTransactionToLocalStorage(newTransaction) {
    const history = JSON.parse(localStorage.getItem('purchaseHistory')) || [];
    console.log("Sebelum tambah:", history);

    const exists = history.some(transaction => transaction.orderNumber === newTransaction.orderNumber);

    if (!exists) {
        history.push(newTransaction);
        console.log("Setelah tambah:", history);
        localStorage.setItem('purchaseHistory', JSON.stringify(history));
    } else {
        console.log("Transaksi sudah ada:", newTransaction.orderNumber);
    }
}
