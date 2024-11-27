document.addEventListener('DOMContentLoaded', () => {
    const checkoutTableBody = document.getElementById('checkout-table-body');
    const grandTotalElement = document.getElementById('grandTotal');
    
    // Muat data keranjang dari localStorage
    const cart = JSON.parse(localStorage.getItem('cart')) || [];
    
    // Fungsi untuk membersihkan format harga menggunakan NumberFormat
    function parsePriceIntl(price) {
        const formatter = new Intl.NumberFormat('id-ID');
        return formatter.formatToParts(price).reduce((acc, part) => {
            if (part.type === 'integer' || part.type === 'fraction') {
                acc += part.value; // Gabungkan bagian angka dan pecahan
            }
            return acc;
        }, '');
    }
    
    // Fungsi untuk memperbarui tampilan checkout
    function updateCheckoutUI() {
        checkoutTableBody.innerHTML = '';
        let grandTotal = 0;

        cart.forEach((item) => {
            const imgPath = item.img.startsWith('asset/') ? `../${item.img}` : item.img;
            const price = parseFloat(parsePriceIntl(item.price)); // Parsing harga menggunakan Intl
            const subtotal = item.quantity * price; // Hitung subtotal
            const row = document.createElement('tr');
            row.innerHTML = ` 
                <td> <img src="${imgPath}" alt="${item.name}"  style="width: 50px; height: 50px; object-fit: cover; margin-right: 10px;"> ${item.name}</td>
                <td>Rp.${price.toLocaleString('id-ID')}</td>
                <td>${item.quantity}</td>
                <td>Rp.${subtotal.toLocaleString('id-ID')}</td>
            `;
            grandTotal += subtotal;
            checkoutTableBody.appendChild(row);
        });

        grandTotalElement.textContent = `Rp.${grandTotal.toLocaleString()}`;
    }
    
    // Inisialisasi tampilan checkout
    updateCheckoutUI();
    
    // Tombol pembayaran
    const payButton = document.getElementById('payButton');
    
    // Pastikan event listener hanya terpasang sekali
    if (!payButton.hasEventListener) {
        payButton.addEventListener('click', () => {
            if (cart.length === 0) {
                alert('Keranjang belanja kosong!');
                return;
            }

            // Simpan transaksi ke riwayat
            const history = JSON.parse(localStorage.getItem('purchaseHistory')) || [];
            const transaction = {
                orderNumber: Date.now(), // Gunakan timestamp sebagai nomor order
                status: 'Dikirim', // Status default
                totalPrice: cart.reduce((total, item) => total + item.quantity * parseFloat(item.price), 0),
                items: cart.map(item => ({
                    name: item.name,
                    price: item.price,
                    quantity: item.quantity,
                    img: item.img,
                })),
                returnDeadline: new Date(Date.now() + 7 * 24 * 60 * 60 * 1000).toLocaleDateString(), // +7 hari dari hari ini
            };
            
            // Pastikan transaksi belum ada sebelumnya
            const transactionExists = history.some(existingTransaction => existingTransaction.orderNumber === transaction.orderNumber);
            if (!transactionExists) {
                history.push(transaction);
                localStorage.setItem('purchaseHistory', JSON.stringify(history));
            }

            // Hapus data keranjang
            localStorage.removeItem('cart');
            
            // Alihkan pengguna ke halaman riwayat transaksi
            window.location.href = '../history/history.html'; // Anda bisa mengalihkan pengguna ke halaman riwayat transaksi setelah pembayaran berhasil
        });
    }
});
