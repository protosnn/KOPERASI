-- Update tabel pinjaman untuk menambahkan status 'tolak'
ALTER TABLE `pinjaman` 
MODIFY `status` enum('pending','acc','tolak') NOT NULL;
