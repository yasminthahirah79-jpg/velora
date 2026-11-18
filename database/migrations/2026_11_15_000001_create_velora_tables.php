<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('users', fn (Blueprint $t) => $t->string('role')->default('admin')->after('email')); // admin|pembelian|keuangan|pimpinan

        Schema::create('suppliers', function (Blueprint $t) {
            $t->id(); $t->string('kode')->unique(); $t->string('nama'); $t->text('alamat')->nullable();
            $t->string('telepon')->nullable(); $t->string('email')->nullable(); $t->string('nama_kontak')->nullable();
            $t->string('bank')->nullable(); $t->string('no_rekening')->nullable(); $t->string('status')->default('aktif'); $t->timestamps();
        });
        Schema::create('categories', function (Blueprint $t) { $t->id(); $t->string('nama'); $t->timestamps(); });
        Schema::create('products', function (Blueprint $t) {
            $t->id(); $t->string('kode')->unique(); $t->string('nama'); $t->foreignId('category_id')->constrained();
            $t->string('satuan'); $t->decimal('harga_beli', 15, 2)->default(0); $t->integer('stok')->default(0); $t->timestamps();
        });
        Schema::create('purchases', function (Blueprint $t) {
            $t->id(); $t->string('no_pembelian')->unique(); $t->string('no_faktur'); $t->foreignId('supplier_id')->constrained();
            $t->date('tanggal'); $t->date('jatuh_tempo'); $t->decimal('total', 15, 2)->default(0);
            $t->string('status')->default('tercatat'); // tercatat|dibatalkan
            $t->foreignId('created_by')->constrained('users'); $t->timestamps();
        });
        Schema::create('purchase_items', function (Blueprint $t) {
            $t->id(); $t->foreignId('purchase_id')->constrained()->cascadeOnDelete(); $t->foreignId('product_id')->constrained();
            $t->integer('qty'); $t->decimal('harga', 15, 2); $t->decimal('subtotal', 15, 2); $t->timestamps();
        });
        Schema::create('payables', function (Blueprint $t) {
            $t->id(); $t->string('no_tagihan')->unique(); $t->foreignId('purchase_id')->unique()->constrained();
            $t->foreignId('supplier_id')->constrained(); $t->decimal('total', 15, 2); $t->date('jatuh_tempo');
            $t->string('status')->default('belum lunas'); // belum lunas|sebagian|lunas
            $t->timestamps();
        });
        Schema::create('payments', function (Blueprint $t) {
            $t->id(); $t->foreignId('payable_id')->constrained(); $t->date('tanggal'); $t->decimal('jumlah', 15, 2);
            $t->string('metode'); $t->string('bukti_bayar')->nullable(); $t->timestamps();
        });
        Schema::create('cash_transactions', function (Blueprint $t) {
            $t->id(); $t->date('tanggal'); $t->string('jenis'); $t->string('kategori'); $t->decimal('jumlah', 15, 2);
            $t->foreignId('payment_id')->nullable()->constrained(); $t->string('keterangan')->nullable(); $t->timestamps();
        });
        Schema::create('activity_logs', function (Blueprint $t) {
            $t->id(); $t->foreignId('user_id')->constrained(); $t->string('aktivitas'); $t->timestamps();
        });
    }

    public function down(): void
    {
        foreach (['activity_logs', 'cash_transactions', 'payments', 'payables', 'purchase_items', 'purchases', 'products', 'categories', 'suppliers'] as $t) Schema::dropIfExists($t);
        Schema::table('users', fn (Blueprint $t) => $t->dropColumn('role'));
    }
};
