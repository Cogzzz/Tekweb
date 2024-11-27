document.addEventListener('DOMContentLoaded', () => {
    const orderContainer = document.querySelector('body');
    const history = JSON.parse(localStorage.getItem('purchaseHistory')) || [];

    if (history.length === 0) {
        orderContainer.innerHTML = '<p>Tidak ada riwayat pembelian.</p>';
        return;
    }  

    history.reverse();

    history.forEach(transaction => {
        // Perbaiki harga total
        const totalPrice = parseFloat(transaction.totalPrice) || 0;

        // Buat elemen untuk setiap transaksi
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
                        // Tambahkan "../" sebelum path jika perlu
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
