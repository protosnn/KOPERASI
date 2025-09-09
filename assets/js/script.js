// Fungsi untuk format currency
function formatCurrency(amount) {
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR'
    }).format(amount);
}

// Fungsi untuk menghitung angsuran
function hitungAngsuran(jumlahPinjaman, bunga, tenor) {
    const bungaBulanan = (bunga / 100) / 12;
    const pembayaranBulanan = (jumlahPinjaman * bungaBulanan * Math.pow(1 + bungaBulanan, tenor)) / 
                             (Math.pow(1 + bungaBulanan, tenor) - 1);
    return pembayaranBulanan;
}

// Fungsi untuk menghitung denda
function hitungDenda(jumlahAngsuran, hariTerlambat) {
    const persenDenda = 0.1; // 0.1% per hari
    return (jumlahAngsuran * persenDenda * hariTerlambat) / 100;
}

// Event listener untuk form pinjaman
document.addEventListener('DOMContentLoaded', function() {
    const formPinjaman = document.querySelector('#formPinjaman');
    if (formPinjaman) {
        formPinjaman.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const jumlahPinjaman = parseFloat(document.querySelector('#jumlah').value);
            const bunga = parseFloat(document.querySelector('#bunga').value);
            const tenor = parseInt(document.querySelector('#tenor').value);
            
            const angsuranBulanan = hitungAngsuran(jumlahPinjaman, bunga, tenor);
            alert('Angsuran bulanan: ' + formatCurrency(angsuranBulanan));
        });
    }
});

// Toggle form nasabah baru
function toggleFormNasabah() {
    const form = document.getElementById('formNasabahBaru');
    if (form) {
        form.style.display = form.style.display === 'none' ? 'block' : 'none';
    }
}

// Validasi input numerik
function validateNumber(event) {
    if (!/[0-9]/.test(event.key) && event.key !== 'Backspace' && event.key !== '.') {
        event.preventDefault();
    }
}

// Konfirmasi pembayaran
function konfirmasiPembayaran() {
    const konfirmasi = confirm('Apakah Anda yakin akan memproses pembayaran ini?');
    if (konfirmasi) {
        // Proses pembayaran
        alert('Pembayaran berhasil diproses!');
        return true;
    }
    return false;
}

// Update total pembayaran
function updateTotalPembayaran() {
    const angsuran = parseFloat(document.getElementById('jumlah_angsuran').value) || 0;
    const denda = parseFloat(document.getElementById('denda').value) || 0;
    const total = angsuran + denda;
    document.getElementById('total_bayar').value = total;
}
